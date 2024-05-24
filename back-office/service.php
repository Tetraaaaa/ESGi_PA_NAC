<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Service</title>
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
include 'header.php';
include 'verif.php';
include 'include/db.php';
$q = 'SELECT * FROM SERVICE';
$req = $bdd->query($q);
$services = $req->fetchAll();

if (count($services) > 0) { ?>
    <div class="container mt-5">
        <h2>Liste des Services :</h2>
        <table class="table">
            <thead>
            <tr>
                <th>ID du service</th>
                <th>Type</th>
                <th>Prix</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($services as $service) { ?>
                <tr>
                    <td><?php echo $service['id']; ?></td>
                    <td><?php echo $service['type']; ?></td>
                    <td><?php echo $service['prix']; ?></td>
                    <td><?php echo $service['description']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="container mt-5">
        <p>Aucun service trouvé.</p>
    </div>
<?php } ?>
</body>

</html>
