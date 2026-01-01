<?php
require_once("../../db/dbConnexion.php");

$email = trim($_POST['email'] ?? '');

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(1, $email, PDO::PARAM_STR);
$stmt->execute();

$user = $stmt->fetch();
if($user){
    
}
?>
