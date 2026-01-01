<?
    $connexion=new PDO("mysql:host=localhost:3310;dbname=contactsDb","root","");

    if($connexion->errorCode()){
        echo "Erreur : ".$connexion->errorCode();
        exit;
    }
?>