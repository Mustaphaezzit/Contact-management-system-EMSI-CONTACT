<?php

$host = "localhost";
$db   = "contacts_db_php";
$user = "root";
$pass = "";
$port = 3306;

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
