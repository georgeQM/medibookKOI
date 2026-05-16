<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';
$error   = '';

// Change role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_role') {
    $uid  = (int)($_POST['user_id'] ?? 0);
    $role = $_POST['role'] ?? '';
    if (in_array($role, ['patient', 'doctor', 'admin']) && $uid > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$role, $uid]);
            $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
            $log->execute([$_SESSION['user_id'], "changed_role_of_user_$uid to $role"]);
            $message = "Role updated successfully.";
        } catch (PDOException $e) {
            $error = "Failed to update role.";
        }
    }
}

// Delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $uid = (int)($_POST['user_id'] ?? 0);
    if ($uid > 0 && $uid !== (int)$_SESSION['user_id']) {
        try {
            $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
            $log->execute([$_SESSION['user_id'], "deleted_user_$uid"]);
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$uid]);
            $message = "User deleted.";
        } catch (PDOException $e) {
            $error = "Failed to delete user.";
        }
    } else {
        $error = "Cannot delete your own account.";
    }
}

// Fetch all users
$users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Users — MediBook Admin</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/responsive.css" />
  <style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
    th { background: #f7fafc; font-weight: 600; }
    select { padding: 4px 8px; border-radius: 6px; border: 1px solid #cbd5e0; font-size: 0.85rem; }
    .btn-sm { padding: 4px 12px; font-size: 0.8rem; border: none; border-radius: 6px; cursor: pointer; }
    .btn-save   { background: #0077b6; color: #fff; }
    .btn-delete { background: #e53e3e; color: #fff; margin-left: 6px; }
    .alert-success { background: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
    .alert-error   { background: #fee2e2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
  </style>
</head>
<body>
  <header>
    <nav aria-label="Main navigation">
      <div class="nav-inner">
        <a href="../index.php" class="nav-logo">Medi<span>Book</span></a>
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
        <h1>Manage Users</h1>
        <p><?= count($users) ?> registered users.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">

        <?php if ($message): ?><div class="alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <div style="overflow-x:auto;">
          <table aria-label="Users list">
            <thead>
              <tr><th>Name</th><th>Email</th><th>Role</th><th>Registered</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
              <tr>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="action"  value="change_role" />
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>" />
                    <select name="role" aria-label="Change role for <?= htmlspecialchars($u['name']) ?>">
                      <option value="patient" <?= $u['role']==='patient' ? 'selected':'' ?>>Patient</option>
                      <option value="doctor"  <?= $u['role']==='doctor'  ? 'selected':'' ?>>Doctor</option>
                      <option value="admin"   <?= $u['role']==='admin'   ? 'selected':'' ?>>Admin</option>
                    </select>
                    <button type="submit" class="btn-sm btn-save">Save</button>
                  </form>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?');">
                    <input type="hidden" name="action"  value="delete" />
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>" />
                    <button type="submit" class="btn-sm btn-delete">Delete</button>
                  </form>
                </td>
                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                <td></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      </div>
    </section>
  </main>

  <footer>
    <div class="footer-bottom"><p>&copy; 2026 MediBook. All rights reserved.</p></div>
  </footer>
</body>
</html>
