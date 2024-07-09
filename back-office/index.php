<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet_details.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../Js/script.js" async></script>
    <style>
        /* Ajoutez ici votre propre CSS pour personnaliser la page */
    </style>
</head>

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
?>
<body class="container text-center">
<h1>Accueil</h1>

<section>
    <h2><a href="user.php">Utilisateur</a></h2>
    <!-- Contenu de la section Utilisateur -->
    <p>Voir Utilisateur.</p>
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>

<section>
    <h2><a href="service.php">Service</a></h2>
    <!-- Contenu de la section Service -->
    <p>Voir Service.</p>
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>
<section>
    <h2><a href="service_verif.php">Service en cours de validation</a></h2>
    <!-- Contenu de la section Déconnexion -->
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>

<section>
    <h2><a href="reservation.php">Réservation</a></h2>
    <!-- Contenu de la section Réservation -->
    <p>Voir Reservation.</p>
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>

<section>
    <h2><a href="logement.php">Logement</a></h2>
    <!-- Contenu de la section Réservation -->
    <p>Voir Logement.</p>
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>

<section>
    <h2><a href="caracteristique.php">Caractéristique</a></h2>
    <!-- Contenu de la section Réservation -->
    <p>Voir Caractéristique.</p>
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>

<section>
    <h2><a href="filtre_icone.php">Filtre icone</a></h2>
    <!-- Contenu de la section Réservation -->
    <p>Voir Caractéristique.</p>
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>

<section>
    <h2><a href="deconnexion.php">Déconnexion</a></h2>
    <!-- Contenu de la section Déconnexion -->
    <p>Vous pouvez vous déconnecter ici.</p>
    <!-- Ajoutez ici tout contenu spécifique à cette section -->
</section>



</body>
</html>