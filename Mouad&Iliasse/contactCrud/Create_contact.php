<?php
// create_contact.php
require_once "../../db/dbConnexion.php";

// Initialisation des variables pour ré-afficher les données en cas d'erreur
$owner_id = '';
$nom      = '';
$prenom   = '';
$email    = '';
$phone    = '';
$city     = '';
$company  = '';
$notes    = '';
$errors   = [];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $owner_id = $_POST['owner_id'] ?? '';
    $nom      = trim($_POST['nom']);
    $prenom   = trim($_POST['prenom']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $city     = trim($_POST['city']);
    $company  = trim($_POST['company']);
    $notes    = trim($_POST['notes']);

    // Validation simple
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }
    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire.";
    }
    if (empty($email)) {
        $errors[] = "L'email est obligatoire.";
    }

    // Si aucune erreur, on insère dans la base de données
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO contacts (owner_id, nom, prenom, email, phone, city, company, notes, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$owner_id, $nom, $prenom, $email, $phone, $city, $company, $notes]);

            // Succès : Redirection vers la liste
            header("Location: list_contacts.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un contact</title>
</head>
<body>

    <h2>Créer un nouveau contact</h2>
    <a href="list_contacts.php">Retour à la liste</a>

    <?php if (!empty($errors)): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        
        <label>ID Propriétaire (Owner ID):</label><br>
        <input type="number" name="owner_id" value="<?= htmlspecialchars($owner_id) ?>" required><br><br>

        <label>Nom:</label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" required><br><br>

        <label>Prénom:</label><br>
        <input type="text" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label>Téléphone:</label><br>
        <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>"><br><br>

        <label>Ville:</label><br>
        <input type="text" name="city" value="<?= htmlspecialchars($city) ?>"><br><br>

        <label>Entreprise:</label><br>
        <input type="text" name="company" value="<?= htmlspecialchars($company) ?>"><br><br>

        <label>Notes:</label><br>
        <textarea name="notes" rows="4" cols="50"><?= htmlspecialchars($notes) ?></textarea><br><br>

        <button type="submit">Enregistrer</button>
    </form>

</body>
</html>