<?php
session_start();
require 'include/db.php'; 
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID du logement manquant.');
}
$id_logement = $_GET['id'];

try {
   
    $bdd->beginTransaction();

    $tables = ['DATE_DISPO', 'SELECTIONNE', 'DATE_INTERVENTION', 'PHOTO_LOGEMENT'];
    foreach ($tables as $table) {
        $stmt = $bdd->prepare("DELETE FROM $table WHERE id_LOGEMENT = :id_logement");
        $stmt->execute([':id_logement' => $id_logement]);
    }


    $stmt = $bdd->prepare("DELETE FROM LOGEMENT WHERE id = :id_logement");
    $stmt->execute([':id_logement' => $id_logement]);


    $bdd->commit();
    echo "Suppression réussie.";


    header('Location: vos_locations.php');
    exit;
} catch (Exception $e) {

    $bdd->rollBack();
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>
