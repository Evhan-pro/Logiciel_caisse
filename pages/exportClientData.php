<?php
// Démarre la session
session_start();

// Vérifie si les données de session existent
if (isset($_SESSION['nomClient']) && isset($_SESSION['prenomClient']) && isset($_SESSION['Idclient'])) {
    // Récupère les données du client
    $nomClient = $_SESSION['nomClient'];
    $prenomClient = $_SESSION['prenomClient'];
    $idClient = $_SESSION['Idclient'];

    // Crée le contenu CSV pour les données du client
    $csvData = "$nomClient,$prenomClient,$idClient";

    // Définis le nom du fichier CSV avec le nom et le prénom du client
    $filename = $nomClient . '_' . $prenomClient . '.csv';

    // Définis les en-têtes pour le téléchargement du fichier CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Envoie le contenu du fichier CSV
    echo $csvData;

    // Supprime les données de la session après l'exportation
    unset($_SESSION['nomClient']);
    unset($_SESSION['prenomClient']);
    unset($_SESSION['Idclient']);

    // Termine la session
    session_destroy();
    
    // Retourne une réponse JSON pour indiquer que l'exportation a réussi
    echo json_encode(['success' => true, 'csvData' => $csvData]);
} else {
    // Les données de session ne sont pas présentes, retourne une réponse JSON pour indiquer une erreur
    echo json_encode(['success' => false, 'message' => 'Les données du client sont introuvables.']);
}
?>
