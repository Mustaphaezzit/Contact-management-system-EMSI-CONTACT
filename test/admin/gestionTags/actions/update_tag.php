<?php
require "../../../../db/dbConnexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}

$id = $_POST["id"];
$label = trim($_POST['label'] ?? '');

if (!$id || $label === '') {
    exit("Invalid input");
}

if (mb_strlen($label) < 2 || mb_strlen($label) > 50) {
    exit("Label length invalid");
}

if (!preg_match('/^[\p{L}\p{N}\s_-]+$/u', $label)) {
    exit("Invalid characters");
}

try {
    $stmt = $pdo->prepare("UPDATE tags SET label = ? WHERE id = ?");
    $stmt->execute([$label, $id]);
} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        exit("Tag already exists");
    }
    throw $e;
}

header("Location: /test/admin/gestionTags/tags.php");
exit;
