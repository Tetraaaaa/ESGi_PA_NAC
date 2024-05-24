<?php
session_start();
require_once 'include/connection_db.php'; // Connexion à la BDD

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupération de l'ID du service depuis l'URL
$serviceId = isset($_GET['id']) ? $_GET['id'] : '';

if ($serviceId) {
    // Récupération de l'ID de l'utilisateur qui possède le service
    $query = $bdd->prepare("SELECT id, id_USER FROM SERVICE WHERE id = :id");
    $query->execute(['id' => $serviceId]);
    $service = $query->fetch(PDO::FETCH_ASSOC);

    // Si le service n'existe pas ou si l'utilisateur actuel n'est pas le propriétaire
    if (!$service || $service['id_USER'] != $_SESSION['id']) {
        echo '<div class="alert alert-danger">Vous n\'êtes pas autorisé à supprimer ce service.</div>';
        exit; // Arrêt du script
    }

    // Suppression du service et des dates associées
    try {
        $bdd->beginTransaction();

        // Supprimer les dates du calendrier associées au service
        $deleteCalendrier = $bdd->prepare("DELETE FROM CALENDRIER WHERE id_service = :id_service");
        $deleteCalendrier->execute(['id_service' => $serviceId]);

        // Supprimer le service
        $deleteService = $bdd->prepare("DELETE FROM SERVICE WHERE id = :id");
        $deleteService->execute(['id' => $serviceId]);

        $bdd->commit();

        // Rediriger vers la page de liste des services avec confirmation
        header('Location: mes_services.php?message=ServiceDeleted');
        exit;
    } catch (PDOException $e) {
        $bdd->rollBack();
        echo "Erreur lors de la suppression : " . $e->getMessage();
        exit;
    }
} else {
    // Si aucun ID n'est fourni, rediriger vers la liste des services
    header('Location: list_services.php?error=NoServiceIdProvided');
    exit;
}
?>
