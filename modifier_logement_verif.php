<?php
session_start();
require 'include/db.php';
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $logement_id = $_GET['id'];

    // Récupération et nettoyage des données du formulaire
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $prix = $_POST['prix'];
    $code_postal = $_POST['code_postal'];
    $pays = $_POST['pays'];
    $capacite_location = $_POST['capacite_location'];
    $dates_disponibles = $_POST['dates_disponibles'];

    // Mise à jour des informations du logement
    $updateStmt = $bdd->prepare("UPDATE LOGEMENT SET nom = ?, description = ?, adresse = ?, ville = ?, prix = ?, code_postal = ?, pays = ?, capacite_location = ? WHERE id = ?");
    $updateStmt->execute([$nom, $description, $adresse, $ville, $prix, $code_postal, $pays, $capacite_location, $logement_id]);

    // Suppression des anciennes dates disponibles
    $deleteDateStmt = $bdd->prepare("DELETE FROM DATE_DISPO WHERE id_LOGEMENT = ?");
    $deleteDateStmt->execute([$logement_id]);

    // Ajout des nouvelles dates disponibles
    if (!empty($dates_disponibles)) {
        $dates = explode(',', $dates_disponibles);
        foreach ($dates as $date) {
            $id = mt_rand(1, 2147483647);
            $date = trim($date);
            $insertDateStmt = $bdd->prepare("INSERT INTO DATE_DISPO (id, date, id_LOGEMENT) VALUES (?, ?, ?)");
            $insertDateStmt->execute([$id, $date, $logement_id]);
        }
    }

    echo "<p>Le logement a été mis à jour avec succès. Vous allez être redirigé.</p>";
    header('Refresh: 2; URL=modifier_logement.php?id=' . $logement_id);
    exit;
} else {
    echo "<p>Erreur lors de l'accès à cette page directement ou ID manquant.</p>";
    header('Refresh: 2; URL=index.php');
    exit;
}
?>
