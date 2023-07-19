<?php
// Démarrez la session
session_start();
$nouveauNomClient = $_SESSION['nomClient'];
$nouveauPrenomClient = $_SESSION['prenomClient'];
$nouvelID = $_SESSION['Idclient'];
$produitsSelectionnes = $_SESSION['produitsSelectionnes'];
// Vérifiez si la session et les données de l'utilisateur sont définies
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'ADMIN') {
    // Vérifiez si l'identifiant du client est passé en tant que paramètre
    if (isset($_GET['idS'])) {
        $clientId = $_GET['idS'];

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=bdd", "root", "root");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupérez les informations du client à partir de la base de données
            $query = $pdo->prepare("SELECT c.idclient, c.nom, c.prenom, p.nom_produit, ps.quantite, ps.prix FROM client AS c INNER JOIN produits_selectionnes AS ps ON c.idproduit = ps.idproduit INNER JOIN produit AS p ON ps.idproduit = p.idproduit WHERE c.idclient = :clientId");
            $query->bindParam(':clientId', $clientId);
            $query->execute();
            $clientData = $query->fetch(PDO::FETCH_ASSOC);

            function donnees_client() {
                global $nouveauNomClient, $nouveauPrenomClient;
                        
                $nomClient = $nouveauNomClient;
                $prenomClient = $nouveauPrenomClient;
                return $nomClient . ' ' . $prenomClient;
            }
            
            $donneesClient = donnees_client();
            echo $donneesClient;

            // Vérifiez si les informations du client existent
            if ($clientData) {
                // Fonction pour générer le contenu du fichier CSV à partir des informations du client
                function generateCsvData($clientData)
                {
                    // Créez un fichier CSV en mémoire
                    $output = fopen('php://temp', 'w');

                    // Définissez le délimiteur de champ
                    $delimiter = ';';

                    // Écrivez les en-têtes CSV
                    fputcsv($output, ['idclient', 'nom', 'prénom', 'nom_produit', 'quantite', 'prix'], $delimiter);

                    // Écrivez les données du client dans le fichier CSV
                    fputcsv($output, $clientData, $delimiter);

                    // Rembobinez le pointeur de fichier au début
                    rewind($output);

                    // Lisez le contenu du fichier CSV depuis le début
                    $csvData = stream_get_contents($output);

                    // Fermez le pointeur de fichier
                    fclose($output);

                    return $csvData;
                }

                // Générez le contenu du fichier CSV
                $csvData = generateCsvData($clientData);

                // Définissez le nom du fichier CSV avec le nom et le prénom du client
                $filename = $clientData['nom'] . '_' . $clientData['prenom'] . '.csv';

                // Définissez les en-têtes pour le téléchargement du fichier CSV
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');

                // Envoyer le contenu du fichier CSV
                echo $csvData;

                // Supprimer les données de la session
                unset($_SESSION['nomClient']);
                unset($_SESSION['prenomClient']);
                unset($_SESSION['Idclient']);
                unset($_SESSION['produitsSelectionnes']);

                // Terminer la session
                session_destroy();

                // Insérez une entrée dans la table historique_fichiers pour le fichier CSV téléchargé
                $query = $pdo->prepare("INSERT INTO historique_fichiers (utilisateur_id, nom_fichier, date_telechargement, contenu_csv) VALUES (:utilisateurId, :nomFichier, NOW(), :contenuCsv)");
                $query->bindParam(':utilisateurId', $_SESSION['user']['id']); // Utilisateur associé
                $query->bindParam(':nomFichier', $filename); // Nom du fichier CSV
                $query->bindParam(':contenuCsv', $csvData); // Contenu du fichier CSV
                $query->execute();
                exit();
            } else {
                // Le client n'a pas été trouvé dans la base de données, redirigez ou affichez un message d'erreur approprié
                // ...
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de la base de données
            echo "Erreur de la base de données : " . $e->getMessage();
        }
    } else {
        // L'identifiant du client n'a pas été passé en tant que paramètre, redirigez ou affichez un message d'erreur approprié
        // ...
    }
} else {
    // Redirection ou message d'erreur si l'utilisateur n'est pas autorisé
    // ...
}
?>
