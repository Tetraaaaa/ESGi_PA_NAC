<?php
require_once 'include/connection_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['valide'])) {
        $id = $_POST['id'];
        $valide = $_POST['valide'];

        $stmt = $bdd->prepare("UPDATE ETAT SET valide = :valide WHERE id = :id");
        $stmt->bindParam(':valide', $valide, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['message' => 'État des lieux mis à jour avec succès.']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Erreur lors de la mise à jour de l\'état des lieux.']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Données manquantes.']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Requête invalide.']);
}
?>
