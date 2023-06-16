<?php
    require_once('identifier.php');
    require_once('connexiondb.php');
    $nom=isset($_POST['nom'])?$_POST['nom']:"";
    $prenom=isset($_POST['prenom'])?$_POST['prenom']:"";
    $civilite=isset($_POST['civilite'])?$_POST['civilite']:"F";
    $idproduit=isset($_POST['idproduit'])?$_POST['idproduit']:1;

    $nomPhoto=isset($_FILES['photo']['name'])?$_FILES['photo']['name']:"";
    $imageTemp=$_FILES['photo']['tmp_name'];
    move_uploaded_file($imageTemp,"../images/".$nomPhoto);

    $requete="insert into client(nom,prenom,civilite,idproduit,photo) values(?,?,?,?,?)";
    $params=array($nom,$prenom,$civilite,$idproduit,$nomPhoto);
    $resultat=$pdo->prepare($requete);
    $resultat->execute($params);
    
    header('location:client.php');

?>