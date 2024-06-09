<?php
session_start();
require 'include/db.php';
include_once 'init.php'; // Inclusion du fichier pour gérer les traductions

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


$logements = get_logements($api);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['Liste des Logements']; ?></title>
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
        <h1><?php echo $translations['Liste des Logements']; ?></h1>
        <div class="row">
            <?php
            if ($logements) {
                foreach ($logements as $logement) {
                    $photo = get_photo($api, $logement['id']);
                    $photo_url = isset($photo['emplacement']) ? $photo['emplacement'] : 'default_photo.jpg';
                    
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($photo_url) . '" class="card-img-top" alt="Photo du logement">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($logement['nom']) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars(truncate_text($logement['description'])) . '</p>';
                    echo '<p class="card-text"><small class="text-muted">' . $translations['Prix'] . ': ' . htmlspecialchars($logement['prix']) . '€ ' . $translations['par nuit'] . '</small></p>';
                    echo '<a href="detail_logement.php?id=' . $logement['id'] . '" class="btn btn-primary">' . $translations['Réserver'] . '</a>';
                    echo '</div>'; // End card-body
                    echo '</div>'; // End card
                    echo '</div>'; // End col
                }
            } else {
                echo 'Erreur lors de la récupération des logements';
            }
            ?>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
