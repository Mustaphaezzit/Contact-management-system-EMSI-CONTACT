<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit;
}
