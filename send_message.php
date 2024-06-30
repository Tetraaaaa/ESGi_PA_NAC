<?php
require_once 'include/connection_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_service']) || (!isset($_POST['id_location']) && !isset($_POST['id_logement'])) || !isset($_POST['demande_user_id']) || !isset($_POST['message'])) {
        echo 'Données manquantes.';
        exit;
    }

    $id_service = $_POST['id_service'];
    $id_location = isset($_POST['id_location']) ? $_POST['id_location'] : null;
    $id_logement = isset($_POST['id_logement']) ? $_POST['id_logement'] : null;
    $demande_user_id = $_POST['demande_user_id'];
    $message = trim($_POST['message']);
    $current_user_id = $_SESSION['id'];

    try {
        $stmt = $bdd->prepare("INSERT INTO MESSAGE (id_USER_ENVOIE, id_USER_RECOIS, text, date_envoie, image) VALUES (:id_user_envoie, :id_user_recois, :text, NOW(), NULL)");
        $stmt->bindParam(':id_user_envoie', $current_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_user_recois', $demande_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':text', $message, PDO::PARAM_STR);
        $stmt->execute();
        echo 'Message envoyé';
    } catch (Exception $e) {
        echo 'Erreur lors de l\'envoi du message : ' . htmlspecialchars($e->getMessage());
    }
} else {
    echo 'Méthode de requête non supportée.';
}
?>
