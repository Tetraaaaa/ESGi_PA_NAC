<?php
session_start();
require_once 'include/connection_db.php'; 


if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}


$serviceId = isset($_GET['id']) ? $_GET['id'] : '';

if ($serviceId) {
    
    $query = $bdd->prepare("SELECT id, id_USER FROM SERVICE WHERE id = :id");
    $query->execute(['id' => $serviceId]);
    $service = $query->fetch(PDO::FETCH_ASSOC);

    
    if (!$service || $service['id_USER'] != $_SESSION['id']) {
        echo '<div class="alert alert-danger">Vous n\'êtes pas autorisé à supprimer ce service.</div>';
        exit; 
    }

    
    try {
        $bdd->beginTransaction();

        
        $deleteCalendrier = $bdd->prepare("DELETE FROM CALENDRIER WHERE id_service = :id_service");
        $deleteCalendrier->execute(['id_service' => $serviceId]);

        
        $deleteService = $bdd->prepare("DELETE FROM SERVICE WHERE id = :id");
        $deleteService->execute(['id' => $serviceId]);

        $bdd->commit();

        
        header('Location: mes_services.php?message=ServiceDeleted');
        exit;
    } catch (PDOException $e) {
        $bdd->rollBack();
        echo "Erreur lors de la suppression : " . $e->getMessage();
        exit;
    }
} else {
    
    header('Location: list_services.php?error=NoServiceIdProvided');
    exit;
}
?>
