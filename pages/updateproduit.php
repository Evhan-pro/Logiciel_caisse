<?php
    require_once('identifier.php');

    require_once('connexiondb.php');

    $idf=isset($_POST['idF'])?$_POST['idF']:0;

    $nomp=isset($_POST['nomP'])?$_POST['nomP']:"";

    $famille=isset($_POST['famille'])?strtoupper($_POST['famille']):"";

    $prix=isset($_POST['prix'])?strtoupper($_POST['prix']):"";
    
    $requete="update produit set prix=?, nomproduit=?,famille=? where idproduit=?";

    $params=array($prix,$nomp,$famille,$idf);

    $resultat=$pdo->prepare($requete);

    $resultat->execute($params);
    
    header('location:produit.php');
?>
