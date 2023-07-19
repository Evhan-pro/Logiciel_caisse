<?php
if (isset($_GET['idF'])) {
    $fileId = $_GET['idF'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=bdd", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $pdo->prepare("SELECT contenu_csv, nom_fichier FROM historique_fichiers WHERE id = :fileId");
        $query->bindParam(':fileId', $fileId);
        $query->execute();
        $fileData = $query->fetch(PDO::FETCH_ASSOC);

        if ($fileData) {
            $content = $fileData['contenu_csv'];
            $filename = $fileData['nom_fichier'];

            // Définissez les en-têtes pour le téléchargement du fichier CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            // Sortez le contenu du fichier CSV
            echo $content;
            exit();
        } else {
            echo "Fichier non trouvé.";
        }
    } catch (PDOException $e) {
        echo "Erreur de la base de données : " . $e->getMessage();
    }
} else {
    echo "Paramètre d'identifiant de fichier manquant.";
}
?>
