<?php
session_start();
require_once 'include/db.php';

function truncate_text($text, $chars = 150) {
    if (strlen($text) > $chars) {
        $text = substr($text, 0, $chars) . "…";
    }
    return $text;
}

function get_logements($api) {
    $url = $api . 'logements';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function get_photo($api, $id) {
    $url = $api . 'logements/photo/id/' . $id;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$logements = get_logements($api);?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Logements</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <?php require_once 'header.php'; ?>

    <main class="container main-background">
        <h1>Liste des Logements</h1>
        <div class="row">
            <?php
            if ($logements) {
                foreach ($logements as $logement) {
                    $photos = get_photo($api, $logement['id']);
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card">';
                    if ($photos && count($photos) > 0) {
                        $first_photo = $photos[0];
                        echo '<img src="' . htmlspecialchars($first_photo['emplacement']) . '" class="card-img-top" alt="' . htmlspecialchars($first_photo['name']) . '">';
                    } else {
                        echo '<img src="default.jpg" class="card-img-top" alt="Photo du logement">';
                    }
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($logement['nom']) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars(truncate_text($logement['description'])) . '</p>';
                    echo '<p class="card-text"><small class="text-muted">Prix: ' . htmlspecialchars($logement['prix']) . '€ par nuit</small></p>';
                    echo '<a href="detail_logement.php?id=' . $logement['id'] . '" class="btn btn-primary">Réserver</a>';
                    echo '</div>'; // End card-body
                    echo '</div>'; // End card
                    echo '</div>'; // End col
                }
            } else {
                echo '<p>Aucun logement trouvé.</p>';
            }
            ?>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
