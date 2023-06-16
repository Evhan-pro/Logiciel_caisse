<?php
    require_once('identifier.php');
    require_once('connexiondb.php');
    
    $nomp=isset($_POST['nomP'])?$_POST['nomP']:"";
    $famille=isset($_POST['famille'])?strtoupper($_POST['famille']):"";
    $prix=isset($_POST['prix'])?strtoupper($_POST['prix']):"";
    
    $requete="insert into produit(nomproduit,famille,prix) values(?,?,?)";
    $params=array($nomp,$famille,$prix);
    $resultat=$pdo->prepare($requete);
    $resultat->execute($params);
    
    header('location:produit.php');
?>