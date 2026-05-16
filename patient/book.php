<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: ../auth/login.php');
    exit;
}

$errors = [];
$step   = 1;
$selectedDoctorId = null;
$selectedDoctor   = null;
$slots            = [];
$post             = [];

// Fetch all doctors for step 1 select
$doctors = $pdo->query("
    SELECT d.id, u.name, d.specialty
    FROM doctors d
    JOIN users u ON d.user_id = u.id
    ORDER BY u.name ASC
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'select_doctor') {
        $selectedDoctorId = (int)($_POST['doctor_id'] ?? 0);
        if ($selectedDoctorId > 0) {
            $ds = $pdo->prepare("SELECT d.id, u.name, d.specialty FROM doctors d JOIN users u ON d.user_id = u.id WHERE d.id = ?");
            $ds->execute([$selectedDoctorId]);
            $selectedDoctor = $ds->fetch();
        }
        if (!$selectedDoctor) {
            $errors[] = 'Please select a valid doctor.';
        } else {
            $ss = $pdo->prepare("SELECT * FROM time_slots WHERE doctor_id = ? ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), start_time");
            $ss->execute([$selectedDoctorId]);
            $slots = $ss->fetchAll();
            $step  = 2;
        }

    } elseif ($action === 'book') {
        $selectedDoctorId = (int)($_POST['doctor_id'] ?? 0);
        $slotId           = (int)($_POST['slot_id']   ?? 0);
        $date             = trim($_POST['date']   ?? '');
        $notes            = trim($_POST['notes']  ?? '');
        $post             = compact('date', 'notes');

        // Re-load doctor and slots for re-render on error
        if ($selectedDoctorId > 0) {
            $ds = $pdo->prepare("SELECT d.id, u.name, d.specialty FROM doctors d JOIN users u ON d.user_id = u.id WHERE d.id = ?");
            $ds->execute([$selectedDoctorId]);
            $selectedDoctor = $ds->fetch();

            $ss = $pdo->prepare("SELECT * FROM time_slots WHERE doctor_id = ? ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), start_time");
            $ss->execute([$selectedDoctorId]);
            $slots = $ss->fetchAll();
        }

        if (!$selectedDoctor)       $errors[] = 'Invalid doctor selected.';
        if ($slotId <= 0)           $errors[] = 'Please select a time slot.';
        if ($date === '') {
            $errors[] = 'Please select a date.';
        } elseif ($date < date('Y-m-d')) {
            $errors[] = 'Please select a future date.';
        }

        // Validate day-of-week matches slot
        if (empty($errors) && $slotId > 0 && $date !== '') {
            $slotCheck = $pdo->prepare("SELECT * FROM time_slots WHERE id = ? AND doctor_id = ?");
            $slotCheck->execute([$slotId, $selectedDoctorId]);
            $chosenSlot = $slotCheck->fetch();

            if (!$chosenSlot) {
                $errors[] = 'Invalid time slot.';
            } elseif (date('l', strtotime($date)) !== $chosenSlot['day_of_week']) {
                $errors[] = 'The selected date is not a ' . $chosenSlot['day_of_week'] . '. Please pick a ' . $chosenSlot['day_of_week'] . '.';
            }
        }

        if (empty($errors)) {
            $insert = $pdo->prepare(
                "INSERT INTO appointments (patient_id, doctor_id, slot_id, date, status, notes)
                 VALUES (?, ?, ?, ?, 'pending', ?)"
            );
            $insert->execute([$_SESSION['user_id'], $selectedDoctorId, $slotId, $date, $notes]);

            $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, 'booking_created')");
            $log->execute([$_SESSION['user_id']]);

            header('Location: dashboard.php?success=1');
            exit;
        }

        $step = 2;
    }
}

$pageTitle = 'Book Appointment';
include __DIR__ . '/../includes/header.php';
?>
<style>
  .patient-nav { display:flex; gap:1rem; margin-bottom:2rem; flex-wrap:wrap; }
  .patient-nav a { padding:0.5rem 1.2rem; background:var(--primary,#0077b6); color:#fff; border-radius:8px; text-decoration:none; font-size:0.9rem; }
  .patient-nav a:hover { opacity:0.85; }
  .book-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:2rem; max-width:600px; }
  .book-card h2 { margin-bottom:1.5rem; }
  .alert-error { background:#fee2e2; color:#991b1b; padding:1rem; border-radius:8px; margin-bottom:1.5rem; }
  .alert-error ul { margin:0.5rem 0 0 1.25rem; }
</style>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Book an Appointment</h1>
      <p>Choose your doctor and a suitable time slot.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <nav class="patient-nav" aria-label="Patient navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="book.php">Book Appointment</a>
        <a href="history.php">History</a>
        <a href="../auth/logout.php">Log Out</a>
      </nav>

      <?php if (!empty($errors)): ?>
        <div class="alert-error" role="alert">
          <strong>Please fix the following:</strong>
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($step === 1): ?>

        <!-- STEP 1: Select doctor -->
        <div class="book-card">
          <h2>Step 1 — Select a Doctor</h2>
          <form method="POST" action="">
            <input type="hidden" name="action" value="select_doctor" />
            <div class="form-group">
              <label for="doctor_id">Doctor *</label>
              <select id="doctor_id" name="doctor_id" required>
                <option value="">— Choose a doctor —</option>
                <?php foreach ($doctors as $doc): ?>
                  <option value="<?= $doc['id'] ?>">
                    <?= htmlspecialchars($doc['name']) ?> — <?= htmlspecialchars($doc['specialty']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:0.5rem;">See Available Slots →</button>
          </form>
        </div>

      <?php elseif ($step === 2): ?>

        <!-- STEP 2: Select slot and date -->
        <div class="book-card">
          <h2>Step 2 — Choose a Slot &amp; Date</h2>
          <p style="margin-bottom:1.5rem; color:var(--text-muted);">
            Booking with <strong><?= htmlspecialchars($selectedDoctor['name']) ?></strong>
            (<?= htmlspecialchars($selectedDoctor['specialty']) ?>)
          </p>

          <?php if (empty($slots)): ?>
            <p style="color:#666;">This doctor has no available time slots. <a href="book.php">Choose another doctor →</a></p>
          <?php else: ?>
            <form method="POST" action="">
              <input type="hidden" name="action"    value="book" />
              <input type="hidden" name="doctor_id" value="<?= $selectedDoctorId ?>" />

              <div class="form-group">
                <label for="slot_id">Time Slot *</label>
                <select id="slot_id" name="slot_id" required>
                  <option value="">— Select a time slot —</option>
                  <?php foreach ($slots as $sl): ?>
                    <option value="<?= $sl['id'] ?>" <?= (int)($_POST['slot_id'] ?? 0) === $sl['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($sl['day_of_week']) ?>
                      <?= substr($sl['start_time'], 0, 5) ?> – <?= substr($sl['end_time'], 0, 5) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="date">Appointment Date * <small style="color:var(--text-muted);">(must match the slot's day)</small></label>
                <input type="date" id="date" name="date" required
                       min="<?= date('Y-m-d') ?>"
                       value="<?= htmlspecialchars($post['date'] ?? '') ?>" />
              </div>

              <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Reason for visit or any relevant information (optional)"><?= htmlspecialchars($post['notes'] ?? '') ?></textarea>
              </div>

              <div style="display:flex; gap:1rem; margin-top:0.5rem; flex-wrap:wrap;">
                <a href="book.php" class="btn btn-outline">← Change Doctor</a>
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
              </div>
            </form>
          <?php endif; ?>
        </div>

      <?php endif; ?>

    </div>
  </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
