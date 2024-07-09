<?php
require_once 'include/connection_db.php';
ini_set('display_errors', 1);


if (!isset($_GET['id_service']) || !isset($_GET['logement_id'])) {
    echo '<p>Erreur : ID de service ou ID de logement manquant.</p>';
    exit;
}

$id_service = $_GET['id_service'];
$logement_id = $_GET['logement_id'];

$stmt = $bdd->prepare("SELECT S.type, S.description, U.nom, U.prenom, U.email 
                       FROM SERVICE S
                       JOIN USER U ON S.id_USER = U.id
                       WHERE S.id = :id_service");
$stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
$stmt->execute();
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo '<p>Aucun service trouvé.</p>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélectionner un Service</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <?php 
    require_once 'include/connection_db.php'; 
    session_start();
    require_once 'header.php'; 
    ?>
    <main class="container mt-4">
        <h2>Formuler une demande pour le service</h2>
        <div class="p-3 border bg-light">
            <h4>Détails du Service</h4>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($service['type']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($service['description']); ?></p>
            <h5>Informations sur l'utilisateur associé</h5>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($service['nom']); ?></p>
            <p><strong>Prénom:</strong> <?php echo htmlspecialchars($service['prenom']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($service['email']); ?></p>
        </div>

        <form action="selectionne_service_logement_verif.php" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="demande" class="form-label">Votre demande</label>
                <textarea class="form-control" id="demande" name="demande" rows="4" required></textarea>
            </div>
            <input type="hidden" name="id_service" value="<?php echo htmlspecialchars($id_service); ?>">
            <input type="hidden" name="id_logement" value="<?php echo htmlspecialchars($logement_id); ?>">
            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
        </form>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
