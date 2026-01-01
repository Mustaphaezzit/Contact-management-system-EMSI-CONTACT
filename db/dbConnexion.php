<?
    $connexion=new PDO("mysql:host=localhost:3310;dbname=contacts_db_php","root","");

    if($connexion->errorCode()){
        echo "Erreur : ".$connexion->errorCode();
        exit;
    }
?>