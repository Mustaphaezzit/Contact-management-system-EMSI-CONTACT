<?php
session_start();
require_once("../../db/dbConnexion.php");

$email = trim($_POST['email'] ?? '');

if (!$email) {
    exit("Email requis.");
}

// Vérifier l'éxistance de l'utilisateur
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    exit("Aucun utilisateur trouvé avec cet email.");
}

// Génération de mot de passe aléatoire
$newPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);

// Mettre à jour le mot de passe 
$sqlPass = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $pdo->prepare($sqlPass);
$stmt->execute([$newPassword, $email]);

// Contenu 
$subject = "Forgot Password";
$message = "Hello,\n\nYour new password is:\n\n$newPassword\n\nPlease login and change it.";

$headers  = "From: Admin <" . ($_SESSION['user_email'] ?? 'admin@example.com') . ">\r\n";
$headers .= "Reply-To: " . ($_SESSION['user_email'] ?? 'admin@example.com') . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envoi email
if (mail($email, $subject, $message, $headers)) {

    setcookie('remember_email', $email, time() + 604800, "/");
    header("Location: /auth/Login.php?reset=success");
    exit;

} else {
    echo "Impossible d'envoyer l'email.";
}
