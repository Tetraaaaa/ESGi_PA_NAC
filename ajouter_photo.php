<?php
session_start();
require 'include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['logement_id'])) {
    $logement_id = $_GET['logement_id'];
    $photo = $_FILES['photo'];

    if ($photo['error'] == 0) {
        $destinationPath = 'icone/'; // Assurez-vous que ce dossier existe et est accessible en écriture
        $filename = uniqid() . '-' . basename($photo['name']);
        $destination = $destinationPath . $filename;

        if (move_uploaded_file($photo['tmp_name'], $destination)) {
            // Insertion de l'information de la photo dans la base de données
            $insertPhotoStmt = $bdd->prepare("INSERT INTO PHOTO_LOGEMENT (id_LOGEMENT, emplacement) VALUES (?, ?)");
            $insertPhotoStmt->execute([$logement_id, $destination]);

            echo "<p>Photo ajoutée avec succès.</p>";
            header('Refresh: 2; URL=modifier_logement.php?id=' . $logement_id);
            exit;
        } else {
            echo "<p>Erreur lors du téléchargement du fichier.</p>";
        }
    } else {
        echo "<p>Erreur lors du téléchargement du fichier.</p>";
    }
} else {
    echo "<p>Erreur : Accès non autorisé ou ID du logement manquant.</p>";
    exit;
}
?>
