<?php
require_once 'include/connection_db.php';
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<p>Erreur : Aucun ID de logement fourni.</p>';
    header('Refresh: 2; URL=admin_logements.php');
    exit;
}

$logement_id = $_GET['id'];

try {
    $bdd->beginTransaction();

    // Suppression des entrées dépendantes dans les tables associées
    $tables = [
        'PHOTO_LOGEMENT' => 'id_LOGEMENT',
        'LOCATION' => 'id_LOGEMENT',
        'DATE_DISPO' => 'id_LOGEMENT',
        'DATE_RESERVE' => 'id_LOGEMENT',
        'INTERVENTION_SERVICE' => 'id_logement',
        'FAIT_APPELLE' => 'id_LOCATION',
        'SELECTIONNE' => 'id_LOGEMENT',
        'FACTURE' => 'id_logement',
        'ETAT' => 'id_LOCATION',
        'DATE_INTERVENTION' => 'id_LOGEMENT',
        'CARACTERISTIQUE_LOGEMENT' => 'id_LOGEMENT'
    ];

    foreach ($tables as $table => $column) {
        $stmt = $bdd->prepare("DELETE FROM $table WHERE $column = :logement_id");
        $stmt->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Suppression du logement
    $stmt = $bdd->prepare("DELETE FROM LOGEMENT WHERE id = :logement_id");
    $stmt->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
    $stmt->execute();

    $bdd->commit();
    echo '<p>Logement supprimé avec succès.</p>';
    header('Refresh: 2; URL=admin_logements.php');
    exit;
} catch (Exception $e) {
    $bdd->rollBack();
    echo '<p>Failed to delete logement: ' . $e->getMessage() . '</p>';
    header('Refresh: 2; URL=admin_logements.php');
    exit;
}
?>
