<?php
// Démarrez la session
session_start();

// Vérifiez si la session et les données de l'utilisateur sont définies
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'ADMIN') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=bdd", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérez l'historique des fichiers CSV téléchargés depuis la table historique_fichiers
        $query = $pdo->query("SELECT * FROM historique_fichiers");
        $historiqueFichiers = $query->fetchAll(PDO::FETCH_ASSOC);

        // Affichez l'historique des fichiers CSV téléchargés
        if ($historiqueFichiers) {
            echo "<h1>Historique des fichiers CSV téléchargés</h1>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Nom du fichier</th><th>Date de téléchargement</th><th>Contenu CSV</th></tr>";
            foreach ($historiqueFichiers as $fichier) {
                echo "<tr>";
                echo "<td>" . $fichier['id'] . "</td>";
                echo "<td>" . $fichier['nom_fichier'] . "</td>";
                echo "<td>" . $fichier['date_telechargement'] . "</td>";
                echo "<td><a href='telecharger.php?idF=" . $fichier['id'] . "'>Télécharger</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Aucun fichier CSV téléchargé.";
        }
    } catch (PDOException $e) {
        echo "Erreur de la base de données : " . $e->getMessage();
    }
} else {
    // Redirection ou message d'erreur si l'utilisateur n'est pas autorisé
    // ...
}
?>
