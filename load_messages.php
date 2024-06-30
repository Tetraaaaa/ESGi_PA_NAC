<?php
require_once 'include/connection_db.php';
session_start();

$id_service = $_GET['id_service'];
$id_location = $_GET['id_location'];
$id_logement = $_GET['id_logement'];
$demande_user_id = $_GET['demande_user_id'];
$current_user_id = $_SESSION['id'];

// Requête pour récupérer les messages texte
$stmt = $bdd->prepare("
    SELECT MESSAGE.*, USER.nom, USER.prenom 
    FROM MESSAGE 
    JOIN USER ON MESSAGE.id_USER_ENVOIE = USER.id 
    WHERE (id_USER_ENVOIE = :current_user_id AND id_USER_RECOIS = :demande_user_id)
       OR (id_USER_ENVOIE = :demande_user_id AND id_USER_RECOIS = :current_user_id)
    ORDER BY MESSAGE.date_envoie ASC
");
$stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
$stmt->bindParam(':demande_user_id', $demande_user_id, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer les messages avec photos
$stmt = $bdd->prepare("
    SELECT MESSAGE.*, PHOTO_CHAT.emplacement, PHOTO_CHAT.largeur, PHOTO_CHAT.hauteur, USER.nom, USER.prenom 
    FROM MESSAGE 
    JOIN PHOTO_CHAT ON MESSAGE.id = PHOTO_CHAT.id_MESSAGE
    JOIN USER ON MESSAGE.id_USER_ENVOIE = USER.id 
    WHERE (id_USER_ENVOIE = :current_user_id AND id_USER_RECOIS = :demande_user_id)
       OR (id_USER_ENVOIE = :demande_user_id AND id_USER_RECOIS = :current_user_id)
    ORDER BY MESSAGE.date_envoie ASC
");
$stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
$stmt->bindParam(':demande_user_id', $demande_user_id, PDO::PARAM_INT);
$stmt->execute();
$photo_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Afficher les messages texte
foreach ($messages as $message) {
    $alignment = $message['id_USER_ENVOIE'] == $current_user_id ? 'message-sent' : 'message-received';
    echo '<div class="' . $alignment . '">';
    echo '<strong>' . htmlspecialchars($message['nom'] . ' ' . $message['prenom']) . ':</strong> ';
    echo htmlspecialchars($message['text']);
    echo '</div>';
}

// Afficher les messages avec photos
foreach ($photo_messages as $message) {
    $alignment = $message['id_USER_ENVOIE'] == $current_user_id ? 'message-sent' : 'message-received';
    echo '<div class="' . $alignment . '">';
    echo '<strong>' . htmlspecialchars($message['nom'] . ' ' . $message['prenom']) . ':</strong> ';
    echo '<img src="' . htmlspecialchars($message['emplacement']) . '" alt="Image" style="max-width: 100%; cursor: pointer;" onclick="showImageModal(\'' . htmlspecialchars($message['emplacement']) . '\')">';
    echo '</div>';
}
?>
