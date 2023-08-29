<!DOCTYPE html>
<html>
<head>
    <title>Liste des produits</title>
    <link rel="stylesheet" href="../css/famille.css">
</head>
<body>
    <?php
        require_once('identifier.php');
        require_once('connexiondb.php');
        include('header.php');

        session_start();

        if (isset($_SESSION['produitsSelectionnes']) && !empty($_SESSION['produitsSelectionnes'])) {
            $produitsSelectionnes = $_SESSION['produitsSelectionnes'];
        } else {
            $produitsSelectionnes = array();
        }

        // Récupérer le nouveau nom et prénom du client sélectionné
        $nouveauNomClient = $client['nom'];
        $nouveauPrenomClient = $client['prenom'];
        $nouvelID = $client['idclient'];
    
        // Mettre à jour les variables de session
        $_SESSION['nomClient'] = $nouveauNomClient;
        $_SESSION['prenomClient'] = $nouveauPrenomClient;
        $_SESSION['IDclient'] = $nouvelID;
        $_SESSION['produitsSelectionnes'] = $produitsSelectionnes;
       
        $nomp = isset($_GET['nomP']) ? $_GET['nomP'] : "";
        $famille = isset($_GET['famille']) ? $_GET['famille'] : "restaurant";
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
            $requete = "SELECT * FROM produit
                        WHERE nomproduit LIKE '%$nomp%'
                        AND famille = '$famille'
                        LIMIT $size
                        OFFSET $offset";
            
            $requeteCount = "SELECT COUNT(*) AS countF FROM produit
                             WHERE nomproduit LIKE '%$nomp%'
                             AND famille = '$famille'";
        }

        $resultatF = $pdo->query($requete);
    
        $resultatCount = $pdo->query($requeteCount);
        $tabCount = $resultatCount->fetch();
        $nbrproduit = $tabCount['countF'];
        $reste = $nbrproduit % $size;
        if ($reste === 0)
            $nbrPage = $nbrproduit / $size;
        else
            $nbrPage = floor($nbrproduit / $size) + 1;
    ?>
    <div class="side-panel">
        <h4><?php echo $nomClient . ' ' . $prenomClient; ?></h4>
        <h5>Produits sélectionnés</h5>
        <div id="produits-selectionnes"></div>
        <div id="total"></div>
        <button class="enregistrer" type="submit" onclick="sendSelectedProducts()">Valider</button>
    </div>
    
    <div class="page_famille">
        <h2 id="famille">Carte</h2>
        <div class="ligne">
            <?php while ($produit = $resultatF->fetch()) { ?>
                <div class="produit">
                    <span id="nomproduit-<?php echo $produit['idproduit']; ?>"><?php echo $produit['nomproduit']; ?></span><br>
                    <div class="prix-produit">
                        <span id="prix-<?php echo $produit['idproduit']; ?>"><?php echo $produit['prix']; ?>€</span>
                    </div>
                    <div class="input-group">
                        <button onclick="decrement(<?php echo $produit['idproduit']; ?>)">-</button>
                        <input type="number" id="num-personnes-<?php echo $produit['idproduit']; ?>" min="0" max="100" value="0" data-productid="<?php echo $produit['idproduit']; ?>" onchange="updateQuantite(<?php echo $produit['idproduit']; ?>)">
                        <button onclick="increment(<?php echo $produit['idproduit']; ?>)">+</button>
                    </div>
                    <div id="quantite-<?php echo $produit['idproduit']; ?>"></div>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <script>
        var produitsSelectionnes = <?php echo json_encode($produitsSelectionnes); ?>;

        function updateQuantite(productId) {
            var inputElement = document.getElementById("num-personnes-" + productId);
            var quantiteElement = document.getElementById("quantite-" + productId);
            var produitElement = document.getElementById("nomproduit-" + productId);
            var prixElement = document.getElementById("prix-" + productId);

            var quantity = inputElement.value;

            if (quantity > 0) {
                var produitExistant = produitsSelectionnes.find(function(produit) {
                    return produit.id === productId;
                });

                if (produitExistant) {
                    produitExistant.quantite = quantity;
                } else {
                    produitsSelectionnes.push({ id: productId, nom: produitElement.textContent, quantite: quantity, prix: parseFloat(prixElement.textContent) });
                }
            } else {
                quantiteElement.textContent = "";

                produitsSelectionnes = produitsSelectionnes.filter(function(produit) {
                    return produit.id !== productId;
                });
            }

            updateListeProduitsSelectionnes();
        }

        function updateListeProduitsSelectionnes() {
            var produitsSelectionnesContainer = document.getElementById("produits-selectionnes");
            produitsSelectionnesContainer.innerHTML = "";

            var total = 0;

            produitsSelectionnes.forEach(function(produit) {
                if (produit.quantite > 0) {
                    var produitDiv = document.createElement("div");
                    var prixUnitaire = parseFloat(produit.prix) * parseFloat(produit.quantite);
                    produitDiv.textContent = produit.nom + " x " + produit.quantite;

                    if (produit.nom === "Présent ce week end") {
                        var produitDiv = document.createElement("div");
                        produitDiv.classList.add("produit-weekend");
                        produitDiv.textContent = produit.nom;
                    } else {
                        var produitDiv = document.createElement("div");
                        produitDiv.textContent = produit.nom + " x " + produit.quantite;
                    }
                    
                    var prixElement = document.createElement("span");
                    prixElement.textContent = "Prix " + prixUnitaire.toFixed(2) + "€";

                    if (prixUnitaire > 0) {
                        prixElement.classList.add("prix-selectionne");
                    } else {
                        prixElement.classList.add("prix-produit");
                    }

                    produitDiv.appendChild(prixElement);
                    produitsSelectionnesContainer.appendChild(produitDiv);

                    var sousTotal = parseFloat(prixUnitaire);
                    total += sousTotal;
                }
            });

            var totalElement = document.getElementById("total");
            totalElement.textContent = "Total : " + total.toFixed(2) + "€";
        }

        function decrement(productId) {
            var inputElement = document.getElementById("num-personnes-" + productId);
            if (inputElement.value > 0) {
                inputElement.value--;
                updateQuantite(productId);
            }
        }

        function increment(productId) {
            var inputElement = document.getElementById("num-personnes-" + productId);
            inputElement.value++;
            updateQuantite(productId);
        }

        function sendSelectedProducts() {
            var selectedProductsJson = JSON.stringify(produitsSelectionnes);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "save_products.php?idClient=", true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };

            xhr.send(selectedProductsJson);
        }
    </script>
</body>
</html>
