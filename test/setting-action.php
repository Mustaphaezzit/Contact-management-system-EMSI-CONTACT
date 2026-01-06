<?php
session_start();
require_once("../db/dbConnexion.php");

$id     = $_POST['id'];
$nom    = htmlentities(trim($_POST['nom']), ENT_QUOTES, 'UTF-8');
$prenom = htmlentities(trim($_POST['prenom']), ENT_QUOTES, 'UTF-8');
$email  = htmlentities(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
$password = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

$errors = [];

/* VALIDATION */
if (!$nom)    $errors['nom'] = "Nom requis";
if (!$prenom) $errors['prenom'] = "Prénom requis";
if (!$email)  $errors['email'] = "Email requis";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors['email'] = "Email invalide";

/* PASSWORD */
if ($password || $confirmPassword) {
    if ($password !== $confirmPassword) {
        $errors['password'] = "Les mots de passe ne correspondent pas";
    }
}

/* UPLOAD AVATAR */
$avatarPath = null;

if (!empty($_FILES['avatar']['name'])) {

    $allowedExt = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        $errors['avatar'] = "Avatar invalide (jpg, jpeg, png)";
    } else {

        // Chemin ABSOLU vers storage
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/storage/photos_users/";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Date unique
        $date = date("Ymd_His");

        // Nom du fichier
        $newName = "avatar_{$id}_{$date}.{$ext}";
        $targetPath = $dir . $newName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {

            // Supprimer ancien avatar si existant
            if (!empty($oldAvatarPath) && file_exists($_SERVER['DOCUMENT_ROOT'] . "/" . $oldAvatarPath)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . "/" . $oldAvatarPath);
            }

            // Chemin RELATIF pour la BDD
            $avatarPath = "storage/photos_users/" . $newName;

        } else {
            $errors['avatar'] = "Erreur lors de l'upload de l'avatar";
        }
    }
}


/* ERREURS */
if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $_POST;
    header("Location: /test/settings.php");
    exit;
}

/* UPDATE */
$params = [$nom, $prenom, $email];
$sql = "UPDATE users SET nom=?, prenom=?, email=?";

if ($avatarPath) {
    $sql .= ", avatar_path=?";
    $params[] = $avatarPath;
    $_SESSION['user_avatar'] = $avatarPath;
}

if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql .= $avatarPath ? ", password=?" : ", password=?";
    $params[] = $hashedPassword;
}

$sql .= " WHERE id=?";
$params[] = $id;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

/* SESSION */
$_SESSION['user_nom']    = $nom;
$_SESSION['user_prenom'] = $prenom;
$_SESSION['user_email']  = $email;

/* REDIRECT */
header("Location: " . ($_SESSION['user_role']==='admin'
    ? "/test/admin/dashboardAdmin.php"
    : "/test/user/dashboardUser.php"));
exit;
