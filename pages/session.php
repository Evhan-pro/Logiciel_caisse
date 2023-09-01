<?php
    session_start(); // Début de la session
    
    // Vérification si les données sont déjà stockées dans la session
    if (!isset($_SESSION['idClient']) || !isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
        // Récupération des données depuis la base de données
        require_once('connexiondb.php'); // Inclure le fichier de connexion à la base de données
    
        $idClient = isset($_GET['idS']) ? $_GET['idS'] : 0;
        $requeteClient = "SELECT * FROM client WHERE idclient = $idClient";
        $resultatClient = $pdo->query($requeteClient);
        $client = $resultatClient->fetch();
        
        // Stockage des données dans la session
        $_SESSION['idClient'] = $client['idclient'];
        $_SESSION['nom'] = $client['nom'];
        $_SESSION['prenom'] = $client['prenom'];
        
        // Fermeture de la connexion à la base de données
        $pdo = null;
    }
    // Récupération de l'ID du client, du nom et du prénom depuis la session
    $idClient = $_SESSION['idClient'];
    $nomClient = $_SESSION['nom'];
    $prenomClient = $_SESSION['prenom'];
?>
