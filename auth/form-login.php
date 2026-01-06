<?php
session_start();
require_once("../db/dbConnexion.php");

$_SESSION['errors'] = [];
$_SESSION['old'] = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    $_SESSION['old']['email'] = $email;

    // Validation
    if (empty($email)) {
        $_SESSION['errors'][] = "Email obligatoire";
    }

    if (empty($password)) {
        $_SESSION['errors'][] = "Mot de passe obligatoire";
    }

    if (!empty($_SESSION['errors'])) {
        header("Location: login.php");
        exit;
    }

    // select user selon l'email écrit
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // vérification de mot de passe
    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['errors'][] = "Email ou mot de passe incorrect";
        header("Location: login.php");
        exit;
    }

    //  Vérifier si l'utilisateur est banni
    if ($user['is_active'] == 0) {
        session_destroy();
        header("Location: /test/banned.php");
        exit;
    }

    // stocker les info dans des sessions
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION["user_nom"] = $user['nom'];
    $_SESSION["user_prenom"] = $user['prenom'];
    $_SESSION["user_active"] = $user['is_active'];
    $_SESSION["user_avatar"] = $user['avatar_path'];

    setcookie('remember_email', $email, time() + 604800, "/");

    // Redirection selon rôle
    if ($user['role'] === "user") {
        header("Location: /test/user/dashboardUser.php");
    } else {
        header("Location: /test/admin/dashboardAdmin.php");
    }

    exit;
}
