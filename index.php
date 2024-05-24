<?php
session_start();
require_once 'include/connection_db.php'; // Connexion à la BDD

function truncate_text($text, $chars = 150) {
    if (strlen($text) > $chars) {
        $text = substr($text, 0, $chars) . "…";
    }
    return $text;
}

?>

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
    <style>
        .card-img-top {
            width: 100%; /* Ensure the image covers the card width */
            height: 200px; /* Set a fixed height */
            object-fit: cover; /* Ensures the image covers the area, and is cropped if larger than the container */
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Add shadow effect */
            transition: 0.3s; /* Transition for hover effect */
        }
        .card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2); /* Enhanced shadow on hover */
        }
    </style>
</head>
<body>
    <?php require_once 'header.php'; ?>

    <main class="container">
        <h1>Liste des Logements</h1>
        <div class="row">
            <?php
            try {
                $stmt = $bdd->query("SELECT LOGEMENT.*, PHOTO_LOGEMENT.emplacement AS photo FROM LOGEMENT LEFT JOIN PHOTO_LOGEMENT ON LOGEMENT.id = PHOTO_LOGEMENT.id_LOGEMENT GROUP BY LOGEMENT.id ORDER BY LOGEMENT.id");
                while ($logement = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($logement['photo']) . '" class="card-img-top" alt="Photo du logement">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($logement['nom']) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars(truncate_text($logement['description'])) . '</p>';
                    echo '<p class="card-text"><small class="text-muted">Prix: ' . htmlspecialchars($logement['prix']) . '€ par nuit</small></p>';
                    echo '<a href="detail_logement.php?id=' . $logement['id'] . '" class="btn btn-primary">Réserver</a>';
                    echo '</div>'; // End card-body
                    echo '</div>'; // End card
                    echo '</div>'; // End col
                }
            } catch (PDOException $e) {
                echo 'Erreur lors de la récupération des logements: ' . $e->getMessage();
            }
            ?>
        </div>
    </main>

  
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
