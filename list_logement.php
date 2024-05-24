<?php
session_start(); // Démarrer la session dès le début

// Inclure le fichier de connexion à la base de données
include 'include/db.php';

// Récupérer l'ID de l'utilisateur connecté
$id_user = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Vérifier si l'utilisateur est connecté
if (!$id_user) {
    header("Location: login.php?message=Veuillez vous connecter");
    exit();
}

// Récupérer tous les logements disponibles pour la réservation
$query = "SELECT LOGEMENT.*, TYPE_LOGEMENT.name AS type_logement FROM LOGEMENT LEFT JOIN TYPE_LOGEMENT ON LOGEMENT.type_bien = TYPE_LOGEMENT.id WHERE LOGEMENT.validation = 1"; // Supposons que validation = 1 signifie que le logement est disponible
$stmt = $bdd->prepare($query);
$stmt->execute();
$logements = $stmt->fetchAll();

?>
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
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Liste des Logements Disponibles</h2>
        <?php if (count($logements) > 0) : ?>
            <div class="row">
                <?php foreach ($logements as $logement) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">Type de Logement: <?php echo htmlspecialchars($logement['type_logement']); ?></p>
                                <p class="card-text">Capacité: <?php echo htmlspecialchars($logement['capacite_location']); ?></p>
                                <p class="card-text">Adresse: <?php echo htmlspecialchars($logement['adresse']); ?></p>
                                <p class="card-text">Ville: <?php echo htmlspecialchars($logement['ville']); ?></p>
                                <p class="card-text">Code Postal: <?php echo htmlspecialchars($logement['code_postal']); ?></p>
                                <p class="card-text">Pays: <?php echo htmlspecialchars($logement['pays']); ?></p>
                                <p class="card-text">Description: <?php echo htmlspecialchars($logement['description']); ?></p>
                                <p class="card-text">Heure de contact: <?php echo date('H:i', strtotime($logement['heure_de_contacte'])); ?></p>
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
