<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Already logged in → redirect
if (isset($_SESSION['user_id'])) {
    header('Location: ../' . $_SESSION['role'] . '/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Start session
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['role']    = $user['role'];

                // Log it
                $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, 'login')");
                $log->execute([$user['id']]);

                // Redirect by role
                header('Location: ../' . $user['role'] . '/dashboard.php');
                exit;
            } else {
                $error = "Incorrect email or password.";
            }
        } catch (PDOException $e) {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log In — MediBook</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/responsive.css" />
</head>
<body>

  <header>
    <nav aria-label="Main navigation">
      <div class="nav-inner">
        <a href="../index.html" class="nav-logo">Medi<span>Book</span></a>
        <ul class="nav-links" role="list">
          <li><a href="../index.html">Home</a></li>
          <li><a href="../services.html">Services</a></li>
          <li><a href="../about.html">About</a></li>
          <li><a href="../contact.html" class="nav-cta">Book Now</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <section class="page-hero">
      <div class="container">
        <h1>Log In</h1>
        <p>Access your MediBook dashboard.</p>
      </div>
    </section>

    <section class="section">
      <div class="container" style="max-width:480px;">

        <?php if ($error): ?>
          <div class="field-error" role="alert" style="display:block; margin-bottom:1rem; padding:0.75rem; background:#fff0f0; border-left:4px solid #e53e3e;">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="POST" novalidate>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;">Log In</button>
          <p style="text-align:center; margin-top:1rem; font-size:0.9rem;">
            Don't have an account? <a href="register.php">Register here</a>
          </p>
        </form>

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
