<?php

$DB_HOST = 'localhost';
$DB_NAME = 'unishare';
$DB_USER = 'root';  
$DB_PASS = '';        

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            // Throw exceptions on error instead of failing silently
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Return rows as associative arrays: $row['column']
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Use real prepared statements (safer against SQL injection)
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // In development it's fine to see the raw error; in production you'd log it instead.
    die('Database connection failed: ' . $e->getMessage());
}
