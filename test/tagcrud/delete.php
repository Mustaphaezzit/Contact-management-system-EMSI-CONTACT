<?php
require "../../db/dbConnexion.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit("Invalid ID");
}

$stmt = $pdo->prepare("DELETE FROM tags WHERE id = ?");
$stmt->execute([$id]);

header("Location: ./form/index.php");
exit;
