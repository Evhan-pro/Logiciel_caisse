<?php
try {

    $pdo = new PDO("mysql:host=localhost;dbname=bdd;port=3306",
        "root", "root");

}catch (Exception $e){
    die('Erreur : ' . $e->getMessage());


    //die('Erreur : impossible de se connecter à la base de donnée');
}


?>




