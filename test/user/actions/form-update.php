<?php
session_start();
require_once("../../../db/dbConnexion.php");

$errors = [];
$old = $_POST;

// Récupérer l'ID du contact
$contactId = $_POST['id'] ?? null;
if (!$contactId) {
    die("Contact introuvable");
}

// Récupérer les données du formulaire
$nom     = trim($_POST['nom'] ?? '');
$prenom  = trim($_POST['prenom'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$city    = trim($_POST['city'] ?? '');
$company = trim($_POST['company'] ?? '');
$tags    = $_POST['tags'] ?? [];

// Validation des champs
if (!$nom) $errors[] = "Le nom est obligatoire";
if (!$prenom) $errors[] = "Le prénom est obligatoire";
if (!$email) $errors[] = "L'email est obligatoire";

// Vérifier si l'email existe déjà pour un autre contact
$sqlCheckEmail = "SELECT id FROM contacts WHERE email = ? AND id != ?";
$stmt = $pdo->prepare($sqlCheckEmail);
$stmt->execute([$email, $contactId]);
if ($stmt->fetch()) {
    $errors[] = "Cet email est déjà utilisé par un autre contact";
}

// Si erreurs, on redirige vers le formulaire
if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $old;
    header("Location: ../../edit-contact.php?id=" . $contactId);
    exit;
}

// Récupérer le contact actuel pour savoir si on doit supprimer l'ancien avatar
$sqlGetContact = "SELECT photo_path FROM contacts WHERE id=?";
$stmt = $pdo->prepare($sqlGetContact);
$stmt->execute([$contactId]);
$currentContact = $stmt->fetch(PDO::FETCH_ASSOC);
$photoPath = $currentContact['photo_path'] ?? null;

// Gérer le téléchargement de la nouvelle photo
if (isset($_FILES['avatar']) && $_FILES['avatar']['name']) {

    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
        $errors[] = "Format de photo invalide (jpg, jpeg, png uniquement)";
    } else {
        $storageDir = "../../../storage/photos_contacts/";
        if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('contact_') . $nom . $prenom . '.' . $ext;
        $targetPath = $storageDir . $filename;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            // Supprimer l'ancienne photo si elle existe
            if ($photoPath && file_exists("../../" . $photoPath)) {
                unlink("../../" . $photoPath);
            }
            $photoPath = "storage/photos_contacts/" . $filename;
        } else {
            $errors[] = "Erreur lors de l'upload de la photo";
        }
    }
}

// Si erreur lors de l'upload, rediriger
if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $old;
    header("Location: ../../edit-contact.php?id=" . $contactId);
    exit;
}

// Mettre à jour le contact
$sqlUpdate = "UPDATE contacts SET nom=?, prenom=?, email=?, phone=?, city=?, company=?, photo_path=?, updated_at=NOW() WHERE id=?";
$stmt = $pdo->prepare($sqlUpdate);
$stmt->execute([$nom, $prenom, $email, $phone, $city, $company, $photoPath, $contactId]);

// Mettre à jour les tags : supprimer les anciens et insérer les nouveaux
$sqlDeleteTags = "DELETE FROM contact_tag WHERE contact_id=?";
$stmt = $pdo->prepare($sqlDeleteTags);
$stmt->execute([$contactId]);

if ($tags) {
    $sqlInsertTag = "INSERT INTO contact_tag (contact_id, tag_id) VALUES (?, ?)";
    $stmt = $pdo->prepare($sqlInsertTag);
    foreach ($tags as $tagId) {
        $stmt->execute([$contactId, $tagId]);
    }
}

// Redirection vers liste des contacts
header("Location: /test/user/mes_contacts.php");
exit;
