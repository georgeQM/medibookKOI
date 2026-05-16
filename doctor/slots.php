<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: ../auth/login.php');
    exit;
}

$doctorStmt = $pdo->prepare("SELECT * FROM doctors WHERE user_id = ?");
$doctorStmt->execute([$_SESSION['user_id']]);
$doctor = $doctorStmt->fetch();

if (!$doctor) {
    header('Location: ../auth/login.php');
    exit;
}

$slotError   = '';
$slotSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $dow        = trim($_POST['day_of_week'] ?? '');
        $startTime  = trim($_POST['start_time']  ?? '');
        $endTime    = trim($_POST['end_time']     ?? '');

        $validDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

        if (!in_array($dow, $validDays))   $slotError = 'Please select a valid day of the week.';
        elseif ($startTime === '')          $slotError = 'Please enter a start time.';
        elseif ($endTime === '')            $slotError = 'Please enter an end time.';
        elseif ($startTime >= $endTime)     $slotError = 'Start time must be before end time.';
        else {
            // Check for duplicate day + start_time for this doctor
            $dup = $pdo->prepare("SELECT id FROM time_slots WHERE doctor_id = ? AND day_of_week = ? AND start_time = ?");
            $dup->execute([$doctor['id'], $dow, $startTime]);
            if ($dup->fetch()) {
                $slotError = 'A slot already exists for ' . htmlspecialchars($dow) . ' at ' . htmlspecialchars($startTime) . '.';
            } else {
                $ins = $pdo->prepare("INSERT INTO time_slots (doctor_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
                $ins->execute([$doctor['id'], $dow, $startTime, $endTime]);

                $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, 'slot_added')");
                $log->execute([$_SESSION['user_id']]);

                $slotSuccess = 'Time slot added successfully.';
            }
        }

    } elseif ($action === 'delete') {
        $slotId = (int)($_POST['slot_id'] ?? 0);
        if ($slotId > 0) {
            $del = $pdo->prepare("DELETE FROM time_slots WHERE id = ? AND doctor_id = ?");
            $del->execute([$slotId, $doctor['id']]);

            $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, 'slot_deleted')");
            $log->execute([$_SESSION['user_id']]);

            $slotSuccess = 'Time slot removed.';
        }
    }
}

// Reload slots after any changes
$slotsStmt = $pdo->prepare("
    SELECT * FROM time_slots WHERE doctor_id = ?
    ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), start_time
");
$slotsStmt->execute([$doctor['id']]);
$slots = $slotsStmt->fetchAll();

$pageTitle = 'Manage Slots';
include __DIR__ . '/../includes/header.php';
?>
<style>
  .doctor-nav { display:flex; gap:1rem; margin-bottom:2rem; flex-wrap:wrap; }
  .doctor-nav a { padding:0.5rem 1.2rem; background:var(--primary,#0077b6); color:#fff; border-radius:8px; text-decoration:none; font-size:0.9rem; }
  .doctor-nav a:hover { opacity:0.85; }
  .slot-form { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:1.75rem; max-width:520px; margin-bottom:2.5rem; }
  .slot-form h2 { margin-bottom:1.25rem; }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:0.75rem 1rem; text-align:left; border-bottom:1px solid #e2e8f0; font-size:0.9rem; }
  th { background:#f7fafc; font-weight:600; }
  .btn-delete { padding:0.3rem 0.8rem; background:#dc2626; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:0.82rem; }
  .btn-delete:hover { background:#b91c1c; }
  .alert-success { background:#d1fae5; color:#065f46; padding:1rem; border-radius:8px; margin-bottom:1.5rem; }
  .alert-error   { background:#fee2e2; color:#991b1b; padding:1rem; border-radius:8px; margin-bottom:1.5rem; }
</style>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Manage Time Slots</h1>
      <p>Add or remove your available appointment slots.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <nav class="doctor-nav" aria-label="Doctor navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="slots.php">Manage Slots</a>
        <a href="../auth/logout.php">Log Out</a>
      </nav>

      <?php if ($slotSuccess): ?>
        <div class="alert-success" role="status"><?= htmlspecialchars($slotSuccess) ?></div>
      <?php endif; ?>
      <?php if ($slotError): ?>
        <div class="alert-error" role="alert"><?= htmlspecialchars($slotError) ?></div>
      <?php endif; ?>

      <!-- ADD SLOT FORM -->
      <div class="slot-form">
        <h2>Add a New Slot</h2>
        <form method="POST" action="">
          <input type="hidden" name="action" value="add" />

          <div class="form-group">
            <label for="day_of_week">Day of the Week *</label>
            <select id="day_of_week" name="day_of_week" required>
              <option value="">— Select a day —</option>
              <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day): ?>
                <option value="<?= $day ?>" <?= ($_POST['day_of_week'] ?? '') === $day ? 'selected' : '' ?>><?= $day ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="grid-2" style="gap:1rem;">
            <div class="form-group">
              <label for="start_time">Start Time *</label>
              <input type="time" id="start_time" name="start_time" required value="<?= htmlspecialchars($_POST['start_time'] ?? '') ?>" />
            </div>
            <div class="form-group">
              <label for="end_time">End Time *</label>
              <input type="time" id="end_time" name="end_time" required value="<?= htmlspecialchars($_POST['end_time'] ?? '') ?>" />
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="margin-top:0.25rem;">Add Slot</button>
        </form>
      </div>

      <!-- EXISTING SLOTS -->
      <h2 style="margin-bottom:1rem;">Your Current Slots</h2>

      <?php if (empty($slots)): ?>
        <p style="color:#666;">No time slots added yet.</p>
      <?php else: ?>
        <div style="overflow-x:auto;">
          <table aria-label="Your time slots">
            <thead>
              <tr>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($slots as $sl): ?>
              <tr>
                <td><?= htmlspecialchars($sl['day_of_week']) ?></td>
                <td><?= substr($sl['start_time'], 0, 5) ?></td>
                <td><?= substr($sl['end_time'],   0, 5) ?></td>
                <td>
                  <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Remove this slot?');">
                    <input type="hidden" name="action"  value="delete" />
                    <input type="hidden" name="slot_id" value="<?= $sl['id'] ?>" />
                    <button type="submit" class="btn-delete">Remove</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </div>
  </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
