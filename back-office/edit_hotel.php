<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Modifier l'Hôtel</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet_details.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../Js/script.js" async></script>

</head>

<?php
include 'header.php';
include 'verif.php';
?>
<body class="container text-center">
<?php
include ("include/db.php");


$id_hotel_a_modifier = $_GET['user_id'];


$q = "SELECT * FROM hotel WHERE id=:id";
$req = $bdd->prepare($q);
$req->execute(['id' => $id_hotel_a_modifier]);
$hotel_a_modifier = $req->fetch();


if (!$hotel_a_modifier) {
    echo "Hôtel non trouvé.";
    exit;
}


echo '<h1>Modifier l\'Hôtel</h1>
<form action="traitement_modification_hotel.php" method="POST">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom de l\'hôtel</label>
        <input type="text" class="form-control" id="nom" name="nom" value="' . htmlspecialchars($hotel_a_modifier['nom']) . '" required>
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="number" class="form-control" id="prix" name="prix" value="' . htmlspecialchars($hotel_a_modifier['prix']) . '" required>
    </div>
    <div class="mb-3">
    <label for="reduction" class="form-label">Reduction</label>
    <input type="number" class="form-control" id="reduction" name="reduction" value="'. htmlspecialchars($hotel_a_modifier['reduction']).'" required>
    </div>

    <div class="mb-3">
        <label for="adresse" class="form-label">adresse</label>
        <input type="text" class="form-control" id="adresse" name="adresse" value="' . htmlspecialchars($hotel_a_modifier['addresse']) . '" required>
    </div>
    <div class="mb-3">
        <label for="nb_place" class="form-label">Nombre de places</label>
        <input type="number" class="form-control" id="nb_place" name="nb_place" value="' . htmlspecialchars($hotel_a_modifier['nb_place']) . '" required>
    </div>
    <div class="mb-3">
        <label for="reouverture" class="form-label">Date de réouverture (Mettre date inférieur à aujourd\'hui pour ouvrir)</label>
        <input type="date" class="form-control" id="reouverture" name="reouverture" value="' . htmlspecialchars($hotel_a_modifier['reouverture']) . '">
    </div>

    <input type="hidden" name="hotel_id" value="' . htmlspecialchars($hotel_a_modifier['id']) . '">
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
</form>';
?>
<script src="js/script.js"></script>
</body>
</html>
