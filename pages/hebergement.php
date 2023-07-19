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
       
        $nomp = isset($_GET['nomP']) ? $_GET['nomP'] : "";
        $famille = isset($_GET['famille']) ? $_GET['famille'] : "hebergement";
        
        $size = isset($_GET['size']) ? $_GET['size'] : 6;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $size;
        
        if ($famille == "hebergement") {
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
    <header>
    <?php
    include('header.php');
    ?>
    </header>
    <div class="side-panel">
        <h4><?php echo $nom . ' ' . $prenom; ?></h4>
        <h5>Produits sélectionnés</h5>
        <div id="produits-selectionnes"></div>
        <div id="total"></div>
        <button class="enregistrer" type="submit" onclick="sendSelectedProducts()">Valider</button>
    </div>
    
    <div class="page_famille">
        <h2 id="famille">Plongée</h2>
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
        var produitsSelectionnes = [];
        

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

            updateListeProduitsSelectionnes(); // Appel de la fonction pour afficher les produits sélectionnés
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
                    
                    var prixElement = document.createElement("span");
                    prixElement.classList.add("prix-produit");
                    prixElement.textContent = "Prix " + prixUnitaire.toFixed(2) + "€";

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
            // Convertir la liste des produits sélectionnés en une chaîne JSON
            var selectedProductsJson = JSON.stringify(produitsSelectionnes);

            // Créer une requête AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "save_products.php?idClient=", true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            // Gérer la réponse de la requête
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Afficher une notification ou effectuer toute autre action après l'enregistrement réussi
                    alert(xhr.responseText);
                }
            };

            // Envoyer la requête avec les données des produits sélectionnés
            xhr.send(selectedProductsJson);
        }
    </script>
</body>
</html>