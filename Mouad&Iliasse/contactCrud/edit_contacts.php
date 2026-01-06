<?php
require_once "../../db/dbConnexion.php"; // Ajustez le chemin

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = $_POST['id'];
    $nom     = trim($_POST['nom']);
    $prenom  = trim($_POST['prenom']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $city    = trim($_POST['city']);
    $company = trim($_POST['company']);
    $notes   = trim($_POST['notes']);

    // Mise à jour des informations dans la base de données
    $stmt = $pdo->prepare("UPDATE contacts SET nom=?, prenom=?, email=?, phone=?, city=?, company=?, notes=?, updated_at=NOW() WHERE id=?");
    $stmt->execute([$nom, $prenom, $email, $phone, $city, $company, $notes, $id]);

    // Redirection vers la liste
    header("Location: list_contacts.php");
    exit;
}
?>
