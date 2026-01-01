<?php
session_start();
session_unset();
session_destroy();

// Supprimer le cookie si tu veux
setcookie('remember_email', '', time() - 3600, "/");

header("Location: login.php");
exit;
?>
