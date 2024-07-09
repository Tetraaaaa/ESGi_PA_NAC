<?php
require_once 'include/connection_db.php';
session_start();

if (!isset($_GET['id_service']) || (!isset($_GET['id_location']) && !isset($_GET['id_logement']))) {
    http_response_code(400);
    echo 'Invalid request';
    exit;
}

$id_service = $_GET['id_service'];
$id_location = isset($_GET['id_location']) ? $_GET['id_location'] : null;
$id_logement = isset($_GET['id_logement']) ? $_GET['id_logement'] : null;
$demande_user_id = $_SESSION['id'];


$stmt = $bdd->prepare("SELECT id_USER FROM SERVICE WHERE id = :id_service");
$stmt->execute(['id_service' => $id_service]);
$id_user_recois = $stmt->fetchColumn();

try {
    $stmt = $bdd->prepare("
        SELECT text, date_envoie, id_USER_ENVOIE
        FROM MESSAGE
        WHERE (id_USER_ENVOIE = :demande_user_id AND id_USER_RECOIS = :id_user_recois)
        OR (id_USER_ENVOIE = :id_user_recois AND id_USER_RECOIS = :demande_user_id)
        ORDER BY date_envoie ASC
    ");
    $stmt->execute([
        'demande_user_id' => $demande_user_id,
        'id_user_recois' => $id_user_recois
    ]);
    
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($messages as $message) {
        $message_class = $message['id_USER_ENVOIE'] == $demande_user_id ? 'message-sent' : 'message-received';
        echo '<div class="' . $message_class . '">' . htmlspecialchars($message['text']) . '</div>';
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo 'Error: ' . $e->getMessage();
}
?>
