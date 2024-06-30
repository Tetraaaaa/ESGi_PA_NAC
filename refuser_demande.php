<?php
require_once 'include/connection_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_location']) || !isset($_POST['id_service']) || !isset($_POST['table'])) {
        echo '<p>Erreur : Données manquantes.</p>';
        exit;
    }

    $id_location = $_POST['id_location'];
    $id_service = $_POST['id_service'];
    $table = $_POST['table'];
    $new_status = 'REFUSE'; // Statut pour "Refuser"

    if ($table === 'FAIT_APPELLE') {
        $stmt = $bdd->prepare("UPDATE FAIT_APPELLE SET status = :new_status WHERE id_location = :id_location AND id_service = :id_service");
        $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
        $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    } else {
        $id_logement = $id_location; // Utilisation de la même valeur pour id_logement
        $stmt = $bdd->prepare("UPDATE SELECTIONNE SET status = :new_status WHERE id_logement = :id_logement AND id_service = :id_service");
        $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
        $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    }

    $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header('Location: mes_demandes.php'); // Redirection après la mise à jour
        exit;
    } else {
        $error = $stmt->errorInfo();
        echo '<p>Erreur lors de la mise à jour du statut: ' . htmlspecialchars($error[2]) . '</p>';
    }
} else {
    echo '<p>Méthode de requête non supportée.</p>';
}
?>
