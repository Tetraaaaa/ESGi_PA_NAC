<?php
session_start();
require 'include/db.php';

header('Content-Type: application/json'); 

if (isset($_POST['date'], $_POST['id_logement'])) {
    $date = $_POST['date'];
    $idLogement = $_POST['id_logement'];

    try {
        $stmt = $bdd->prepare("DELETE FROM DATE_DISPO WHERE date = ? AND id_LOGEMENT = ?");
        $stmt->execute([$date, $idLogement]);

        echo json_encode(['status' => 'success', 'message' => 'Date supprimée avec succès']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données nécessaires non fournies']);
}
exit;
?>
