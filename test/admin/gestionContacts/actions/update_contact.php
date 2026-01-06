<?php
require_once "../../../../db/dbConnexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id       = $_POST['id'];
    $owner_id = $_POST['owner_id'];

    $nom      = trim($_POST['nom']);
    $prenom   = trim($_POST['prenom']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $city     = trim($_POST['city']);
    $company  = trim($_POST['company']);
    $notes    = trim($_POST['notes']);

    $stmt = $pdo->prepare("
        UPDATE contacts 
        SET 
            owner_id = ?,
            nom = ?,
            prenom = ?,
            email = ?,
            phone = ?,
            city = ?,
            company = ?,
            notes = ?,
            updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $owner_id,
        $nom,
        $prenom,
        $email,
        $phone,
        $city,
        $company,
        $notes,
        $id
    ]);

    header("Location: /test/admin/gestionContacts/contacts.php");
    exit;
}
?>
