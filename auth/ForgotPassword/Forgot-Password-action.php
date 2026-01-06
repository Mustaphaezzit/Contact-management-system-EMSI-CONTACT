<?php
session_start();
require_once("../../db/dbConnexion.php");

$email = trim($_POST['email'] ?? '');

if (!$email) {
    exit("Email requis.");
}

// Vérifier l'existence de l'utilisateur
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    exit("Aucun utilisateur trouvé avec cet email.");
}

// Génération du mot de passe aléatoire
$newPassword = bin2hex(random_bytes(4)); // 8 caractères

// Hachage du mot de passe
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Mise à jour du mot de passe haché
$sqlPass = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $pdo->prepare($sqlPass);
$stmt->execute([$hashedPassword, $email]);

// Email
// Email
$subject = "Réinitialisation de votre mot de passe";

$message = "Bonjour,\n\n"
         . "Suite à votre demande, votre mot de passe a été réinitialisé.\n\n"
         . "Votre nouveau mot de passe est :\n\n"
         . $newPassword . "\n\n"
         . "Nous vous recommandons de vous connecter et de le modifier immédiatement pour des raisons de sécurité.\n\n"
         . "Cordialement,\n"
         . "L'équipe EMSI Contact";

$headers  = "From: EMSI Contact <admin@contactEmsi.ma>\r\n";
$headers .= "Reply-To: admin@contactEmsi.ma\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";


// Envoi email
if (mail($email, $subject, $message, $headers)) {
    setcookie('remember_email', $email, time() + 604800, "/");
    header("Location: /auth/Login.php?reset=success");
    exit;
} else {
    echo "Impossible d'envoyer l'email.";
}
