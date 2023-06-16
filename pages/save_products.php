<?php
// Connexion à la base de données (remplacez les valeurs par celles de votre configuration)
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "root";
$nomBaseDeDonnees = "bdd";

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$nomBaseDeDonnees", $utilisateur, $motDePasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données des produits sélectionnés envoyées en tant que JSON
    $produitsSelectionnesJson = file_get_contents('php://input');
    $produitsSelectionnes = json_decode($produitsSelectionnesJson, true);
    $idClient = $selectedProductsData['idClient']; 

    // Parcourir la liste des produits sélectionnés et les enregistrer dans la base de données
    foreach ($produitsSelectionnes as $produit) {
        $idProduit = $produit['id'];
        $nomProduit = $produit['nom'];
        $quantite = $produit['quantite'];
        $prix = $produit['prix'];


        // Exécuter la requête d'insertion dans la base de données
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
