<?php
require "../../../../db/dbConnexion.php";

$id = $_GET["id"];
if (!$id) {
    http_response_code(400);
    exit("Invalid ID");
}

$stmt = $pdo->prepare("DELETE FROM tags WHERE id = ?");
$stmt->execute([$id]);

header("Location: /test/admin/gestionTags/tags.php");
exit;
