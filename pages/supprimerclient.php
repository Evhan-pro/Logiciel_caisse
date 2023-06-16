<?php
        session_start();
        if(isset($_SESSION['user']) ){
            
            if($_SESSION['user']['role']=='ADMIN'){
               
                require_once('connexiondb.php');
                
                $idS=isset($_GET['idS'])?$_GET['idS']:0;

                $requete="delete from client where idclient=?";
                
                $params=array($idS);
                
                $resultat=$pdo->prepare($requete);
                
                $resultat->execute($params);
                
                header('location:client.php'); 
                
            }else{
                $message="Vous n'avez pas le privilège de supprimer un client!!!";
                
                $url='client.php';
                
                header("location:alerte.php?message=$message&url=$url"); 
            }
           
        }else {
                header('location:login.php');
        }
?>