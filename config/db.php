<?php
// config/db.php
// Include this file at the top of any page that needs the database:
// require_once __DIR__ . '/../config/db.php';

define('DB_HOST', 'localhost');
define('DB_NAME', 'medibook');
define('DB_USER', 'root');      // XAMPP default
define('DB_PASS', '');          // XAMPP default (no password)

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die("Database connection failed. Please try again later.");
}