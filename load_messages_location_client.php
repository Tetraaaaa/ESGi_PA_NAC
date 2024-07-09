<?php
require_once 'include/connection_db.php';
session_start();

$id_location = $_GET['id_location'];
$id_locataire = $_GET['id_locataire'];
$id_proprietaire = $_GET['id_proprietaire'];


$stmt = $bdd->prepare("
    SELECT MESSAGE.*, USER.nom, USER.prenom 
    FROM MESSAGE 
    JOIN USER ON MESSAGE.id_USER_ENVOIE = USER.id
    WHERE (id_USER_ENVOIE = :id_locataire AND id_USER_RECOIS = :id_proprietaire) 
    OR (id_USER_ENVOIE = :id_proprietaire AND id_USER_RECOIS = :id_locataire)
    ORDER BY date_envoie ASC
");
$stmt->execute([':id_locataire' => $id_locataire, ':id_proprietaire' => $id_proprietaire]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($messages) {
    foreach ($messages as $message) {
        if ($message['id_USER_ENVOIE'] == $_SESSION['id']) {
            echo '<div class="message-sent">';
            echo '<strong>Moi:</strong><br>';
        } else {
            echo '<div class="message-received">';
            echo '<strong>' . htmlspecialchars($message['nom']) . ' ' . htmlspecialchars($message['prenom']) . ':</strong><br>';
        }
        echo htmlspecialchars($message['text']);
        echo '<br><small>' . date('d-m-Y H:i', strtotime($message['date_envoie'])) . '</small>';
        echo '</div>';
    }
} else {
    echo '<p>Aucun message trouvé.</p>';
}
?>
