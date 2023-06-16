<?php
   require_once('identifier.php');
    require_once('connexiondb.php');
    $idf=isset($_GET['idF'])?$_GET['idF']:0;
    $requete="select * from produit where idproduit=$idf";
    $resultat=$pdo->query($requete);
    $produit=$resultat->fetch();
    $nomf=$produit['nomproduit'];
    $famille=strtolower($produit['famille']);
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <meta charset="utf-8">
        <title>Edition d'un produit</title>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../css/monstyle.css">
    </head>
    <body>
        <?php include("menu.php"); ?>
        
        <div class="container">
                       
             <div class="panel panel-primary margetop60">
                <div class="panel-heading">Edition du produit :  <?php echo $produit['nomproduit'] ?></div>
                <div class="panel-body">
                    <form method="post" action="updateproduit.php" class="form">
						<div class="form-group">
                             <label for="famille">id du produit: <?php echo $idf ?></label>
                            <input type="hidden" name="idF" 
                                   class="form-control"
                                    value="<?php echo $idf ?>"/>
                        </div>
                        
                        <div class="form-group">
                             <label for="famille">Nom du produit:</label>
                            <input type="text" name="nomP" 
                                   placeholder="Nom du produit"
                                   class="form-control"
                                   value="<?php echo $produit['nomproduit'] ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="famille">Famille:</label>
				            <select name="famille" class="form-control" id="famille" value="<?php echo $famille ?>">
                            <option value="all" <?php if($famille==="all") echo "selected" ?>>Toutes les familles</option>
                            <option value="plongee"   <?php if($famille==="p")   echo "selected" ?>>Plongée</option>
                            <option value="gonflage"   <?php if($famille==="g")   echo "selected" ?>>Gonglage</option>
                            <option value="restauration"  <?php if($famille==="restauration")  echo "selected" ?>>Restauration</option>
                            <option value="hébergement"   <?php if($famille==="h")   echo "selected" ?>>Hébergement</option>
                            <option value="formation"   <?php if($famille==="f")   echo "selected" ?>>Formation</option> 
                            <option value="carte"   <?php if($famille==="c")   echo "selected" ?>>Carte</option> 
                            <option value="boutique"   <?php if($famille==="b")   echo "selected" ?>>Boutique</option> 
			            </select>
                        </div>

                        <div class="form-group">
                             <label for="famille">Prix du produit:</label>
                            <input type="number" name="prix" 
                                   placeholder="Prix du produit"
                                   class="form-control"
                                   value="<?php echo $produit['prix'] ?>" step="any"/>
                        </div>
                        
				        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-save"></span>
                            Enregistrer
                        </button> 
                      
					</form>
                </div>
            </div>   
        </div>      
    </body>
</HTML>