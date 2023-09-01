<?php
require_once('identifier.php');
require_once("connexiondb.php");

session_start();

// Réinitialiser les variables de session à zéro si nécessaire
if ($_SERVER['REQUEST_METHOD'] === 'GET' && basename($_SERVER['PHP_SELF']) === 'client.php') {
    // Vous pouvez également vérifier si un paramètre d'URL spécifique est présent pour effectuer la réinitialisation uniquement lorsque nécessaire
    // if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
        $_SESSION['idClient'] = 0;
        $_SESSION['nom'] = '';
        $_SESSION['prenom'] = '';
    // }
} 

$nomPrenom = isset($_GET['nomPrenom']) ? $_GET['nomPrenom'] : "";
$idproduit = isset($_GET['idproduit']) ? $_GET['idproduit'] : 0;
$size = isset($_GET['size']) ? $_GET['size'] : 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $size;

$requeteclient = "SELECT idclient, nom, prenom, photo, civilite 
                FROM client WHERE nom LIKE '%$nomPrenom%' OR prenom LIKE '%$nomPrenom%'
                LIMIT $size      
                OFFSET $offset";

$resultatclient = $pdo->query($requeteclient);
$clients = $resultatclient->fetchAll();

// Pagination
$requeteCount = "SELECT COUNT(*) countS FROM client
                WHERE nom LIKE '%$nomPrenom%' OR prenom LIKE '%$nomPrenom%'";
$resultatCount = $pdo->query($requeteCount);
$tabCount = $resultatCount->fetch();
$nbrclientTotal = $tabCount['countS'];
$nbrPage = ceil($nbrclientTotal / $size);
?>

<!DOCTYPE HTML>
<HTML>
<head>
    <meta charset="utf-8">
    <title>Gestion des clients</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/monstyle.css">
</head>
<body>
    <?php require("menu.php"); ?>

    <div class="container">
        <div class="panel panel-success margetop60">

            <div class="panel-heading">Rechercher des clients</div>

            <div class="panel-body">
                <form method="get" action="client.php" class="form-inline">
                    <div class="form-group">

                        <input type="text" name="nomPrenom" 
                               placeholder="Nom et prénom"
                               class="form-control"
                               value="<?php echo $nomPrenom ?>"/>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-search"></span>
                        Chercher...
                    </button> 

                    &nbsp;&nbsp;
                     <?php if ($_SESSION['user']['role']== 'ADMIN') {?>

                        <a href="nouveauclient.php">

                            <span class="glyphicon glyphicon-plus"></span>
                            Nouveau client

                        </a>

                     <?php }?>
                </form>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">Liste des clients (<?php echo $nbrclientTotal ?> clients)</div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nom</th> <th>Prénom</th> 
                            <th>Photo</th> 
                            <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                <th>Actions</th>
                            <?php }?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($clients as $client) { ?>      
                                <td><?php echo $client['nom'] ?> </td>
                                <td><?php echo $client['prenom'] ?> </td> 
                                <td>
                                    <img src="../images/<?php echo $client['photo']?>"
                                    width="50px" height="50px" class="img-circle">
                                </td> 
                                <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                    <td>
                                        <a href="editerclient.php?idS=<?php echo $client['idclient'] ?>">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                        &nbsp;
                                        <a onclick="return confirm('Etes vous sur de vouloir supprimer le client')"
                                        href="supprimerclient.php?idS=<?php echo $client['idclient'] ?>">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                        <a href="plongee.php?idS=<?php echo $client['idclient'] ?>"><span class="glyphicon glyphicon-shopping-cart"></span></a>
                                        <a href="exportData.php?idS=<?php echo $client['idclient'] ?>">
                                            <span class="glyphicon glyphicon-download-alt"></span>
                                        </a>
                                        <a class="present" href="client.php?page=<?php echo $page ?>&nomPrenom=<?php echo $nomPrenom ?>&idS=<?php echo $client['idclient'] ?>">Présent</a>
                                    </td>
                                <?php }?>
                             </tr>
                         <?php } ?>
                    </tbody>
                </table>
                <div>
                    <ul class="pagination">
                        <?php for($i=1;$i<=$nbrPage;$i++){ ?>
                            <li class="<?php if($i==$page) echo 'active' ?>"> 
                                <a href="client.php?page=<?php echo $i;?>&nomPrenom=<?php echo $nomPrenom ?>&idproduit=<?php echo $idproduit ?>">
                                    <?php echo $i; ?>
                                </a> 
                             </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</HTML>
