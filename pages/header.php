<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <title>Maïtaï</title>
    <?php if(isset($stylesheets)):foreach($stylesheets as $stylesheet): ?>
        <link rel="stylesheet" href=<?php echo($stylesheet) ?>>
    <?php endforeach; endif ?>

    <?php 
    session_start(); // Démarrer la session
    require_once('connexiondb.php');
    require_once('identifier.php');
    require_once('session.php');
    require_once('header.php');



// Récupérer le nouveau nom et prénom du client sélectionné s'il existe
if (isset($_GET['idS'])) {
    $idS = $_GET['idS'];
    $requeteS = "SELECT * FROM client WHERE idclient = $idS";
    $resultatS = $pdo->query($requeteS);
    $client = $resultatS->fetch();

    $nom = $client['nom'];
    $prenom = $client['prenom'];
    $civilite = strtoupper($client['civilite']);

    // Mettre à jour les variables de session pour les utiliser sur d'autres pages
    $_SESSION['nomClient'] = $nom;
    $_SESSION['prenomClient'] = $prenom;
    $_SESSION['idClient'] = $idS;
}

// Récupérer les produits sélectionnés du client connecté s'ils existent
if (isset($_SESSION['idClient'])) {
    $idClient = $_SESSION['idClient'];
    $requeteProduitsSelectionnes = "SELECT p.idproduit, p.nomproduit, ps.quantite, ps.prix
    FROM produits_selectionnes ps
    INNER JOIN produit p ON ps.idproduit = p.idproduit
    WHERE ps.idclient = :idClient";

    $stmt = $pdo->prepare($requeteProduitsSelectionnes);
    $stmt->bindParam(':idClient', $idClient);
    $stmt->execute();

    $produitsSelectionnes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Maintenant, vous pouvez afficher les informations dans le header -->
<!-- par exemple, afficher le nom et le prénom du client -->
<?php if (isset($_SESSION['nomClient']) && isset($_SESSION['prenomClient'])) : ?>
    <p>Bienvenue, <?php echo $_SESSION['prenomClient'] . ' ' . $_SESSION['nomClient']; ?></p>
<?php endif; ?>

</head>
    <!--Début Header-->
    <header class="header" data-header>
        <a href="client.php"><h4><?php echo $nom . ' ' . $prenom; ?></h4></a>
        <div class="container">
            <div class="home">
                <img src="images/logo.png" width="15%">
                <ul class="menu cf">
                    <li class="plongee"><a href="plongee.php">Plongée</a></li>
                    <li class="gonflage"><a href="gonflage.php">Gonflage</a></li>
                    <li class="carte"><a href="carte.php">Carte</a></li>
                    <li class="restaurant"><a href="restaurant.php">Restaurant</a></li>
                    <li class="formation"><a href="formation.php">Formation</a></li>
                    <li class="hebergement"><a href="hebergement.php">Hébergement</a></li>
                    <li class="boutique"><a href="boutique.php">Boutique</a></li>
                </ul>
            </div>
            <div class="recouvrir" data-nav-toggler data-recouvrir></div>
        </div>
    </header>
<!--Fin Header-->