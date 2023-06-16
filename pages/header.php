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
    require_once('identifier.php');
    require_once('connexiondb.php');
    $idS=isset($_GET['idS'])?$_GET['idS']:0;
    $requeteS="select * from client where idclient=$idS";
    $resultatS=$pdo->query($requeteS);
    $client=$resultatS->fetch();
    $nom=$client['nom'];
    $prenom=$client['prenom'];
    $civilite=strtoupper($client['civilite']);
    $idproduit=$client['idproduit'];
    $nomPhoto=$client['photo'];

    $requeteF="select * from produit";
    $resultatF=$pdo->query($requeteF); 
?>

    <script>
            jQuery(function(){
                $(function () {
                    $(window).scroll(function () {
                        if ($(this).scrollTop() > 200 ) { 
                            $('#scrollUp').css('right','10px');
                        } else { 
                            $('#scrollUp').removeAttr( 'style' );
                        }
 
                    });
                });
            });
</script>
</head>
<body>

    <!--Début Header-->
    <header class="header" data-header>
        <a href="client.php"><span class="glyphicon glyphicon-arrow-left"></span><?php echo $nom ?>
        <?php echo $prenom ?></a>
        <div class="container">
        <div class="home">
        <img src="images/logo.png" width="15%">
        <ul class="menu cf">
            <li class="plongee"><a href="plongee.php">Plongée</a></li>
            <li class="gonflage"><a href="gonflage.php">Gonflage</a></li>
            <li class="carte"><a href="">Carte</a></li>
            <li class="restaurant"><a href="">Restaurant</a></li>
            <li class="formation"><a href="">Formation</a></li>
            <li class="hebergement"><a href="">Hébergement</a></li>
            <li class="boutique"><a href="">Boutique</a></li>
        </ul>
    </div>
        <div class="recouvrir" data-nav-toggler data-recouvrir></div>

        </div>
    </header>
<!--Fin Header-->

<!-- Début flèche retour vers le haut -->

<div class="btn">
   <img src="images/fleche_haut.png" class="icone" >
</div>

<!-- Fin flèche retour vers le haut -->
    <script src="js/fleche.js"></script>
    <script src="js/script.js"></script>

</body>