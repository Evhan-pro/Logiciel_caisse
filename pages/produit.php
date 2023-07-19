<?php
    require_once('identifier.php');
    require_once("connexiondb.php");
    
    /*
    if(isset($_GET['nomP']))
        $nomp=$_GET['nomP'];
    else
        $nomp="";
    */
  
    $nomp=isset($_GET['nomP'])?$_GET['nomP']:"";
    $famille=isset($_GET['famille'])?$_GET['famille']:"all";
    
    $size=isset($_GET['size'])?$_GET['size']:6;
    $page=isset($_GET['page'])?$_GET['page']:1;
    $offset=($page-1)*$size;
    
    if($famille=="all"){
        $requete="select * from produit
                where nomproduit like '%$nomp%'
                limit $size
                offset $offset";
        
        $requeteCount="select count(*) countF from produit
                where nomproduit like '%$nomp%'";
    }else{
         $requete="select * from produit
                where nomproduit like '%$nomp%'
                and famille='$famille'
                limit $size
                offset $offset";
        
        $requeteCount="select count(*) countF from produit
                where nomproduit like '%$nomp%'
                and famille='$famille'";
    }

    $resultatF=$pdo->query($requete);

    $resultatCount=$pdo->query($requeteCount);
    $tabCount=$resultatCount->fetch();
    $nbrproduit=$tabCount['countF'];
    $reste=$nbrproduit % $size;   // % operateur modulo: le reste de la division 
                                 //euclidienne de $nbrproduit par $size
    if($reste===0) //$nbrproduit est un multiple de $size
        $nbrPage=$nbrproduit/$size;   
    else
        $nbrPage=floor($nbrproduit/$size)+1;  // floor : la partie entière d'un nombre décimal
?>
<!DOCTYPE HTML>
<HTML>
    <head>
        <meta charset="utf-8">
        <title>Gestion des produits</title>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../css/monstyle.css">
    </head>
    <body>
        <?php include("menu.php"); ?>
        
        <div class="container">
            <div class="panel panel-success margetop60">
          
				<div class="panel-heading">Rechercher des produits</div>
				<div class="panel-body">
					
					<form method="get" action="produit.php" class="form-inline">
					
						<div class="form-group">
                            
                            <input type="text" name="nomP" 
                                   placeholder="Nom du produit"
                                   class="form-control"
                                   value="<?php echo $nomp ?>"/>
                                   
                        </div>
                        
                        <label for="famille">Famille:</label>
			            <select name="famille" class="form-control" id="famille"
                                onchange="this.form.submit()">
                            <option value="all" <?php if($famille==="all") echo "selected" ?>>Toutes les familles</option>
                            <option value="p"   <?php if($famille==="p")   echo "selected" ?>>Plongée</option>
                            <option value="g"   <?php if($famille==="g")   echo "selected" ?>>Gonglage</option>
                            <option value="r"  <?php if($famille==="r")  echo "selected" ?>>Restauration</option>
                            <option value="h"   <?php if($famille==="h")   echo "selected" ?>>Hébergement</option>
                            <option value="f"   <?php if($famille==="f")   echo "selected" ?>>Formation</option> 
                            <option value="c"   <?php if($famille==="c")   echo "selected" ?>>Carte</option> 
                            <option value="b"   <?php if($famille==="b")   echo "selected" ?>>Boutique</option> 
			            </select>
			            
				        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Chercher...
                        </button> 
                        
                        &nbsp;&nbsp;
                        
                       	<?php if ($_SESSION['user']['role']=='ADMIN') {?>
                       	
                            <a href="nouvelleproduit.php">
                            
                                <span class="glyphicon glyphicon-plus"></span>
                                
                                Nouveau produit
                                
                            </a>
                            
                        <?php } ?>                 
                         
					</form>
				</div>
			</div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">Liste des produits (<?php echo $nbrproduit ?> Produits)</div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id produit</th><th>Nom produit</th><th>famille</th><th>Prix</th>
                                <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                	<th>Actions</th>
                                <?php }?>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php while($produit=$resultatF->fetch()){ ?>
                                <tr>
                                    <td><?php echo $produit['idproduit'] ?> </td>
                                    <td><?php echo $produit['nomproduit'] ?> </td>
                                    <td><?php echo $produit['famille'] ?> </td> 
                                    <td><?php echo $produit['prix'] ?>€</td>
                                    
                                     <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                        <td>
                                            <a href="editerproduit.php?idF=<?php echo $produit['idproduit'] ?>">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                            &nbsp;
                                            <a onclick="return confirm('Etes vous sur de vouloir supprimer le produit ?')"
                                                href="supprimerproduit.php?idF=<?php echo $produit['idproduit'] ?>">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                        </td>
                                    <?php }?>
                                    
                                </tr>
                            <?PHP } ?>
                       </tbody>
                    </table>
                <div>
                    <ul class="pagination">
                        <?php for($i=1;$i<=$nbrPage;$i++){ ?>
                            <li class="<?php if($i==$page) echo 'active' ?>"> 
            <a href="produit.php?page=<?php echo $i;?>&nomP=<?php echo $nomp ?>&famille=<?php echo $famille ?>">
                                    <?php echo $i; ?>
                                </a> 
                             </li>
                        <?php } ?>
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </body>
</HTML>