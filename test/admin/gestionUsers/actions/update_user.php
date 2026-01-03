<?php
require "../../../../db/dbConnexion.php";

$id = $_POST["id"] ?? null;
if (!$id) exit("ID utilisateur manquant.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom    = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email  = trim($_POST["email"]);
    $role   = $_POST["role"];
    // Checkbox: si non coché, on considère 0
    $is_active = isset($_POST["is_active"]) ? 1 : 0;

    $erreurs = [];

    // Validations
    if (strlen($nom) < 2) $erreurs[] = "Le nom doit contenir au moins 2 caractères.";
    if (strlen($prenom) < 2) $erreurs[] = "Le prénom doit contenir au moins 2 caractères.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide.";

    // Vérifier si email existe déjà pour un autre utilisateur
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=? AND id<>?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetchColumn() > 0) $erreurs[] = "Cet email est déjà utilisé.";

    if ($erreurs) {
        foreach ($erreurs as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
        echo "<a href='/test/admin/gestionUsers/edit_user_form.php?id=$id'>Retour au formulaire</a>";
        exit;
    }

    // Mise à jour dans la DB
    $stmt = $pdo->prepare("UPDATE users SET nom=?, prenom=?, email=?, role=?, is_active=? WHERE id=?");
    $stmt->execute([$nom, $prenom, $email, $role, $is_active, $id]);

    header("Location: /test/admin/gestionUsers/users.php");
    exit;
}
