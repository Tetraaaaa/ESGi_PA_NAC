<?php
require_once 'include/connection_db.php';

if (isset($_GET['id'])) {
    $serviceId = intval($_GET['id']);

    // URL de l'API pour récupérer les détails du service
    $apiUrl = "http://91.134.89.127:8080/services/id/" . $serviceId;

    // Initialiser cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Exécuter la requête cURL et stocker la réponse
    $response = curl_exec($ch);

    // Vérifier les erreurs cURL
    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
    } else {
        // Décoder la réponse JSON
        $service = json_decode($response, true);

        // Fermer cURL
        curl_close($ch);

        // Vérifier si le service a été trouvé
        if ($service) {
            echo '<h4>Détails du Service</h4>';
            echo '<p><strong>Type:</strong> ' . htmlspecialchars($service['type']) . '</p>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($service['description']) . '</p>';
        } else {
            echo '<p>Aucun détail trouvé pour ce service.</p>';
        }
    }
} else {
    echo '<p>ID de service non spécifié.</p>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Service</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body class="container text-center">
    <?php include 'header.php'; ?>
    <div class="container mt-5">
    </div>
</body>
</html>
