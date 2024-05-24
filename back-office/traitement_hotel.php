<?php
if(
    !isset($_POST['nom'])
    || !isset($_POST['nb_place'])
    || !isset($_POST['prix'])
    || empty($_POST['nom'])
    || empty($_POST['nb_place'])
    || empty($_POST['prix'])
){
    $msg = 'Vous devez remplir tous les champs';
    header('location: add_pa.php?message=' . $msg);
    exit;
}

include('include/db.php');

$q = 'SELECT id FROM hotel WHERE nom = :nom';
$req = $bdd->prepare($q);
$req->execute([
    'nom' => $_POST['nom']
]);
$results = $req->fetchAll();

if (!empty($results)) {
    $message = 'Ce nom est déjà utilisé.';
    header('location: ../back-office/service.php?message=' . $message);
    exit;
}



$q = 'INSERT INTO hotel (nom,nb_place,prix,id_pa) VALUES (:nom,:nb_place,:prix,:id_pa)';
$req = $bdd->prepare($q);
$reponse = $req->execute([
    'nom' => $_POST['nom'],
    'nb_place' => $_POST['nb_place'],
    'prix' => $_POST['prix'],
    'id_pa' => $_GET['id_pa'],
]);

if (!$reponse){
    $message = 'Erreur lors de l\'inscription en base de données.';
    header('location: ../back-office/service.php?message=' . $message);
    exit;
}

$message = 'Hotel ajouter avec succès.';
header('location: ../back-office/service.php?message=' . $message);
exit;

?>