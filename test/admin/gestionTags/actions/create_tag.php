<?php
require "../../../../db/dbConnexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Method not allowed");
}

$label = trim($_POST['label'] ?? '');

if (empty($label)) {
    exit("Label is required");
}

try {
    $stmt = $pdo->prepare("INSERT INTO tags (label) VALUES (?)");
    $stmt->execute([$label]);

    header("Location: /test/admin/gestionTags/tags.php");
    exit;

} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        exit("Tag already exists");
    }
    exit("Database error");
}
