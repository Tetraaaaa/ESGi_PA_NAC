<?php
require_once 'include/connection_db.php';

ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['demande']) || !isset($_POST['id_service']) || !isset($_POST['id_logement'])) {
        echo '<p>Erreur : Données manquantes.</p>';
        exit;
    }

    $demande = trim($_POST['demande']);
    $id_service = intval($_POST['id_service']);
    $id_logement = intval($_POST['id_logement']);

    // Validation des entrées
    if (empty($demande) || $id_service <= 0 || $id_logement <= 0) {
        echo '<p>Erreur : Données invalides.</p>';
        exit;
    }

    // Préparation de la requête pour insérer les données dans la table SELECTIONNE
    $stmt = $bdd->prepare("INSERT INTO SELECTIONNE (id_LOGEMENT, id_SERVICE, status, demande) 
                           VALUES (:id_logement, :id_service, 'demande envoyée', :demande)");
    $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
    $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $stmt->bindParam(':demande', $demande, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: vos_locations.php");
        exit;
    } else {
        echo '<p>Erreur lors de l\'envoi de votre demande.</p>';
    }
} else {
    echo '<p>Méthode de requête non supportée.</p>';
}
?>
