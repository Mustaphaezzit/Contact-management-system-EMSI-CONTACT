<?php
require "../../db/dbConnexion.php";

$id = $_GET["id"] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);
}

header("Location: ./form/list_users.php");
exit;
