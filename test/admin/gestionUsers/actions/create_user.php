<?php
require_once("../../../../db/dbConnexion.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom    = htmlentities(trim($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $prenom = htmlentities(trim($_POST['prenom']), ENT_QUOTES, 'UTF-8');
    $email  = htmlentities(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $role   = htmlentities(trim($_POST['role']), ENT_QUOTES, 'UTF-8');

    // Génération du mot de passe réel
    $password = bin2hex(random_bytes(4)); // ex: 8 caractères

    // Hachage pour la base de données
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $errors = [];

    // Validation
    if (strlen($nom) < 2) $errors[] = "Le nom doit contenir au moins 2 caractères.";
    if (strlen($prenom) < 2) $errors[] = "Le prénom doit contenir au moins 2 caractères.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";

    // Vérifie si l'email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) $errors[] = "Cet email est déjà utilisé.";

    if ($errors) {
        foreach ($errors as $e) echo "<p style='color:red;'>$e</p>";
        echo "<a href='./form_create_user.php'>Retour au formulaire</a>";
        exit;
    }

    // Insertion avec mot de passe hache
    $stmt = $pdo->prepare("
        INSERT INTO users (nom, prenom, email, password, role, is_active)
        VALUES (?, ?, ?, ?, ?, 1)
    ");
    $stmt->execute([$nom, $prenom, $email, $hashedPassword, $role]);

    // Email
    $subject = "Bienvenue sur EMSIContact !";
    $message = "
Bonjour $prenom $nom,

Votre compte a été créé avec succès.

Voici vos identifiants :
Email : $email
Mot de passe : $password

Connectez-vous ici :
http://localhost/projet_php/auth/Login.php

Cordialement,
EMSIContact
";

    // 🔹 Correction du header From
    $fromEmail = isset($_SESSION["user_email"])
        ? $_SESSION["user_email"]
        : "admin@emsiContact.ma";

    $headers  = "From: $fromEmail\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoi de l'email
    mail($email, $subject, $message, $headers);

    // Redirection
    header("Location: /test/admin/gestionUsers/users.php");
    exit;
}
