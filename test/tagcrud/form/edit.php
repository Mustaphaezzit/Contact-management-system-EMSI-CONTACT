<?php
require "../../../db/dbConnexion.php";

$id = $_GET['id'];

if (!$id) {
  http_response_code(400);
  exit("Invalid ID");
}

$stmt = $pdo->prepare("SELECT * FROM tags WHERE id = ?");
$stmt->execute([$id]);
$tag = $stmt->fetch();

if (!$tag) {
  http_response_code(404);
  exit("Tag not found");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <h2>Edit Tag</h2>

  <form method="POST" action="../update.php">
    <input type="hidden" name="id" value="<?= (int)$tag['id'] ?>">
    <input
      type="text"
      name="label"
      value="<?= htmlspecialchars($tag['label'], ENT_QUOTES) ?>"
      maxlength="50"
      required>
    <button type="submit">Update</button>
  </form>

  <a href="./index.php">Back</a>
</body>

</html>