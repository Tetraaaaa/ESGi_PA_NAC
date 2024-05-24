<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Points D'Arrets</title>
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

include ("include/db.php");
echo '<h1>Ajouter Points D\'arret</h1>
';

if(isset($_GET['message'])&&!empty($_GET['message'])){
    echo '<div class="alert alert-danger" role="alert">
                '.$_GET['message'].'
            </div>';
}
?>
<form action="traitement_hotel_pa.php?id_pa=<?echo($_GET['id_pa'])?>" method="POST">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom de l'hôtel</label>
        <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="mb-3">
        <label for="nb_place" class="form-label">Nombre de places</label>
        <input type="number" class="form-control" id="nb_place" name="nb_place" required>
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="double" class="form-control" id="prix" name="prix" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
