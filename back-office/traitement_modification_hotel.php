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


$id_hotel_a_modifier = $_POST['hotel_id']; // Récupérez l'ID de l'hôtel à partir du formulaire


$q = "SELECT * FROM hotel WHERE id=:id";
$req = $bdd->prepare($q);
$req->execute(['id' => $id_hotel_a_modifier]);
$hotel_a_modifier = $req->fetch();


if (!$hotel_a_modifier) {
    echo "Hôtel non trouvé.";
    exit;
}

// Récupérez les données du formulaire
$nom = $_POST['nom'];
$prix = $_POST['prix'];
$reduction = $_POST['reduction']; // Assurez-vous d'utiliser le bon nom de champ ici
$adresse = $_POST['adresse'];
$nb_place = $_POST['nb_place'];
$reouverture = $_POST['reouverture'];

// Préparez la requête de mise à jour
$q = "UPDATE hotel SET nom = :nom, prix = :prix, reduction = :reduction, addresse = :adresse, nb_place = :nb_place, reouverture = :reouverture WHERE id = :hotel_id";
$req = $bdd->prepare($q);

// Exécutez la requête avec les valeurs des champs du formulaire
$result = $req->execute([
    'nom' => $nom,
    'prix' => $prix,
    'reduction' => $reduction,
    'adresse' => $adresse,
    'nb_place' => $nb_place,
    'reouverture' => $reouverture,
    'hotel_id' => $id_hotel_a_modifier
]);

if ($result) {
    header('Location: service.php?message=Hôtel Modifié');
    exit();
} else {
    header("Location: service.php?message=Erreur lors de la mise à jour de l'hôtel : " . implode(' ', $req->errorInfo()));
}
?>
<script src="js/script.js"></script>
</body>
</html>
