<?php
require_once("../../../../db/dbConnexion.php");

// Vérifie que le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email  = trim($_POST['email']);
    $role   = $_POST['role'];
    $password = bin2hex(random_bytes(4)); // mot de passe généré aléatoire pour l'utilisateur

    $errors = [];

    // Validation simple
    if (strlen($nom) < 2) $errors[] = "Le nom doit contenir au moins 2 caractères.";
    if (strlen($prenom) < 2) $errors[] = "Le prénom doit contenir au moins 2 caractères.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";

    // Vérifie si l'email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) $errors[] = "Cet email est déjà utilisé.";

    if ($errors) {
        foreach ($errors as $e) echo "<p style='color:red;'>$e</p>";
        echo "<a href='./form_create_user.php'>Retour au formulaire</a>";
        exit;
    }

    // Insère l'utilisateur (mot de passe non hashé pour envoyer dans l'email)
    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([$nom, $prenom, $email, $password, $role]);

    // Prépare le message à envoyer
    $subject = "Bienvenue sur EMSIContact !";
    $message = "
Bonjour $prenom $nom,

Votre compte a été créé avec succès.

Voici vos identifiants pour vous connecter :
Email : $email
Mot de passe : $password

Connectez-vous ici : http://localhost/auth/Login.php

Cordialement,
EMSIContact
";
    $headers = "From: contact@emsiContact.ma";

    // Envoi de l'email via MailHog
    mail($email, $subject, $message, $headers);

    // Redirection vers la liste des utilisateurs
    header("Location: /test/admin/gestionUsers/users.php");
    exit;
}
