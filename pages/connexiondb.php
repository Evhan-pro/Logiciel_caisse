<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bdd", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion à la base de données réussie !";
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>




