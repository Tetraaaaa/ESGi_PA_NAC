<?php
include 'include/db.php';  // Assurez-vous que le chemin d'accès est correct

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $newStatus = 4;  // Définir le nouveau status

    // Préparation de la requête de mise à jour
    $stmt = $bdd->prepare("UPDATE USER SET status = :newStatus WHERE id = :id");
    $result = $stmt->execute([':newStatus' => $newStatus, ':id' => $userId]);

    if ($result) {
        echo "Status mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du status.";
    }
} else {
    echo "Aucun ID fourni.";
}

// Vous pourriez vouloir rediriger l'utilisateur ou faire d'autres actions ici
?>
