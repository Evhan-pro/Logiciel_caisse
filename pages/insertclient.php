<?php
    require_once('identifier.php');
    require_once('connexiondb.php');
    if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['civilite'])){
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $civilite = $_POST['civilite'];
        // $idproduit = isset($_POST['idproduit']) ? $_POST['idproduit'] : 1;

        if(isset($_FILES['photo'])){
            $nomPhoto = $_FILES['photo']['name'];
            $imageTemp = $_FILES['photo']['tmp_name'];
            move_uploaded_file($imageTemp,"../images/".$nomPhoto);
            unset($_FILES);
        }else{
            $nomPhoto = NULL;
        }
        $requete = "insert into client(nom,prenom,civilite, idproduit,photo) values(?,?,?,?,?)";
        $params = array($nom,$prenom,$civilite,$idproduit,$nomPhoto);
        $resultat = $pdo->prepare($requete);
        $resultat->execute($params);

        unset($_POST['nom']);
        unset($_POST['prenom']);
        unset($_POST['civilite']);
        header('location:client.php');
    }
   
?>