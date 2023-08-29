<html>
<head>
    <title>Liste des produits</title>
    <link rel="stylesheet" href="../css/famille.css">
</head>
<body>
<h4><?php echo $nouveauNomClient . ' ' . $nouveauPrenomClient; ?></h4>
    <?php
        require_once('identifier.php');
        require_once('connexiondb.php');
        require_once('header.php');      
        // Requête pour récupérer les informations du client à partir de la base de données
        $requeteClient = "SELECT * FROM client WHERE idclient = " . $_SESSION['idClient'];
        $resultatClient = $pdo->query($requeteClient);
        $client = $resultatClient->fetch();
        
        // Assurez-vous que le client est trouvé avant de mettre à jour les variables de session
        if ($client) {
            // Récupérer le nouveau nom et prénom du client sélectionné
            $nouveauNomClient = $client['nom'];
            $nouveauPrenomClient = $client['prenom'];
            $nouvelID = $client['idclient'];
        
            // Mettre à jour les variables de session
            $_SESSION['nomClient'] = $nouveauNomClient;
            $_SESSION['prenomClient'] = $nouveauPrenomClient;
            $_SESSION['Idclient'] = $nouvelID;
        }
    
       
        $nomp = isset($_GET['nomP']) ? $_GET['nomP'] : "";
        $famille = isset($_GET['famille']) ? $_GET['famille'] : "gonflage";
        
        $size = isset($_GET['size']) ? $_GET['size'] : 6;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $size;
        
        if ($famille == "all") {
            $requete = "SELECT * FROM produit
                        WHERE nomproduit LIKE '%$nomp%'
                        LIMIT $size
                        OFFSET $offset";
            $requeteCount = "SELECT COUNT(*) AS countF FROM produit
                             WHERE nomproduit LIKE '%$nomp%'";
        } else {
            $condition = $nomp != '' ? "WHERE nomproduit LIKE '%$nomp%' AND" : "WHERE ";
            $requete = "SELECT * FROM produit ". $condition." famille = '$famille' LIMIT $size OFFSET $offset";
            $requeteCount = "SELECT COUNT(*) AS countF FROM produit
                             WHERE nomproduit LIKE '%$nomp%'
                             AND famille = '$famille'";
        }

        $resultatF = $pdo->query($requete);
        $produits = $resultatF->fetchAll();
        
    
        $resultatCount = $pdo->query($requeteCount);
        $tabCount = $resultatCount->fetch();
        $nbrproduit = $tabCount['countF'];
        $reste = $nbrproduit % $size;
        if ($reste === 0){
            $nbrPage = $nbrproduit / $size;}
        else{
            $nbrPage = floor($nbrproduit / $size) + 1;
        }

            // Requête pour récupérer les produits sélectionnés du client connecté
            $requeteProduitsSelectionnes = "SELECT p.idproduit, p.nomproduit, ps.quantite, ps.prix
            FROM produits_selectionnes ps
            INNER JOIN produit p ON ps.idproduit = p.idproduit
            WHERE ps.idclient = :idClient";
            

$stmt = $pdo->prepare($requeteProduitsSelectionnes);
$stmt->bindParam(':idClient', $_SESSION['idClient']);
$stmt->execute();

$produitsSelectionnes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
<script>
    data = <?php echo json_encode($produitsSelectionnes); ?>;
    produitsSelectionnes = [];

    data.forEach(function(produit) {
        produitsSelectionnes.push({
            id: produit.idproduit,
            nom: produit.nomproduit,
            quantite: produit.quantite,
            prix: parseFloat(produit.prix)
        });
    });
</script>
<div class="side-panel">
    <h4><?php echo $nouveauNomClient . ' ' . $nouveauPrenomClient; ?></h4>
    <div id="produits-selectionnes">
        <?php foreach ($produitsSelectionnes as $produit): ?>
            <div><?= $produit['nomproduit']; ?> x <?= $produit['quantite']; ?></div>
            <div><?= $produit['prix'] ?></div>
        <?php endforeach; ?>
    </div>
    <div id="total"></div>
    <button class="enregistrer" type="submit" onclick="sendSelectedProducts()">Valider</button>
</div>
<div class="page_famille">
    <h2 id="famille">Plongée</h2>
    <div class="ligne">
        <?php foreach ($produits as $produit): 
            if(in_array($produit['idproduit'], array_column($produitsSelectionnes, 'idproduit'))){
                $produitSelectKey = array_search($produit['idproduit'], array_column($produitsSelectionnes, 'idproduit'));
                $produitSelect = $produitsSelectionnes[$produitSelectKey];
            }else{
                $produitSelect = null;
            }
            ?>
            
            <div class="produit">
                <span id="nomproduit-<?php echo $produit['idproduit']; ?>"><?php echo $produit['nomproduit']; ?></span><br>
                <div class="prix-produit">
                    <span id="prix-<?php echo $produit['idproduit']; ?>"><?php echo $produit['prix']; ?>€</span>
                </div>
                <div class="input-group">
                    <button onclick="decrement(<?php echo $produit['idproduit']; ?>)">-</button>
                    <input type="number" id="num-personnes-<?php echo $produit['idproduit']; ?>" min="0" max="100" value="<?= $produitSelect ? $produitSelect['quantite'] : '0'; ?>" data-productid="<?php echo $produit['idproduit']; ?>" onchange="updateQuantite(<?php echo $produit['idproduit']; ?>)">
                    <button onclick="increment(<?php echo $produit['idproduit']; ?>)">+</button>
                </div>
                <div id="quantite-<?php echo $produit['idproduit']; ?>"></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
