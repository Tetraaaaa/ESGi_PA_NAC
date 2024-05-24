<?php
session_start();
require 'include/db.php';  // Assurez-vous que ce fichier contient les informations de connexion à votre base de données.

// Vérifier si l'ID du logement est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID du logement manquant.');
}
$id_logement = $_GET['id'];

try {
    // Début de la transaction
    $bdd->beginTransaction();

    // Suppression des données dans les tables associées
    $tables = ['DATE_DISPO', 'SELECTIONNE', 'DATE_INTERVENTION', 'PHOTO_LOGEMENT'];
    foreach ($tables as $table) {
        $stmt = $bdd->prepare("DELETE FROM $table WHERE id_LOGEMENT = :id_logement");
        $stmt->execute([':id_logement' => $id_logement]);
    }

    // Suppression dans la table LOGEMENT
    $stmt = $bdd->prepare("DELETE FROM LOGEMENT WHERE id = :id_logement");
    $stmt->execute([':id_logement' => $id_logement]);

    // Validation de la transaction
    $bdd->commit();
    echo "Suppression réussie.";

    // Redirection ou autre logique de post-suppression
    header('Location: vos_locations.php');
    exit;
} catch (Exception $e) {
    // Annulation de la transaction en cas d'erreur
    $bdd->rollBack();
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>
