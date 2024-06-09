<?php
session_start(); // Démarrer la session dès le début

// Inclure le fichier pour gérer les traductions
include_once 'init.php'; 

// Récupérer l'ID de l'utilisateur connecté
$id_user = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Vérifier si l'utilisateur est connecté
if (!$id_user) {
    header("Location: login.php?message=Veuillez vous connecter");
    exit();
}

function get_logements($api) {
    $url = $api;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$logements = get_logements($api);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des Logements Disponibles</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="css/header.css">
</head>
<body class="container text-center">
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Liste des Logements Disponibles</h2>
        <?php if (is_array($logements) && count($logements) > 0) : ?>
            <div class="row">
                <?php foreach ($logements as $logement) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($logement['nom']); ?></h5>
                                <p class="card-text">Type de Logement: <?php echo htmlspecialchars($logement['typeBien']); ?></p>
                                <p class="card-text">Capacité: <?php echo htmlspecialchars($logement['capaciteLocation']); ?></p>
                                <p class="card-text">Adresse: <?php echo htmlspecialchars($logement['adresse']); ?></p>
                                <p class="card-text">Ville: <?php echo htmlspecialchars($logement['ville']); ?></p>
                                <p class="card-text">Code Postal: <?php echo htmlspecialchars($logement['codePostal']); ?></p>
                                <p class="card-text">Pays: <?php echo htmlspecialchars($logement['pays']); ?></p>
                                <p class="card-text">Description: <?php echo htmlspecialchars($logement['description']); ?></p>
                                <p class="card-text">Heure de contact: <?php echo date('H:i', strtotime($logement['heureDeContacte'])); ?></p>
                                <a href="view_reservation.php?id=<?php echo $logement['id']; ?>" class="btn btn-primary">Réserver</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Aucun logement disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
