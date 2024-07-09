<?php
session_start();
require 'include/db.php';

if (isset($_GET['photo_id']) && isset($_GET['logement_id'])) {
    $photo_id = $_GET['photo_id'];
    $logement_id = $_GET['logement_id'];

  s
    $deleteStmt = $bdd->prepare("DELETE FROM PHOTO_LOGEMENT WHERE id = ? AND id_LOGEMENT = ?");
    $deleteStmt->execute([$photo_id, $logement_id]);

    

    header('Location: modifier_logement.php?id=' . $logement_id);
    exit;
} else {
    echo 'Erreur : Aucun identifiant de photo ou de logement fourni.';
    exit;
}
?>
