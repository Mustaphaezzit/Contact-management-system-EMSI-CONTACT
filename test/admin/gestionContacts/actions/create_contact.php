<?php
require_once("../../../../db/dbConnexion.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $owner_id = $_POST['owner_id'] ?? '';
    $nom      = trim($_POST['nom'] ?? '');
    $prenom   = trim($_POST['prenom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $city     = trim($_POST['city'] ?? '');
    $company  = trim($_POST['company'] ?? '');
    $notes    = trim($_POST['notes'] ?? '');

    // Validation
    if (empty($owner_id)) $errors[] = "Owner obligatoire";
    if (empty($nom)) $errors[] = "Nom obligatoire";
    if (empty($prenom)) $errors[] = "Prénom obligatoire";
    if (empty($email)) $errors[] = "Email obligatoire";

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO contacts
                    (owner_id, nom, prenom, email, phone, city, company, notes, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $owner_id,
                $nom,
                $prenom,
                $email,
                $phone,
                $city,
                $company,
                $notes
            ]);

            header("Location: /test/admin/gestionContacts/contacts.php");
            exit;

        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }
}
