<?php
session_start();
require 'include/db.php';

header('Content-Type: application/json');

if (isset($_POST['date'], $_POST['id_logement'])) {
    $date = $_POST['date'];
    $idLogement = $_POST['id_logement'];
    $id = mt_rand(1, 2147483647);  

    try {
        $stmt = $bdd->prepare("INSERT INTO DATE_DISPO (id, date, id_LOGEMENT) VALUES (?, ?, ?)");
        $stmt->execute([$id, $date, $idLogement]);

        echo json_encode(['status' => 'success', 'message' => 'Date ajoutée avec succès']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données nécessaires non fournies']);
}
?>
