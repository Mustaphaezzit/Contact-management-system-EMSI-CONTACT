<?
session_start();
require_once("../../../../db/dbConnexion.php");

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: /auth/login.php");
    exit;
}
$id=$_GET["id"];
$sql="DELETE FROM users WHERE id=?";
$stmt=$pdo->prepare($sql);
$stmt->execute([$id]);
header("Location: /test/admin/gestionUsers/users.php");
?>