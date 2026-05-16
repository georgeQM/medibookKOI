<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Stats
$totalUsers    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPatients = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'patient'")->fetchColumn();
$totalDoctors  = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'doctor'")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$todayBookings = $pdo->query("SELECT COUNT(*) FROM appointments WHERE date = CURDATE()")->fetchColumn();
$pending       = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn();

// Recent 5 appointments
$recent = $pdo->query("
    SELECT a.date, a.status, u.name AS patient, d.name AS doctor
    FROM appointments a
    JOIN users u ON a.patient_id = u.id
    JOIN users d ON a.doctor_id  = d.id
    ORDER BY a.created_at DESC
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard — MediBook</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/responsive.css" />
  <style>
    .dash-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
    .stat-card  { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; text-align: center; }
    .stat-card .stat-num { font-size: 2.2rem; font-weight: 700; color: var(--primary, #0077b6); }
    .stat-card .stat-lbl { font-size: 0.85rem; color: #666; margin-top: 4px; }
    .admin-nav  { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .admin-nav a { padding: 0.5rem 1.2rem; background: var(--primary, #0077b6); color: #fff; border-radius: 8px; text-decoration: none; font-size: 0.9rem; }
    .admin-nav a:hover { opacity: 0.85; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
    th { background: #f7fafc; font-weight: 600; }
    .badge { padding: 2px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .badge-pending   { background: #fef3c7; color: #92400e; }
    .badge-confirmed { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    .badge-completed { background: #e0e7ff; color: #3730a3; }
  </style>
</head>
<body>
  <header>
    <nav aria-label="Main navigation">
      <div class="nav-inner">
        <a href="../index.php" class="nav-logo">Medi<span>Book</span></a>
        <ul class="nav-links" role="list">
          <li><a href="dashboard.php">Admin</a></li>
          <li><a href="users.php">Users</a></li>
          <li><a href="appointments.php">Appointments</a></li>
          <li><a href="../auth/logout.php" class="nav-cta">Log Out</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <section class="page-hero">
      <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, <?= htmlspecialchars($_SESSION['name']) ?>.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">

        <nav class="admin-nav" aria-label="Admin navigation">
          <a href="users.php">Manage Users</a>
          <a href="appointments.php">All Appointments</a>
          <a href="../auth/logout.php">Log Out</a>
        </nav>

        <!-- Stats -->
        <div class="dash-grid">
          <div class="stat-card"><div class="stat-num"><?= $totalUsers ?></div><div class="stat-lbl">Total Users</div></div>
          <div class="stat-card"><div class="stat-num"><?= $totalPatients ?></div><div class="stat-lbl">Patients</div></div>
          <div class="stat-card"><div class="stat-num"><?= $totalDoctors ?></div><div class="stat-lbl">Doctors</div></div>
          <div class="stat-card"><div class="stat-num"><?= $totalBookings ?></div><div class="stat-lbl">Total Bookings</div></div>
          <div class="stat-card"><div class="stat-num"><?= $todayBookings ?></div><div class="stat-lbl">Today's Bookings</div></div>
          <div class="stat-card"><div class="stat-num"><?= $pending ?></div><div class="stat-lbl">Pending</div></div>
        </div>

        <!-- Recent appointments -->
        <h2 style="margin-bottom:1rem;">Recent Appointments</h2>
        <?php if (empty($recent)): ?>
          <p style="color:#666;">No appointments yet.</p>
        <?php else: ?>
        <div style="overflow-x:auto;">
          <table aria-label="Recent appointments">
            <thead>
              <tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
              <?php foreach ($recent as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['patient']) ?></td>
                <td><?= htmlspecialchars($row['doctor']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>

      </div>
    </section>
  </main>

  <footer>
    <div class="footer-bottom"><p>&copy; 2026 MediBook. All rights reserved.</p></div>
  </footer>
</body>
</html>
