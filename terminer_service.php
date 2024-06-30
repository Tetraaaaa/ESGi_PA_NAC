<?php
require_once 'include/connection_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_service']) || (!isset($_POST['id_location']) && !isset($_POST['id_logement']))) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit;
    }

    $id_service = $_POST['id_service'];
    $id_location = isset($_POST['id_location']) ? $_POST['id_location'] : null;
    $id_logement = isset($_POST['id_logement']) ? $_POST['id_logement'] : null;

    try {
        if ($id_location) {
            $stmt = $bdd->prepare("
                UPDATE FAIT_APPELLE 
                SET status = 'Termine' 
                WHERE id_location = :id_location AND id_service = :id_service
            ");
            $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
            $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        } else {
            $stmt = $bdd->prepare("
                UPDATE SELECTIONNE 
                SET status = 'Termine' 
                WHERE id_logement = :id_logement AND id_service = :id_service
            ");
            $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
            $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        }

        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
