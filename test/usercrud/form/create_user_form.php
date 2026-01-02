<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h2>Créer un utilisateur</h2>
  <form method="POST" action="../create_user.php">
      <input type="text" name="nom" placeholder="Nom" required>
      <input type="text" name="prenom" placeholder="Prénom" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
      <select name="role">
          <option value="user">Utilisateur</option>
          <option value="admin">Administrateur</option>
      </select>
      <button type="submit">Créer</button>
  </form>
  <a href="list_users.php">Retour à la liste</a>
</body>
</html>