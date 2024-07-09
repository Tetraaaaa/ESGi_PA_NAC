<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur de Réservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Erreur de Réservation</h1>
        <div class="alert alert-danger mt-4">
            <?php
            if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                    case 'nodates':
                        echo 'Aucune date de réservation sélectionnée.';
                        break;
                    case 'invalidDates':
                        echo 'Les dates sélectionnées ne sont pas valides.';
                        break;
                    case 'payment':
                        echo 'Erreur lors du paiement. Veuillez réessayer.';
                        break;
                    default:
                        echo 'Une erreur est survenue lors de la réservation.';
                        break;
                }
            } else {
                echo 'Une erreur inconnue est survenue.';
            }
            ?>
        </div>
        <a href="reservation.php" class="btn btn-primary">Retour à la page de réservation</a>
    </div>
</body>
</html>
