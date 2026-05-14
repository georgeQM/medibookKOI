<?php
session_start();

// Log the logout action before destroying session
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/db.php';
    try {
        $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, 'logout')");
        $log->execute([$_SESSION['user_id']]);
    } catch (PDOException $e) {
        // Fail silently — logout must always work
    }
}

$_SESSION = [];
session_destroy();

header('Location: ../auth/login.php');
exit;
