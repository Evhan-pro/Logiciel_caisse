<?php
     session_start();
    if(isset($_SESSION['user'])){
        
            require_once('connexiondb.php');
            
            $idUser=isset($_GET['idUser'])?$_GET['idUser']:0;

            $requete="delete from produit where idproduit=?";
            
            $params=array($idUser);
            
            $resultat=$pdo->prepare($requete);
            
            $resultat->execute($params);
            
            header('location:produit.php');   
            
     }else {
                header('location:login.php');
        }
    
?>