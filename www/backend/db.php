<?php
// Database connection

require_once __DIR__ . '/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $databaseConnection = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>