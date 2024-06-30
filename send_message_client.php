<?php
require_once 'include/connection_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $id_service = $_POST['id_service'];
    $id_location = isset($_POST['id_location']) ? $_POST['id_location'] : null;
    $id_logement = isset($_POST['id_logement']) ? $_POST['id_logement'] : null;
    $demande_user_id = $_SESSION['id'];

    // Récupérer l'id de l'utilisateur propriétaire du service
    $stmt = $bdd->prepare("SELECT id_USER FROM SERVICE WHERE id = :id_service");
    $stmt->execute(['id_service' => $id_service]);
    $id_user_recois = $stmt->fetchColumn();

    try {
        $stmt = $bdd->prepare("INSERT INTO MESSAGE (text, date_envoie, id_USER_ENVOIE, id_USER_RECOIS) VALUES (:message, NOW(), :demande_user_id, :id_user_recois)");
        $stmt->execute([
            'message' => $message,
            'demande_user_id' => $demande_user_id,
            'id_user_recois' => $id_user_recois
        ]);
        echo 'Message envoyé avec succès';
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo 'Error: ' . $e->getMessage();
    }
}
?>
