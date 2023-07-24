<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
try {

    $pdo = new PDO("mysql:host=localhost;dbname=bdd;port=3306",
        "root", "root");

}catch (Exception $e){
    die('Erreur : ' . $e->getMessage());


    //die('Erreur : impossible de se connecter à la base de donnée');
}


?>




