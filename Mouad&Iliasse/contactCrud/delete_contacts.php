<?php
require_once "../../db/dbConnexion.php";

$id = $_GET["id"] ?? null;

if ($id) {
    // Suppression du contact par son ID
    $sql = "DELETE FROM contacts WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Redirection vers la liste après suppression
header("Location: list_contacts.php");
exit;
?>