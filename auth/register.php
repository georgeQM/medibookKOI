<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Already logged in → redirect to their dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Validate
    if (empty($name))                          $errors[] = "Full name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6)                 $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm)                $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        try {
            // Check email not already taken
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "An account with that email already exists.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'patient')");
                $stmt->execute([$name, $email, $hash]);

                $userId = $pdo->lastInsertId();

                // Log it
                $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, 'register')");
                $log->execute([$userId]);

                $success = true;
            }
        } catch (PDOException $e) {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register — MediBook</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/responsive.css" />
</head>
<body>

  <header>
    <nav aria-label="Main navigation">
      <div class="nav-inner">
        <a href="../index.php" class="nav-logo">Medi<span>Book</span></a>
        <ul class="nav-links" role="list">
          <li><a href="../index.php">Home</a></li>
          <li><a href="../services.php">Services</a></li>
          <li><a href="../about.php">About</a></li>
          <li><a href="../contact.php" class="nav-cta">Book Now</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <section class="page-hero">
      <div class="container">
        <h1>Create an Account</h1>
        <p>Register to manage your appointments online.</p>
      </div>
    </section>

    <section class="section">
      <div class="container" style="max-width:520px;">

        <?php if ($success): ?>
          <div class="form-success" role="status">
            <strong>Account created!</strong>
            <p>You can now <a href="login.php">log in</a>.</p>
          </div>
        <?php else: ?>

          <?php if (!empty($errors)): ?>
            <div class="field-error" role="alert" style="display:block; margin-bottom:1rem; padding:0.75rem; background:#fff0f0; border-left:4px solid #e53e3e;">
              <?php foreach ($errors as $e): ?>
                <p style="margin:0 0 4px;"><?= htmlspecialchars($e) ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form method="POST" novalidate>
            <div class="form-group">
              <label for="name">Full Name *</label>
              <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />
            </div>
            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
            </div>
            <div class="form-group">
              <label for="password">Password * <span style="font-weight:400; font-size:0.85rem;">(min. 6 characters)</span></label>
              <input type="password" id="password" name="password" required />
            </div>
            <div class="form-group">
              <label for="confirm">Confirm Password *</label>
              <input type="password" id="confirm" name="confirm" required />
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Create Account</button>
            <p style="text-align:center; margin-top:1rem; font-size:0.9rem;">
              Already have an account? <a href="login.php">Log in</a>
            </p>
          </form>

        <?php endif; ?>

      </div>
    </section>
  </main>

  <footer>
    <div class="footer-bottom">
      <p>&copy; 2026 MediBook. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
