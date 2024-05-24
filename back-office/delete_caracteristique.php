<?php
session_start();
include 'include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        try {
            // Commencer une transaction
            $bdd->beginTransaction();

            // Récupérer le chemin de l'icône associée
            $queryIcone = $bdd->prepare("SELECT emplacement FROM ICONE WHERE id_CARACTERISTIQUE = :id");
            $queryIcone->execute(['id' => $id]);
            $icone = $queryIcone->fetch();

            // Si une icône est trouvée, supprimez le fichier du système de fichiers
            if ($icone && file_exists($icone['emplacement'])) {
                unlink($icone['emplacement']);
            }

            // Supprimer l'entrée de la table ICONE
            $queryIcone = $bdd->prepare("DELETE FROM ICONE WHERE id_CARACTERISTIQUE = :id");
            $queryIcone->execute(['id' => $id]);

            // Supprimer l'entrée de la table CARACTERISTIQUE
            $queryCaracteristique = $bdd->prepare("DELETE FROM CARACTERISTIQUE WHERE id = :id");
            $queryCaracteristique->execute(['id' => $id]);

            // Valider la transaction
            $bdd->commit();

            // Redirection vers la page des caractéristiques
            header('Location: caracteristique.php');
            exit;
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            $bdd->rollBack();
            die('Erreur : ' . $e->getMessage());
        }
    } else {
        die('ID de caractéristique non valide.');
    }
} else {
    die('Méthode de requête non valide.');
}
