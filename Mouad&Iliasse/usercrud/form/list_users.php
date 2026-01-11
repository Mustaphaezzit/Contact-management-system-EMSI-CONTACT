<?php
require "../../../db/dbConnexion.php";

// Pagination settings
$limit = 5; // users per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total users count
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPages = ceil($totalUsers / $limit);

// Get users for current page
$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des utilisateurs</title>
</head>
<body>

  <h2>Liste des utilisateurs</h2>
  <a href="create_user_form.php">Créer un nouvel utilisateur</a>
  <table border="1" cellpadding="5" cellspacing="0">
      <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Actions</th>
      </tr>
      <?php foreach ($users as $u): ?>
      <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['nom']) ?></td>
          <td><?= htmlspecialchars($u['prenom']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= $u['role']=="user"?"Utilisateur":"Administrateur" ?></td>
          <td>
              <a href="edit_user_form.php?id=<?= $u['id'] ?>">Modifier</a> |
              <a href="../delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
          </td>
      </tr>
      <?php endforeach; ?>
  </table>

  <!-- Pagination links -->
  <div style="margin-top:20px;">
      <?php if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>">« Précédent</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i == $page): ?>
              <strong><?= $i ?></strong>
          <?php else: ?>
              <a href="?page=<?= $i ?>"><?= $i ?></a>
          <?php endif; ?>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>">Suivant »</a>
      <?php endif; ?>
  </div>

</body>
</html>
