<?php 
    require_once('identifier.php');
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <meta charset="utf-8">
        <tit>Nouvelle filière</tit>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../css/monstyle.css">
    </head>
    <body>
        <?php include("menu.php"); ?>
        
        <div class="container">
                       
             <div class="panel panel-primary margetop60">
                <div class="panel-heading">Veuillez saisir les données de la nouvelle filère</div>
                <div class="panel-body">
                    <form method="post" action="insertproduit.php" class="form">
						
                        <div class="form-group">
                             <label for="famille">Nom du produit:</label>
                            <input type="text" name="nomP" 
                                   placeholder="Nom du produit"
                                   class="form-control"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="famille">Famille:</label>
				            <select name="famille" class="form-control" id="famille" >
                            <option value="all">Toutes les familles</option>
                            <option value="plongEe">Plongée</option>
                            <option value="gonglage">Gonglage</option>
                            <option value="restauration">Restauration</option>
                            <option value="hébergement">Hébergement</option>
                            <option value="formation">Formation</option> 
                            <option value="carte">Carte</option> 
                            <option value="boutique">Boutique</option> 
				            </select>
                        </div>

                        <div class="form-group">
                             <label for="famille">Prix du produit:</label>
                            <input type="number" name="prix" 
                                   placeholder="Prix du produit"
                                   class="form-control" step="any"/> 
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