<?php
    require_once('identifier.php');
    require_once('connexiondb.php');
   
    $requeteF="select * from produit";
    $resultatF=$pdo->query($requeteF);

?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <meta charset="utf-8">
        <title>Nouveau client</title>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../css/monstyle.css">
    </head>
    <body>
        <?php include("menu.php"); ?>
        <div class="container">
             <div class="panel panel-primary margetop60">
                <div class="panel-heading">Les informations du nouveau client :</div>
                <div class="panel-body">
                    <form method="POST" action="insertclient.php" class="form"  enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input type="text" name="nom" placeholder="Nom" class="form-control" required />
                        </div>
                        <div class="form-group">
                             <label for="prenom">Prénom :</label>
                            <input type="text" name="prenom" placeholder="Prénom" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <label for="civilite">Civilité :</label>
                            <div class="radio">
                                <label><input type="radio" name="civilite" value="F" required/> F </label><br>
                                <label><input type="radio" name="civilite" value="M" required/> M </label></br>
                                <label><input type="radio" name="civilite" value="A" required/> Autre </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="photo">Photo :</label>
                            <input type="file" name="photo" />
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