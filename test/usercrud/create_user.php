<?php
require "../../db/dbConnexion.php";

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
    if (strlen($password) < 6) $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
    if ($password !== $confirm) $erreurs[] = "Les mots de passe ne correspondent pas.";

    // Vérifier si email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=?");
    $stmt->execute([$email]);

    if ($stmt->fetchColumn() > 0) $erreurs[] = "Cet email est déjà utilisé.";

    if ($erreurs) {
        foreach ($erreurs as $e) echo "<p style='color:red;'>$e</p>";
        echo "<a href='./form/create_user_form.php'>Retour au formulaire</a>";
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $hashed, $role]);

    header("Location: ./form/list_users.php");
    exit;
}
