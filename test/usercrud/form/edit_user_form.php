<?php
  require "../../../db/dbConnexion.php";

  $id = $_GET["id"] ?? null;
  if (!$id) exit("ID utilisateur manquant.");

  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
  $stmt->execute([$id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h2>Modifier l'utilisateur</h2>
  <form method="POST" action="../edit_user.php?id=<?= $user['id'] ?>">
      <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
      <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      <input type="password" name="password" placeholder="Laisser vide pour garder le mot de passe">
      <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe">
      <select name="role">
          <option value="user" <?= $user['role']=="user"?"selected":"" ?>>Utilisateur</option>
          <option value="admin" <?= $user['role']=="admin"?"selected":"" ?>>Administrateur</option>
      </select>
      <button type="submit">Mettre à jour</button>
  </form>
  <a href="list_users.php">Retour à la liste</a>
</body>
</html>