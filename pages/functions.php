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