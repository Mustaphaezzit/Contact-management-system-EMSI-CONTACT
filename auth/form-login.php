<?php
session_start();
require_once("../db/dbConnexion.php");

// Initialisation
$_SESSION['errors'] = [];
$_SESSION['old'] = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    // Sauvegarde pour refill
    $_SESSION['old']['email'] = $email;

    // Validation
    if (empty($email)) {
        $_SESSION['errors'][] = "Email obligatoire";
    }

    if (empty($password)) {
        $_SESSION['errors'][] = "Mot de passe obligatoire";
    }

    // Si erreurs → retour login
    if (!empty($_SESSION['errors'])) {
        header("Location: login.php");
        exit;
    }

    // Vérification BD
    $sql = "SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([
        'email' => $email,
        'password' => $password
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['errors'][] = "Email ou mot de passe incorrect";
        header("Location: login.php");
        exit;
    }

    // ✅ Connexion OK
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    setcookie('remember_email', $email, time() + 604800, "/");

    header("Location: dashboard.php");
    exit;
}
