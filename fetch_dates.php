<?php
session_start();
require 'include/db.php';

header('Content-Type: application/json');

if (isset($_GET['id_logement'])) {
    $idLogement = $_GET['id_logement'];
    $stmt = $bdd->prepare("SELECT date FROM DATE_DISPO WHERE id_LOGEMENT = ?");
    $stmt->execute([$idLogement]);
    $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['status' => 'success', 'dates' => $dates]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de logement non fourni']);
}
?>
