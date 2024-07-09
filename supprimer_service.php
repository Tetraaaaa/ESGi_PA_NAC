<?php
require_once 'include/connection_db.php';

if (isset($_GET['id'])) {
    $id_service = $_GET['id'];

    // Begin transaction
    $bdd->beginTransaction();
    try {
        // Désactiver les contraintes de clé étrangère
        $bdd->exec('SET FOREIGN_KEY_CHECKS = 0');

        // Supprimer les interventions liées au service
        $stmtInterventions = $bdd->prepare("DELETE FROM INTERVENTION_SERVICE WHERE id_service = ?");
        $stmtInterventions->execute([$id_service]);

        // Supprimer les factures liées au service
        $stmtFactures = $bdd->prepare("DELETE FROM FACTURE WHERE id_service = ?");
        $stmtFactures->execute([$id_service]);

        // Supprimer le service
        $stmtService = $bdd->prepare("DELETE FROM SERVICE WHERE id = ?");
        $stmtService->execute([$id_service]);

        // Réactiver les contraintes de clé étrangère
        $bdd->exec('SET FOREIGN_KEY_CHECKS = 1');

        // Commit transaction
        $bdd->commit();

        // Rediriger après suppression
        header("Location: admin_services.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction en cas d'erreur
        $bdd->rollBack();
        echo "Failed to delete service: " . $e->getMessage();
    }
} else {
    echo "No service ID provided.";
}
?>
