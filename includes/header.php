<?php
// includes/header.php
// Usage: require_once __DIR__ . '/../includes/header.php';
// Set $pageTitle before including this file.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? 'MediBook';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?> — MediBook</title>
  <link rel="stylesheet" href="/medibook/css/style.css" />
  <link rel="stylesheet" href="/medibook/css/responsive.css" />
</head>
<body>

<header>
  <nav aria-label="Main navigation">
    <div class="nav-inner">
      <a href="/medibook/index.php" class="nav-logo">Medi<span>Book</span></a>
      <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
      <ul class="nav-links" role="list">
        <li><a href="/medibook/index.php">Home</a></li>
        <li><a href="/medibook/services.php">Services</a></li>
        <li><a href="/medibook/about.php">About</a></li>
        <li><a href="/medibook/media.php">Gallery</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="/medibook/<?= $_SESSION['role'] ?>/dashboard.php">My Dashboard</a></li>
          <li><a href="/medibook/auth/logout.php" class="nav-cta">Log Out</a></li>
        <?php else: ?>
          <li><a href="/medibook/auth/login.php">Log In</a></li>
          <li><a href="/medibook/contact.php" class="nav-cta">Book Now</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
</header>
