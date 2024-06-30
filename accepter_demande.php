<?php
require_once 'include/connection_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_location']) || !isset($_POST['id_service']) || !isset($_POST['table'])) {
        echo '<p>Erreur : Données manquantes.</p>';
        exit;
    }

    $id_location = $_POST['id_location'];
    $id_service = $_POST['id_service'];
    $table = $_POST['table'];
    $new_status = 'En cours'; // Statut pour "Accepter"
    $current_user_id = $_SESSION['id'];

    try {
        $bdd->beginTransaction();

        if ($table === 'FAIT_APPELLE') {
            // Mettre à jour le statut dans la table FAIT_APPELLE
            $stmt = $bdd->prepare("UPDATE FAIT_APPELLE SET status = :new_status WHERE id_location = :id_location AND id_service = :id_service");
            $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
            $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
            $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
            $stmt->execute();

            // Récupérer id_USER à partir de la table LOCATION
            $stmt = $bdd->prepare("SELECT id_USER FROM LOCATION WHERE id = :id_location");
            $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
            $stmt->execute();
            $id_user_envoie = $stmt->fetchColumn();

            // Récupérer le texte de la demande
            $stmt = $bdd->prepare("SELECT demande FROM FAIT_APPELLE WHERE id_location = :id_location AND id_service = :id_service");
            $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
            $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
            $stmt->execute();
            $demande_text = $stmt->fetchColumn();
        } else {
            $id_logement = $id_location; // Utilisation de la même valeur pour id_logement

            // Mettre à jour le statut dans la table SELECTIONNE
            $stmt = $bdd->prepare("UPDATE SELECTIONNE SET status = :new_status WHERE id_logement = :id_logement AND id_service = :id_service");
            $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
            $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
            $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
            $stmt->execute();

            // Récupérer id_USER à partir de la table LOGEMENT
            $stmt = $bdd->prepare("SELECT id_USER FROM LOGEMENT WHERE id = :id_logement");
            $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
            $stmt->execute();
            $id_user_envoie = $stmt->fetchColumn();

            // Récupérer le texte de la demande
            $stmt = $bdd->prepare("SELECT demande FROM SELECTIONNE WHERE id_logement = :id_logement AND id_service = :id_service");
            $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
            $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
            $stmt->execute();
            $demande_text = $stmt->fetchColumn();
        }

        // Afficher les informations avant l'insertion
        echo "<p>id_user_envoie: $id_user_envoie</p>";
        echo "<p>id_user_recois: $current_user_id</p>";
        echo "<p>text: $demande_text</p>";

        // Insérer les informations dans la table MESSAGE
        $stmt = $bdd->prepare("INSERT INTO MESSAGE (id_USER_ENVOIE, id_USER_RECOIS, text, date_envoie, image) VALUES (:id_user_envoie, :id_user_recois, :text, NOW(), NULL)");
        $stmt->bindParam(':id_user_envoie', $id_user_envoie, PDO::PARAM_INT);
        $stmt->bindParam(':id_user_recois', $current_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':text', $demande_text, PDO::PARAM_STR);
        $stmt->execute();

        $bdd->commit();

        // Redirection après la mise à jour (temporairement commentée pour la vérification)
        // header('Location: mes_demandes.php');
        exit;
    } catch (Exception $e) {
        $bdd->rollBack();
        echo '<p>Erreur lors de la mise à jour du statut : ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
} else {
    echo '<p>Méthode de requête non supportée.</p>';
}
?>
