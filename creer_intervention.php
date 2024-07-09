<?php
session_start();
require_once 'include/connection_db.php';

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

$id_service = $_GET['id_service'] ?? null;
$id_location = $_GET['id_location'] ?? null;
$id_logement = $_GET['id_logement'] ?? null;
$user_id = $_SESSION['id'];

if (!$id_service || (!$id_location && !$id_logement)) {
    header('Location: index.php');
    exit;
}

// Vérifier que l'utilisateur possède bien le service
$stmtService = $bdd->prepare("SELECT id_USER FROM SERVICE WHERE id = :id_service");
$stmtService->bindParam(':id_service', $id_service, PDO::PARAM_INT);
$stmtService->execute();
$service = $stmtService->fetch(PDO::FETCH_ASSOC);

if (!$service || $service['id_USER'] != $user_id) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Intervention</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/crer_intervention.css">
</head>
<body>
    <?php 
    require_once 'header.php'; 
    ?>
    <main class="container mt-4">
        <h2>Créer une Intervention</h2>
        <div class="form-container">
            <form action="soumettre_intervention.php" method="post">
                <input type="hidden" name="id_service" value="<?php echo htmlspecialchars($id_service); ?>">
                <input type="hidden" name="id_location" value="<?php echo htmlspecialchars($id_location); ?>">
                <input type="hidden" name="id_logement" value="<?php echo htmlspecialchars($id_logement); ?>">
                <div class="mb-3">
                    <label for="entreprise" class="form-label">Entreprise</label>
                    <input type="text" class="form-control" id="entreprise" name="entreprise" required>
                </div>
                <div class="mb-3">
                    <label for="nature_intervention" class="form-label">Nature de l'intervention</label>
                    <input type="text" class="form-control" id="nature_intervention" name="nature_intervention" required>
                </div>
                <div class="mb-3">
                    <label for="debut_intervention" class="form-label">Date et heure du début de l'intervention</label>
                    <input type="datetime-local" class="form-control" id="debut_intervention" name="debut_intervention" required>
                </div>
                <div class="mb-3">
                    <label for="description_intervention" class="form-label">Description de l'intervention</label>
                    <textarea class="form-control" id="description_intervention" name="description_intervention" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="problemes_rencontres" class="form-label">Problèmes rencontrés</label>
                    <textarea class="form-control" id="problemes_rencontres" name="problemes_rencontres" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="fin_intervention" class="form-label">Date et heure de la fin de l'intervention</label>
                    <input type="datetime-local" class="form-control" id="fin_intervention" name="fin_intervention" required>
                </div>
                <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>
        </div>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
