<?php
session_start();
require_once("../../../db/dbConnexion.php");

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: ../../login.php");
    exit;
}

$errors = []; // tableau pour les erreurs

// Récupérer les champs
$nom     = trim($_POST['nom'] ?? '');
$prenom  = trim($_POST['prenom'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$city    = trim($_POST['city'] ?? '');
$company = trim($_POST['company'] ?? '');
$tags    = $_POST['tags'] ?? [];

// 1️⃣ Validation des champs obligatoires
if (!$nom)    $errors['nom'] = "Le nom est requis";
if (!$prenom) $errors['prenom'] = "Le prénom est requis";
if (!$email)  $errors['email'] = "L'email est requis";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "L'email est invalide";

// 2️⃣ Vérifier si l'email existe déjà
$stmt = $pdo->prepare("SELECT id FROM contacts WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    $errors['email'] = "Cet email existe déjà";
}

// 3️⃣ Upload photo
$photoPath = null;
if (isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '') {
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
        $errors['photo'] = "Format de photo invalide (jpg, jpeg, png uniquement)";
    } else {
        $storageDir = "../../../storage/photos_contacts/";
        if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('contact_') . "_" . $nom . "_" . $prenom . '.' . $ext;
        $targetPath = $storageDir . $filename;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $errors['photo'] = "Erreur lors de l'upload de la photo";
        } else {
            $photoPath = "storage/photos_contacts/" . $filename;
        }
    }
}

// 4️⃣ Si pas d'erreurs, insérer contact + tags
if (empty($errors)) {
    try {
        $pdo->beginTransaction();

        // Insert contact
        $sql = "INSERT INTO contacts 
        (owner_id, nom, prenom, email, phone, city, company, photo_path, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $userId,
            $nom,
            $prenom,
            $email,
            $phone,
            $city,
            $company,
            $photoPath
        ]);


        $contactId = $pdo->lastInsertId();

        // Insert tags
        if (!empty($tags)) {
            $sqlTag = "INSERT INTO contact_tag (contact_id, tag_id) VALUES ";
            $values = [];
            $params = [];
            foreach ($tags as $i => $tagId) {
                $values[] = "(?, ?)";
                $params[] = $contactId;
                $params[] = $tagId;
            }
            $sqlTag .= implode(", ", $values);
            $stmtTag = $pdo->prepare($sqlTag);
            $stmtTag->execute($params);
        }

        $pdo->commit();
        // redirection si succès
        header("Location: /test/user/mes_contacts.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $errors['general'] = "Erreur serveur : " . $e->getMessage();
    }
}

// Si on arrive ici, il y a des erreurs => revenir au formulaire
$_SESSION['old'] = $_POST;
$_SESSION['errors'] = $errors;
header("Location: /test/user/contact-add.php");
exit;
