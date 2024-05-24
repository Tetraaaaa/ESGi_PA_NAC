<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Liste des Logements</title>
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

    // Récupération de tous les logements
    $query = "SELECT * FROM LOGEMENT";
    $stmt = $bdd->query($query);
    $logements = $stmt->fetchAll();
    if(isset($_GET['message'])){
        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['message']) . '</div>';
    }

    if (count($logements) > 0) { ;?>
        <div class="container mt-5">
            <h2>Liste des Logements</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID du Logement</th>
                        <th>Type de Logement</th>
                        <th>Capacité</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logements as $logement) {
                        $query_type_logement = "SELECT name FROM TYPE_LOGEMENT WHERE id=:type_bien";
                        $stmt_type_logement = $bdd->prepare($query_type_logement);
                        $stmt_type_logement->execute(['type_bien' => $logement['type_bien']]);
                        $type_logement = $stmt_type_logement->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $logement['id']; ?></td>
                            <td><?php echo $type_logement; ?></td>
                            <td><?php echo $logement['capacite_location']; ?></td>
                            <td>
                                <a href="reservation.php?id=<?php echo $logement['id']; ?>" class="btn btn-primary">Réserver</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="container mt-5">
            <p>Aucun logement trouvé.</p>
        </div>
    <?php } ?>
</body>

</html>
