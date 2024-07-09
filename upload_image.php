<?php
require_once 'include/connection_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image']) || !isset($_POST['id_service']) || !isset($_POST['demande_user_id'])) {
        echo '<p>Erreur : Données manquantes.</p>';
        exit;
    }

    $image = $_FILES['image'];
    $id_service = $_POST['id_service'];
    $id_location = isset($_POST['id_location']) ? $_POST['id_location'] : null;
    $id_logement = isset($_POST['id_logement']) ? $_POST['id_logement'] : null;
    $demande_user_id = $_POST['demande_user_id'];
    $current_user_id = $_SESSION['id'];

  
    $uploadDir = 'chat-pic/';
    $uploadFile = $uploadDir . basename($image['name']);

 
    $fileInfo = pathinfo($uploadFile);
    $uploadFile = $uploadDir . $fileInfo['filename'] . '_' . time() . '.' . $fileInfo['extension'];


    if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
  
        list($width, $height) = getimagesize($uploadFile);


        $stmt = $bdd->prepare("INSERT INTO MESSAGE (id_USER_ENVOIE, id_USER_RECOIS, text, date_envoie, image) VALUES (:id_user_envoie, :id_user_recois, '', NOW(), NULL)");
        $stmt->bindParam(':id_user_envoie', $current_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_user_recois', $demande_user_id, PDO::PARAM_INT);
        $stmt->execute();
        $id_message = $bdd->lastInsertId();


        $stmt = $bdd->prepare("INSERT INTO PHOTO_CHAT (id_MESSAGE, emplacement, largeur, hauteur) VALUES (:id_message, :emplacement, :largeur, :hauteur)");
        $stmt->bindParam(':id_message', $id_message, PDO::PARAM_INT);
        $stmt->bindParam(':emplacement', $uploadFile, PDO::PARAM_STR);
        $stmt->bindParam(':largeur', $width, PDO::PARAM_INT);
        $stmt->bindParam(':hauteur', $height, PDO::PARAM_INT);
        $stmt->execute();

        echo '<p>Image téléchargée et message enregistré avec succès.</p>';
    } else {
        echo '<p>Erreur lors du téléchargement de l\'image.</p>';
    }
} else {
    echo '<p>Méthode de requête non supportée.</p>';
}
?>
