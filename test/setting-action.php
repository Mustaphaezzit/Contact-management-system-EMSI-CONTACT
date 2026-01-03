<?php
session_start();
require_once("../db/dbConnexion.php");

$id     = $_POST['id'];
$nom    = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);
$email  = trim($_POST['email']);

$errors = [];

/* VALIDATION */
if (!$nom)    $errors['nom'] = "Nom requis";
if (!$prenom) $errors['prenom'] = "Prénom requis";
if (!$email)  $errors['email'] = "Email requis";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors['email'] = "Email invalide";

/* UPLOAD AVATAR */
$avatarPath = null;

if (!empty($_FILES['avatar']['name'])) {
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];

    if (!in_array($ext, $allowed)) {
        $errors['avatar'] = "Avatar invalide (jpg, jpeg, png)";
    } else {
        $dir = "../storage/photos_users/";
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $newName = "avatar_{$id}_" . time() . ".$ext";
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dir.$newName)) {
            $avatarPath = "storage/photos_users/".$newName;
        } else {
            $errors['avatar'] = "Erreur upload avatar";
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
if ($avatarPath) {
    $stmt = $pdo->prepare(
        "UPDATE users SET nom=?, prenom=?, email=?, avatar_path=? WHERE id=?"
    );
    $stmt->execute([$nom,$prenom,$email,$avatarPath,$id]);
    $_SESSION['user_avatar'] = $avatarPath;
} else {
    $stmt = $pdo->prepare(
        "UPDATE users SET nom=?, prenom=?, email=? WHERE id=?"
    );
    $stmt->execute([$nom,$prenom,$email,$id]);
}

/* SESSION */
$_SESSION['user_nom']    = $nom;
$_SESSION['user_prenom'] = $prenom;
$_SESSION['user_email']  = $email;

/* REDIRECT */
header("Location: " . ($_SESSION['user_role']==='admin'
    ? "/test/admin/dashboardAdmin.php"
    : "/test/user/dashboardUser.php"));
exit;
