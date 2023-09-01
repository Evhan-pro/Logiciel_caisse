<?php
error_reporting(E_ALL);

// Connexion à la base de données 
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "root";
$nomBaseDeDonnees = "bdd";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBaseDeDonnees", $utilisateur, $motDePasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Récupérer l'ID du client à partir de la variable de session
    $idClient = $_SESSION['IdClient'];
    $idS=isset($_GET['idS'])?$_GET['idS']:0;

    // Récupérer les données des produits sélectionnés envoyées en tant que JSON
    $produitsSelectionnesJson = file_get_contents('php://input');
    $produitsSelectionnes = json_decode($produitsSelectionnesJson, true);

    // Parcourir la liste des produits sélectionnés et les enregistrer dans la base de données
    foreach ($produitsSelectionnes as $produit) {
        $idProduit = $produit['id'];
        $nomProduit = $produit['nomproduit'];  
        $quantite = $produit['quantite'];
        $prix = $produit['prix'];
        

        // Exécuter la requête d'insertion dans la base de données avec l'ID du client
        $requete = $connexion->prepare("INSERT INTO produits_selectionnes (idproduit, nomproduit, quantite, prix, idclient) VALUES (?, ?, ?, ?, ?)");
        $requete->execute([$idProduit, $nomProduit, $quantite, $prix, $idClient]);
    }

    echo "Les produits sélectionnés ont été enregistrés avec succès !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
// Fermer la connexion à la base de données
$connexion = null;
?>
