<?php
require "../../../db/dbConnexion.php";

$tags = $pdo->query("
  SELECT 
      t.id,
      t.label,
      COUNT(ct.contact_id) AS usage_count
  FROM tags t
  LEFT JOIN contact_tag ct ON ct.tag_id = t.id
  GROUP BY t.id
  ORDER BY t.label ASC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>

  <h2>Tags</h2>
  <a href="./create.php">New Tag</a>

  <table border="1" cellpadding="6">
    <tr>
      <th>Label</th>
      <th>Used By</th>
      <th>Actions</th>
    </tr>

    <?php foreach ($tags as $tag): ?>
      <tr>
        <td><?= htmlspecialchars($tag['label'], ENT_QUOTES) ?></td>
        <td><?= (int)$tag['usage_count'] ?></td>
        <td>
          <a href="./edit.php?id=<?= (int)$tag['id'] ?>">Edit</a>
          |
          <a href="../delete.php?id=<?= (int)$tag['id'] ?>"
            onclick="return confirm('Delete this tag?')">
            Delete
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>

</html>