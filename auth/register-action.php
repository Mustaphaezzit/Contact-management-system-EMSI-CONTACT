<?php
session_start();
require_once("../db/dbConnexion.php");

$nom     = trim($_POST['nom'] ?? '');
$prenom  = trim($_POST['prenom'] ?? '');
$email   = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

$errors = [];

/* Validation */
if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($password_confirm)) {
    $errors[] = "Tous les champs sont obligatoires.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Adresse email invalide.";
}

if ($password !== $password_confirm) {
    $errors[] = "Les mots de passe ne correspondent pas.";
}

if (strlen($password) < 6) {
    $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
}

/* Si erreurs */
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: register.php");
    exit;
}

/* Vérifier si email existe */
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    $_SESSION['errors'] = ["Cet email existe déjà."];
    header("Location: register.php");
    exit;
}

/* 🔐 Hachage du mot de passe */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* Insertion */
$stmt = $pdo->prepare("
    INSERT INTO users (nom, prenom, email, password)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([
    $nom,
    $prenom,
    $email,
    $hashedPassword
]);

/* Succès */
$_SESSION['success'] = "Compte créé avec succès. Connectez-vous.";
header("Location: login.php");
exit;
