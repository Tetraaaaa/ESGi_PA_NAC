<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="css/header.css">
</head>
<body>

<body class="container text-center">
    <?php include 'header.php'; 
    include 'include/db.php';
    session_start();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $logement_id = intval($_GET['id']);

    $query = "SELECT * FROM LOGEMENT WHERE id = :id";
    $stmt = $bdd->prepare($query);
    $stmt->execute(['id' => $logement_id]);
    $logement = $stmt->fetch();

    if (!$logement) {
        header("Location: index.php?message=Logement introuvable");
        exit();
    }
    
    // Récupérer le prix du logement depuis la base de données
    $prix = $logement['prix'];
} else {
    header("Location: index.php?message=Aucun logement sélectionné");
    exit();
}
?>

<div class="container mt-5">
    <div id="stripe-error-message" class="text-danger"></div>
    <h2>Réservation du Logement</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Logement ID: <?php echo htmlspecialchars($logement['id']); ?></h5>
            <form action="reservation_traitement.php" method="POST" id="payment-form">
                <input type="hidden" name="logement_id" value="<?php echo $logement['id']; ?>">
                
                <!-- Ajouter des champs pour les dates de début et de fin -->
                <label for="date_debut">Date de début :</label>
                <input type="date" id="date_debut" name="date_debut" required><br><br>
                
                <label for="date_fin">Date de fin :</label>
                <input type="date" id="date_fin" name="date_fin" required><br><br>

                <!-- Afficher le prix récupéré depuis la base de données -->
                <label for="prix">Prix par nuit : <?php echo $prix; ?></label>
                
                <!-- Ajouter un champ caché pour le prix -->
                <input type="hidden" id="prix_par_nuit" name="prix" value="<?php echo $prix; ?>">
                
                <!-- Afficher le prix total calculé -->
                <label for="prix_total">Prix total : <span id="prix_total">0</span> EUR</label>
                
                <div id="card-element">
                    <!-- Stripe Element sera inséré ici -->
                </div>

                <!-- Utilisé pour afficher les erreurs de formulaire -->
                <div id="card-errors" role="alert"></div>

                <button id="submit">Payer avec Stripe</button>
            </form>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    // Récupérer les champs de dates et de prix
    var dateDebutField = document.getElementById('date_debut');
    var dateFinField = document.getElementById('date_fin');
    var prixField = document.getElementById('prix');
    var prixTotalElement = document.getElementById('prix_total');

    // Calculer le prix total lorsque les dates de début et de fin changent
    dateDebutField.addEventListener('change', updatePrixTotal);
    dateFinField.addEventListener('change', updatePrixTotal);

    function updatePrixTotal() {
        var dateDebut = new Date(dateDebutField.value);
        var dateFin = new Date(dateFinField.value);
        var prixParNuit = parseFloat(document.getElementById('prix_par_nuit').value);
        
        // Vérifier si les champs de dates sont valides
        if (!isNaN(dateDebut.getTime()) && !isNaN(dateFin.getTime())) {
            // Calculer le nombre de jours de réservation
            var differenceInTime = dateFin.getTime() - dateDebut.getTime();
            var differenceInDays = differenceInTime / (1000 * 3600 * 24);
            
            // Calculer le prix total
            var prixTotal = differenceInDays * prixParNuit;
            
            // Vérifier si le prix total est un nombre valide
            if (!isNaN(prixTotal)) {
                // Mettre à jour l'affichage du prix total
                prixTotalElement.textContent = prixTotal.toFixed(2);
                return;
            }
        }
        
        // Afficher un message d'erreur si le calcul du prix total échoue
        prixTotalElement.textContent = 'Calcul du prix total impossible';
    }

    var stripe = Stripe('pk_test_51PFa0VDDadRJj5EqsPvLN0v2RU3Rxz6Y95qgsK4rWEZTAfQgfo86k4PXYU2RyHV0QRgK4fqCVKUkBIqcUGU5uWrj00niWyaDhd');
    var elements = stripe.elements();

    var style = {
        base: {
            color: "#32325d",
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": {
                color: "#aab7c4"
            }
        },
        invalid: {
            color: "#fa755a",
            iconColor: "#fa755a"
        }
    };

    var card = elements.create("card", { style: style });
    card.mount("#card-element");

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');
    var errorMessageElement = document.getElementById('stripe-error-message');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                errorMessageElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Soumettre le formulaire
        form.submit();
    }
</script>