// Mettre à jour l'affichage des produits sélectionnés
// updateListeProduitsSelectionnes();
function updateQuantite(productId) {
    var inputElement = document.getElementById("num-personnes-" + productId);
    var quantiteElement = document.getElementById("quantite-" + productId);
    var produitElement = document.getElementById("nomproduit-" + productId);
    var prixElement = document.getElementById("prix-" + productId);

    var quantity = parseInt(inputElement.value);
    var produitExistant = produitsSelectionnes.find(produit => produit.id == productId);
    if (produitExistant) {
        
        
        produitExistant.quantite = parseInt(quantity);
    } else {
        var nomProduit = produitElement.textContent;

        produitsSelectionnes.push({
            id: productId,
            nom: nomProduit,
            quantite: quantity,
            prix: parseFloat(prixElement.textContent)
        });
      
        
    }
    console.log('produitExistant');
    console.log(produitExistant);
    console.log('produitsSelectionnes');
    console.log(produitsSelectionnes);
 
    // Mettez à jour l'affichage des produits sélectionnés
    updateListeProduitsSelectionnes();
}
   
function isProductSelected(productId) {
    for (var i = 0; i < produitsSelectionnes.length; i++) {
        if (produitsSelectionnes[i].id === productId) {
            return true;
        }
    }
    return false;
}

function updateListeProduitsSelectionnes() {
    var produitsSelectionnesContainer = document.getElementById("produits-selectionnes");
    produitsSelectionnesContainer.innerHTML = "";

    var total = 0;

    for (var i = 0; i < produitsSelectionnes.length; i++) {
        var produit = produitsSelectionnes[i];

        if (produit.quantite > 0) {
            var produitDiv = document.createElement("div");
            var prixUnitaire = parseFloat(produit.prix) * parseFloat(produit.quantite);

            if (produit.nom === "Présent ce week end") {
                produitDiv.classList.add("produit-weekend");
                produitDiv.textContent = produit.nom;
            } else {
                produitDiv.textContent = produit.nom + " x " + produit.quantite;

                if (prixUnitaire > 0) {
                    var prixElement = document.createElement("span");
                    prixElement.textContent = "Prix " + prixUnitaire.toFixed(2) + "€";
                    prixElement.classList.add("prix-selectionne");
                    produitDiv.appendChild(prixElement);
                }
            }

            produitsSelectionnesContainer.appendChild(produitDiv);

            if (produit.nom !== "Présent ce week end") {
                var sousTotal = parseFloat(prixUnitaire);
                total += sousTotal;
            }
        }
    }

    var totalElement = document.getElementById("total");
    totalElement.textContent = "Total : " + total.toFixed(2) + "€";
}

function decrement(productId) {
    var inputElement = document.getElementById("num-personnes-" + productId);
    if (inputElement.value > 0) {
        inputElement.value = Math.max(parseInt(inputElement.value) - 1, 0);
        inputElement.dispatchEvent(new Event('change'));
        // updateQuantite(productId);
    }
}

function increment(productId) {
    var inputElement = document.getElementById("num-personnes-" + productId);
    inputElement.value = parseInt(inputElement.value) + 1;
    inputElement.dispatchEvent(new Event('change'));
    // updateQuantite(productId, inputElement.value);
}

function sendSelectedProducts() {
    var produitsSelectionnes = <?php echo json_encode($produitsSelectionnes); ?>;
    
    // Vérifier si la liste des produits sélectionnés est vide
    if (produitsSelectionnes.length === 0) {
        alert("La liste des produits sélectionnés est vide !");
        return;
    }

    // Créer une requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_products.php", true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Gérer la réponse de la requête
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Réponse reçue avec succès
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Les produits sélectionnés ont été enregistrés avec succès !");
                } else {
                    alert("Une erreur est survenue lors de l'enregistrement des produits.");
                }
            } else {
                // Erreur lors de la requête
                alert("Une erreur est survenue lors de l'envoi de la requête.");
            }
        }
        updateListeProduitsSelectionnes();
    };

    // Envoyer la requête avec les données des produits sélectionnés
    xhr.send(JSON.stringify(produitsSelectionnes));
}


    </script>
</body>
</html>