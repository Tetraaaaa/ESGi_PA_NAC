<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Réservation</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet_details.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../Js/script.js" async></script>
    <style>
        /* Ajoutez ici votre propre CSS pour personnaliser la page */
    </style>
</head>
<body class="container text-center">
<?php
session_start();

// Vérifier si l'utilisateur est connecté et si son statut est celui d'un administrateur
if (!isset($_SESSION['status']) || $_SESSION['status'] != 0) {
    // Rediriger l'utilisateur vers la page d'accueil ou de connexion si ce n'est pas un administrateur
    header("Location: index.php");
    exit; // Important pour arrêter l'exécution du script après la redirection
}
include 'header.php';
include 'verif.php';
include 'include/db.php';

// Récupération de toutes les réservations depuis la table location
$query = "SELECT * FROM LOCATION";
$stmt = $bdd->query($query);
$reservations = $stmt->fetchAll();

// Vérification si des réservations sont trouvées
if (count($reservations) > 0) { ?>
    <div class="container mt-5">
        <h2>Liste des Réservations :</h2>
        <table class="table">
            <thead>
            <tr>
                <th>ID de la réservation</th>
                <th>Utilisateur</th>
                <th>Point d'arrêt</th>
                <th>Hébergement</th>
                <th>Supplément</th>
                <th>Nombre de personnes</th>
                <th>Prix total</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reservations as $reservation) { ?>
                <tr>
                    <td><?php echo $reservation['id']; ?></td>
                    <td><?php echo $reservation['id_USER']; ?></td>
                    <td><?php echo $reservation['id_LOGEMENT']; ?></td>
                    <td><?php echo $reservation['nom']; ?></td>
                    <td><?php echo $reservation['taille']; ?></td>
                    <td><?php echo $reservation['emplacement']; ?></td>
                    <td><?php echo $reservation['date_debut']; ?></td>
                    <td><?php echo $reservation['date_fin']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="container mt-5">
        <p>Aucune réservation trouvée.</p>
    </div>
<?php } ?>
</body>

</html>
