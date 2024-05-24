<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Récapitulatif de la Réservation</title>
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
    include 'include/db.php';

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $query = "SELECT * FROM LOGEMENT WHERE id = :id";
        $stmt = $bdd->prepare($query);
        $stmt->execute(['id' => $_GET['id']]);
        $logement = $stmt->fetch();

        if ($logement) {
            $query_type_logement = "SELECT name FROM TYPE_LOGEMENT WHERE id=:type_bien";
            $stmt_type_logement = $bdd->prepare($query_type_logement);
            $stmt_type_logement->execute(['type_bien' => $logement['type_bien']]);
            $type_logement = $stmt_type_logement->fetchColumn();
            ?>
            <div class="container mt-5">
                <h2>Récapitulatif de la Réservation</h2>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Logement ID: <?php echo $logement['id']; ?></h5>
                        <p class="card-text">Type de Logement: <?php echo $type_logement; ?></p>
                        <p class="card-text">Capacité: <?php echo $logement['capacite_location']; ?></p>
                        <p class="card-text">Adresse: <?php echo $logement['adresse']; ?></p>
                        <p class="card-text">Ville: <?php echo $logement['ville']; ?></p>
                        <p class="card-text">Code Postal: <?php echo $logement['code_postal']; ?></p>
                        <p class="card-text">Pays: <?php echo $logement['pays']; ?></p>
                        <p class="card-text">Description: <?php echo $logement['description']; ?></p>
                        <p class="card-text">Heure de contact: <?php echo date('H:i', strtotime($logement['heure_de_contacte'])); ?></p>
                        <a href="reservation_traitement.php?id=<?php echo $logement['id']; ?>" class="btn btn-primary">Réserver</a>
                    </div>
                </div>
            </div>
            <?php
        } else {
            header("Location: index.php?message=Logement introuvable");
            exit();
        }
    } else {
        header("Location: index.php?message=Aucune logement selectionné");
        exit();
    }
    ?>
</body>

</html>
