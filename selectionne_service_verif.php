<?php
require_once 'include/connection_db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['demande']) || !isset($_POST['id_service']) || !isset($_POST['id_location'])) {
        echo '<p>Erreur : Données manquantes.</p>';
        exit;
    }

    $demande = trim($_POST['demande']);
    $id_service = intval($_POST['id_service']);
    $id_location = intval($_POST['id_location']);

    // Validation des entrées
    if (empty($demande) || $id_service <= 0 || $id_location <= 0) {
        echo '<p>Erreur : Données invalides.</p>';
        exit;
    }

    // Préparation de la requête pour insérer les données dans la table FAIT_APPELLE
    $stmt = $bdd->prepare("INSERT INTO FAIT_APPELLE (id_LOCATION, id_SERVICE, status, demande) 
                           VALUES (:id_location, :id_service, 'demande envoyée', :demande)");
    $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
    $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $stmt->bindParam(':demande', $demande, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: voyages.php");
    } else {
        echo '<p>Erreur lors de l\'envoi de votre demande.</p>';
    }
} else {
    echo '<p>Méthode de requête non supportée.</p>';
}
?>
