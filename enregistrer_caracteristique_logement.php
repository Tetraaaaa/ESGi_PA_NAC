<?php
session_start();
include 'include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les autres données du logement depuis le formulaire
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
    // Ajoutez les autres champs nécessaires ici

    // Insérer le logement dans la table LOGEMENT
    try {
        $queryLogement = $bdd->prepare("INSERT INTO LOGEMENT (nom, adresse) VALUES (:nom, :adresse)");
        $queryLogement->execute([
            'nom' => $nom,
            'adresse' => $adresse
            // Ajoutez les autres champs nécessaires ici
        ]);

        // Récupérer l'ID du logement inséré
        $idLogement = $bdd->lastInsertId();

        // Récupérer les caractéristiques sélectionnées
        $caracteristiques = isset($_POST['caracteristiques']) ? $_POST['caracteristiques'] : [];

        // Insérer les caractéristiques dans la table de liaison LOGEMENT_CARACTERISTIQUE
        foreach ($caracteristiques as $idCaracteristique) {
            $query = $bdd->prepare("INSERT INTO LOGEMENT_CARACTERISTIQUE (id_LOGEMENT, id_CARACTERISTIQUE) VALUES (:id_LOGEMENT, :id_CARACTERISTIQUE)");
            $query->execute([
                'id_LOGEMENT' => $idLogement,
                'id_CARACTERISTIQUE' => $idCaracteristique
            ]);
        }

        // Redirection ou message de confirmation
        header('Location: confirmation.php');
        exit;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
} else {
    die('Méthode de requête non valide.');
}
?>
