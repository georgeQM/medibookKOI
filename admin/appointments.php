<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $aid    = (int)($_POST['appointment_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if (in_array($status, ['pending','confirmed','cancelled','completed']) && $aid > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->execute([$status, $aid]);
            $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
            $log->execute([$_SESSION['user_id'], "updated_appointment_{$aid}_status_to_{$status}"]);
            $message = "Appointment status updated.";
        } catch (PDOException $e) {
            $message = "Failed to update status.";
        }
    }
}

// Filter
$filterStatus = $_GET['status'] ?? '';
$filterDate   = $_GET['date'] ?? '';

$sql    = "SELECT a.id, a.date, a.status, a.notes,
                  p.name AS patient, d.name AS doctor,
                  ts.start_time, ts.end_time
           FROM appointments a
           JOIN users p ON a.patient_id = p.id
           JOIN users d ON a.doctor_id  = d.id
           JOIN time_slots ts ON a.slot_id = ts.id
           WHERE 1=1";
$params = [];

if ($filterStatus) { $sql .= " AND a.status = ?"; $params[] = $filterStatus; }
if ($filterDate)   { $sql .= " AND a.date = ?";   $params[] = $filterDate; }

$sql .= " ORDER BY a.date DESC, ts.start_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Appointments — MediBook Admin</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/responsive.css" />
  <style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.875rem; }
    th { background: #f7fafc; font-weight: 600; }
    .filters { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; align-items: flex-end; }
    .filters label { font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 4px; }
    .filters select, .filters input { padding: 6px 10px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.9rem; }
    .badge { padding: 2px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .badge-pending   { background: #fef3c7; color: #92400e; }
    .badge-confirmed { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    .badge-completed { background: #e0e7ff; color: #3730a3; }
    select.status-select { padding: 4px 8px; border-radius: 6px; border: 1px solid #cbd5e0; font-size: 0.82rem; }
    .btn-sm { padding: 4px 12px; font-size: 0.8rem; border: none; border-radius: 6px; cursor: pointer; background: #0077b6; color: #fff; }
    .alert-success { background: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
  </style>
</head>
<body>
  <header>
    <nav aria-label="Main navigation">
      <div class="nav-inner">
        <a href="../index.html" class="nav-logo">Medi<span>Book</span></a>
        <ul class="nav-links" role="list">
          <li><a href="dashboard.php">Dashboard</a></li>
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
        <h1>All Appointments</h1>
        <p><?= count($appointments) ?> appointment(s) found.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">

        <?php if ($message): ?><div class="alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>

        <!-- Filters -->
        <form method="GET" class="filters" aria-label="Filter appointments">
          <div>
            <label for="filter-status">Status</label>
            <select id="filter-status" name="status">
              <option value="">All</option>
              <option value="pending"   <?= $filterStatus==='pending'   ?'selected':''?>>Pending</option>
              <option value="confirmed" <?= $filterStatus==='confirmed' ?'selected':''?>>Confirmed</option>
              <option value="cancelled" <?= $filterStatus==='cancelled' ?'selected':''?>>Cancelled</option>
              <option value="completed" <?= $filterStatus==='completed' ?'selected':''?>>Completed</option>
            </select>
          </div>
          <div>
            <label for="filter-date">Date</label>
            <input type="date" id="filter-date" name="date" value="<?= htmlspecialchars($filterDate) ?>" />
          </div>
          <button type="submit" class="btn btn-primary" style="padding:6px 16px;">Filter</button>
          <a href="appointments.php" class="btn btn-outline" style="padding:6px 16px;">Clear</a>
        </form>

        <!-- Table -->
        <?php if (empty($appointments)): ?>
          <p style="color:#666;">No appointments found.</p>
        <?php else: ?>
        <div style="overflow-x:auto;">
          <table aria-label="Appointments list">
            <thead>
              <tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th><th>Update</th></tr>
            </thead>
            <tbody>
              <?php foreach ($appointments as $a): ?>
              <tr>
                <td><?= htmlspecialchars($a['patient']) ?></td>
                <td><?= htmlspecialchars($a['doctor']) ?></td>
                <td><?= htmlspecialchars($a['date']) ?></td>
                <td><?= substr($a['start_time'],0,5) ?> – <?= substr($a['end_time'],0,5) ?></td>
                <td><span class="badge badge-<?= $a['status'] ?>"><?= ucfirst($a['status']) ?></span></td>
                <td>
                  <form method="POST" style="display:flex; gap:6px; align-items:center;">
                    <input type="hidden" name="action"         value="update_status" />
                    <input type="hidden" name="appointment_id" value="<?= $a['id'] ?>" />
                    <select name="status" class="status-select" aria-label="Update status">
                      <option value="pending"   <?= $a['status']==='pending'   ?'selected':''?>>Pending</option>
                      <option value="confirmed" <?= $a['status']==='confirmed' ?'selected':''?>>Confirmed</option>
                      <option value="cancelled" <?= $a['status']==='cancelled' ?'selected':''?>>Cancelled</option>
                      <option value="completed" <?= $a['status']==='completed' ?'selected':''?>>Completed</option>
                    </select>
                    <button type="submit" class="btn-sm">Save</button>
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

  <footer>
    <div class="footer-bottom"><p>&copy; 2026 MediBook. All rights reserved.</p></div>
  </footer>
</body>
</html>
