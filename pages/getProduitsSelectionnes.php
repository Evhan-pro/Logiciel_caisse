<?php
    // Connexion à la base de données
    require_once('connexiondb.php');

    // Récupérer les produits sélectionnés du client connecté
    $requeteProduitsSelectionnes = "SELECT p.nomproduit, ps.quantite
                                   FROM produits_selectionnes ps
                                   INNER JOIN produit p ON ps.idproduit = p.idproduit
                                   WHERE ps.idclient = :idClient";

    $stmt = $pdo->prepare($requeteProduitsSelectionnes);
    $stmt->bindParam(':idClient', $_SESSION['idClient']);
    $stmt->execute();

    $produitsSelectionnes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir les résultats en JSON
    $jsonResponse = json_encode(array('produits' => $produitsSelectionnes));

    // Envoyer la réponse JSON
    header('Content-Type: application/json');
    echo $jsonResponse;
?>
