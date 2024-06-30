<?php
require_once 'include/connection_db.php';
session_start();

$id_location = $_POST['id_location'];
$id_locataire = $_POST['id_locataire'];
$id_proprietaire = $_POST['id_proprietaire'];
$message = $_POST['message'];

// Récupérer l'ID de l'utilisateur connecté
$id_user_envoie = $_SESSION['id'];

// Déterminer le destinataire
$id_user_recois = ($id_user_envoie == $id_locataire) ? $id_proprietaire : $id_locataire;

// Insérer le message dans la base de données
$stmt = $bdd->prepare("
    INSERT INTO MESSAGE (id_USER_ENVOIE, id_USER_RECOIS, text, date_envoie) 
    VALUES (:id_user_envoie, :id_user_recois, :text, NOW())
");
$stmt->execute([
    ':id_user_envoie' => $id_user_envoie,
    ':id_user_recois' => $id_user_recois,
    ':text' => $message
]);
?>
