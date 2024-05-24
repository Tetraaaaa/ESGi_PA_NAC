<?php
require_once 'include/connection_db.php';

if (isset($_GET['id'])) {
    $serviceId = $_GET['id'];

    $stmt = $bdd->prepare("SELECT S.id, S.type, S.description, U.nom, U.prenom, U.email 
                           FROM SERVICE S
                           JOIN USER U ON S.id_USER = U.id
                           WHERE S.id = :id");
    $stmt->bindParam(':id', $serviceId, PDO::PARAM_INT);
    $stmt->execute();

    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        echo '<h4>Détails du Service</h4>';
        echo '<p><strong>Type:</strong> ' . htmlspecialchars($service['type']) . '</p>';
        echo '<p><strong>Description:</strong> ' . htmlspecialchars($service['description']) . '</p>';
        echo '<h5>Informations sur l\'utilisateur associé</h5>';
        echo '<p><strong>Nom:</strong> ' . htmlspecialchars($service['nom']) . '</p>';
        echo '<p><strong>Prénom:</strong> ' . htmlspecialchars($service['prenom']) . '</p>';
        echo '<p><strong>Email:</strong> ' . htmlspecialchars($service['email']) . '</p>';
    } else {
        echo '<p>Aucun détail trouvé pour ce service.</p>';
    }
}
?>
