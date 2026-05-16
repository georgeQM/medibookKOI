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

$stmt = $pdo->prepare("
    SELECT a.id, a.date, a.status, a.notes,
           u.name AS patient_name,
           ts.start_time, ts.end_time
    FROM appointments a
    JOIN users u            ON a.patient_id = u.id
    LEFT JOIN time_slots ts ON a.slot_id    = ts.id
    WHERE a.doctor_id = ? AND a.date >= CURDATE()
    ORDER BY a.date ASC
");
$stmt->execute([$doctor['id']]);
$appointments = $stmt->fetchAll();

$pageTitle = 'Doctor Dashboard';
include __DIR__ . '/../includes/header.php';
?>
<style>
  .doctor-nav { display:flex; gap:1rem; margin-bottom:2rem; flex-wrap:wrap; }
  .doctor-nav a { padding:0.5rem 1.2rem; background:var(--primary,#0077b6); color:#fff; border-radius:8px; text-decoration:none; font-size:0.9rem; }
  .doctor-nav a:hover { opacity:0.85; }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:0.75rem 1rem; text-align:left; border-bottom:1px solid #e2e8f0; font-size:0.9rem; }
  th { background:#f7fafc; font-weight:600; }
  .badge { padding:2px 10px; border-radius:20px; font-size:0.8rem; font-weight:600; }
  .badge-pending   { background:#fef3c7; color:#92400e; }
  .badge-confirmed { background:#d1fae5; color:#065f46; }
  .badge-cancelled { background:#fee2e2; color:#991b1b; }
  .badge-completed { background:#e0e7ff; color:#3730a3; }
</style>

<main>
  <section class="page-hero">
    <div class="container">
      <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h1>
      <p><?= htmlspecialchars($doctor['specialty']) ?> — Your upcoming appointments.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <nav class="doctor-nav" aria-label="Doctor navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="slots.php">Manage Slots</a>
        <a href="../auth/logout.php">Log Out</a>
      </nav>

      <h2 style="margin-bottom:1rem;">Upcoming Appointments</h2>

      <?php if (empty($appointments)): ?>
        <p style="color:#666;">No upcoming appointments.</p>
      <?php else: ?>
        <div style="overflow-x:auto;">
          <table aria-label="Upcoming appointments">
            <thead>
              <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($appointments as $a): ?>
              <tr>
                <td><?= htmlspecialchars($a['patient_name']) ?></td>
                <td><?= htmlspecialchars(date('D j M Y', strtotime($a['date']))) ?></td>
                <td>
                  <?php if ($a['start_time']): ?>
                    <?= substr($a['start_time'], 0, 5) ?> – <?= substr($a['end_time'], 0, 5) ?>
                  <?php else: ?>
                    TBC
                  <?php endif; ?>
                </td>
                <td><span class="badge badge-<?= $a['status'] ?>"><?= ucfirst($a['status']) ?></span></td>
                <td style="max-width:220px; font-size:0.85rem; color:#555;"><?= htmlspecialchars($a['notes'] ?? '') ?></td>
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
