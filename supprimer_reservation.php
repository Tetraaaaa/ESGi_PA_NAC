<?php
require_once 'include/connection_db.php';

if (isset($_GET['id'])) {
    $id_reservation = $_GET['id'];

    // Begin transaction
    $bdd->beginTransaction();
    try {
        // Désactiver les contraintes de clé étrangère
        $bdd->exec('SET FOREIGN_KEY_CHECKS = 0');

        // Supprimer les états des lieux liés à la réservation
        $stmtEtat = $bdd->prepare("DELETE FROM ETAT WHERE id_LOCATION = ?");
        $stmtEtat->execute([$id_reservation]);

        // Supprimer la réservation
        $stmtReservation = $bdd->prepare("DELETE FROM LOCATION WHERE id = ?");
        $stmtReservation->execute([$id_reservation]);

        // Réactiver les contraintes de clé étrangère
        $bdd->exec('SET FOREIGN_KEY_CHECKS = 1');

        // Commit transaction
        $bdd->commit();

        // Rediriger après suppression
        header("Location: admin_reservations.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction en cas d'erreur
        $bdd->rollBack();
        echo "Failed to delete reservation: " . $e->getMessage();
    }
} else {
    echo "No reservation ID provided.";
}
?>
