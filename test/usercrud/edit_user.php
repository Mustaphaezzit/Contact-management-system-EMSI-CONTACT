<?php
require "../../db/dbConnexion.php";

$id = $_GET["id"] ?? null;
if (!$id) exit("ID utilisateur manquant.");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom      = trim($_POST["nom"]);
    $prenom   = trim($_POST["prenom"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm_password"];
    $role     = $_POST["role"];

    $erreurs = [];

    if (strlen($nom) < 2) $erreurs[] = "Le nom doit contenir au moins 2 caractères.";
    if (strlen($prenom) < 2) $erreurs[] = "Le prénom doit contenir au moins 2 caractères.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide.";

    // Vérifier si email existe déjà pour un autre utilisateur
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=? AND id<>?");
    $stmt->execute([$email, $id]);

    if ($stmt->fetchColumn() > 0) $erreurs[] = "Cet email est déjà utilisé.";

    // Validation du mot de passe uniquement si fourni
    if ($password) {
        if (strlen($password) < 6) $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
        if ($password !== $confirm) $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    if ($erreurs) {
        foreach ($erreurs as $e) echo "<p style='color:red;'>$e</p>";
        echo "<a href='formulaire_modifier_utilisateur.php?id=$id'>Retour au formulaire</a>";
        exit;
    }

    if ($password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET nom=?, prenom=?, email=?, role=?, password=? WHERE id=?");
        $stmt->execute([$nom, $prenom, $email, $role, $hashed, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET nom=?, prenom=?, email=?, role=? WHERE id=?");
        $stmt->execute([$nom, $prenom, $email, $role, $id]);
    }

    header("Location: ./form/list_users.php");
    exit;
}
