<?php
session_start();?>
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

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $logement_id = intval($_GET['id']);

    $query = "SELECT * FROM LOGEMENT WHERE id = :id";
    $stmt = $bdd->prepare($query);
    $stmt->execute(['id' => $logement_id]);
    $logement = $stmt->fetch();

    if ($logement) {
        $query_type_logement = "SELECT name FROM TYPE_LOGEMENT WHERE id = :type_bien";
        $stmt_type_logement = $bdd->prepare($query_type_logement);
        $stmt_type_logement->execute(['type_bien' => $logement['type_bien']]);
        $type_logement = $stmt_type_logement->fetchColumn();
    } else {
        header("Location: index.php?message=Logement introuvable");
        exit();
    }
} else {
    header("Location: index.php?message=Aucun logement sélectionné");
    exit();
}
?>

    <div class="container mt-5">
        <h2>Détails du Logement</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Logement ID: <?php echo htmlspecialchars($logement['id']); ?></h5>
                <p class="card-text">Type de Logement: <?php echo htmlspecialchars($type_logement); ?></p>
                <p class="card-text">Capacité: <?php echo htmlspecialchars($logement['capacite_location']); ?></p>
                <p class="card-text">Adresse: <?php echo htmlspecialchars($logement['adresse']); ?></p>
                <p class="card-text">Ville: <?php echo htmlspecialchars($logement['ville']); ?></p>
                <p class="card-text">Code Postal: <?php echo htmlspecialchars($logement['code_postal']); ?></p>
                <p class="card-text">Pays: <?php echo htmlspecialchars($logement['pays']); ?></p>
                <p class="card-text">Description: <?php echo htmlspecialchars($logement['description']); ?></p>
                <p class="card-text">Heure de Contact: <?php echo date('H:i', strtotime($logement['heure_de_contacte'])); ?></p>
                <a href="reservation.php?id=<?php echo $logement['id']; ?>" class="btn btn-primary">Réserver</a>
            </div>
        </div>
    </div>
</body>

</html>
