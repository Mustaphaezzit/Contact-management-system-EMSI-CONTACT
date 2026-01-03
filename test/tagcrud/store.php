<?php
require "../../db/dbConnexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}

$label = trim($_POST['label'] ?? '');

if ($label === '') {
    exit("Label is required");
}

if (mb_strlen($label) < 2 || mb_strlen($label) > 50) {
    exit("Label length invalid");
}

if (!preg_match('/^[\p{L}\p{N}\s_-]+$/u', $label)) {
    exit("Invalid characters");
}

try {
    $stmt = $pdo->prepare("INSERT INTO tags (label) VALUES (?)");
    $stmt->execute([$label]);
} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        exit("Tag already exists");
    }
    throw $e;
}

header("Location: ./form/index.php");
exit;
