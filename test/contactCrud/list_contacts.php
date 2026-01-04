<?php
// list_contacts.php
require_once "../../db/dbConnexion.php";

// Paramètres de pagination
$limit = 5; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Récupérer le nombre total de contacts
$totalContacts = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$totalPages = ceil($totalContacts / $limit);

// Récupérer les contacts pour la page actuelle
$stmt = $pdo->prepare("SELECT * FROM contacts ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des contacts</title>
</head>
<body>

    <h2>Liste des contacts</h2>
    <a href="create_contact_form.php">Créer un nouveau contact</a>
    
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>ID Propriétaire</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Ville</th>
            <th>Entreprise</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($contacts as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= $c['owner_id'] ?></td>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td><?= htmlspecialchars($c['prenom']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['phone']) ?></td>
            <td><?= htmlspecialchars($c['city']) ?></td>
            <td><?= htmlspecialchars($c['company']) ?></td>
            <td>
                <a href="edit_contact_form.php?id=<?= $c['id'] ?>">Modifier</a> |
                <a href="delete_contact.php?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ce contact ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div style="margin-top:20px;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">« Précédent</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>"> <?= $i ?> </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Suivant »</a>
        <?php endif; ?>
    </div>

</body>
</html>