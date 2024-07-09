<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Logements</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <?php 
    require_once 'include/connection_db.php'; 
    session_start();
    require_once 'header.php'; 
    
    if (!isset($_SESSION['id'])) {
        echo '<p>Veuillez vous connecter pour voir vos appels.</p>';
        exit;
    }

    $userId = $_SESSION['id'];
    
    $stmt = $bdd->prepare("SELECT id FROM SERVICE WHERE id_USER = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    if (empty($services)) {
        echo '<p>Aucun service trouvé pour cet utilisateur.</p>';
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($services), '?'));
    $stmt = $bdd->prepare("
        SELECT FAIT_APPELLE.*, SERVICE.type, SERVICE.description, USER.nom, USER.prenom, FAIT_APPELLE.id_location, FAIT_APPELLE.id_service
        FROM FAIT_APPELLE
        JOIN SERVICE ON FAIT_APPELLE.id_service = SERVICE.id
        JOIN LOCATION ON FAIT_APPELLE.id_location = LOCATION.id
        JOIN USER ON LOCATION.id_USER = USER.id
        WHERE FAIT_APPELLE.id_service IN ($placeholders)
    ");
    $stmt->execute($services);
    $appels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $bdd->prepare("
        SELECT SELECTIONNE.*, SERVICE.type, SERVICE.description, USER.nom, USER.prenom, SELECTIONNE.id_logement, SELECTIONNE.id_SERVICE as id_service
        FROM SELECTIONNE
        JOIN SERVICE ON SELECTIONNE.id_SERVICE = SERVICE.id
        JOIN LOGEMENT ON SELECTIONNE.id_logement = LOGEMENT.id
        JOIN USER ON LOGEMENT.id_USER = USER.id
        WHERE SELECTIONNE.id_SERVICE IN ($placeholders)
    ");
    $stmt->execute($services);
    $selections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <h2>Liste des Demandes</h2>
        <h3>Demandes voyageurs</h3>
        <div class="row">
            <?php if (count($appels) > 0): ?>
                <?php foreach ($appels as $appel): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Demande #<?php echo isset($appel['id']) ? htmlspecialchars($appel['id']) : 'N/A'; ?></h5>
                                <p class="card-text"><strong>Nom du Locataire:</strong> <?php echo isset($appel['nom']) ? htmlspecialchars($appel['nom'] . ' ' . $appel['prenom']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Nom du Service:</strong> <?php echo isset($appel['description']) ? htmlspecialchars($appel['description']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Status:</strong> <?php echo isset($appel['status']) ? htmlspecialchars($appel['status']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Demande:</strong> <?php echo isset($appel['demande']) ? htmlspecialchars($appel['demande']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Type de Service:</strong> <?php echo isset($appel['type']) ? htmlspecialchars($appel['type']) : 'N/A'; ?></p>
                        
                                <?php if ($appel['status'] === 'demande envoyée'): ?>
                                    <form action="accepter_demande.php" method="post" class="d-inline">
                                        <input type="hidden" name="id_location" value="<?php echo isset($appel['id_location']) ? htmlspecialchars($appel['id_location']) : ''; ?>">
                                        <input type="hidden" name="id_service" value="<?php echo isset($appel['id_service']) ? htmlspecialchars($appel['id_service']) : ''; ?>">
                                        <input type="hidden" name="table" value="FAIT_APPELLE">
                                        <button type="submit" class="btn btn-success">Accepter</button>
                                    </form>
                                    <form action="refuser_demande.php" method="post" class="d-inline">
                                        <input type="hidden" name="id_location" value="<?php echo isset($appel['id_location']) ? htmlspecialchars($appel['id_location']) : ''; ?>">
                                        <input type="hidden" name="id_service" value="<?php echo isset($appel['id_service']) ? htmlspecialchars($appel['id_service']) : ''; ?>">
                                        <input type="hidden" name="table" value="FAIT_APPELLE">
                                        <button type="submit" class="btn btn-danger">Refuser</button>
                                    </form>
                                <?php elseif ($appel['status'] !== 'Termine'): ?>
                                    <a href="consulter_demande.php?id_service=<?php echo htmlspecialchars($appel['id_service']); ?>&id_location=<?php echo htmlspecialchars($appel['id_location']); ?>" class="btn btn-primary">Consulter</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune demande trouvée.</p>
            <?php endif; ?>
        </div>

        <h3>Demandes bailleurs</h3>
        <div class="row">
            <?php if (count($selections) > 0): ?>
                <?php foreach ($selections as $selection): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Demande #<?php echo isset($selection['id']) ? htmlspecialchars($selection['id']) : 'N/A'; ?></h5>
                                <p class="card-text"><strong>Nom du Locataire:</strong> <?php echo isset($selection['nom']) ? htmlspecialchars($selection['nom'] . ' ' . $selection['prenom']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Nom du Service:</strong> <?php echo isset($selection['description']) ? htmlspecialchars($selection['description']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Status:</strong> <?php echo isset($selection['status']) ? htmlspecialchars($selection['status']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Demande:</strong> <?php echo isset($selection['demande']) ? htmlspecialchars($selection['demande']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Type de Service:</strong> <?php echo isset($selection['type']) ? htmlspecialchars($selection['type']) : 'N/A'; ?></p>
                                <?php if ($selection['status'] === 'demande envoyée'): ?>
                                    <form action="accepter_demande.php" method="post" class="d-inline">
                                        <input type="hidden" name="id_location" value="<?php echo isset($selection['id_logement']) ? htmlspecialchars($selection['id_logement']) : ''; ?>">
                                        <input type="hidden" name="id_service" value="<?php echo isset($selection['id_service']) ? htmlspecialchars($selection['id_service']) : ''; ?>">
                                        <input type="hidden" name="table" value="SELECTIONNE">
                                        <button type="submit" class="btn btn-success">Accepter</button>
                                    </form>
                                    <form action="refuser_demande.php" method="post" class="d-inline">
                                        <input type="hidden" name="id_location" value="<?php echo isset($selection['id_logement']) ? htmlspecialchars($selection['id_logement']) : ''; ?>">
                                        <input type="hidden" name="id_service" value="<?php echo isset($selection['id_service']) ? htmlspecialchars($selection['id_service']) : ''; ?>">
                                        <input type="hidden" name="table" value="SELECTIONNE">
                                        <button type="submit" class="btn btn-danger">Refuser</button>
                                    </form>
                                <?php elseif ($selection['status'] !== 'Termine'): ?>
                                    <a href="consulter_demande.php?id_service=<?php echo htmlspecialchars($selection['id_service']); ?>&id_logement=<?php echo htmlspecialchars($selection['id_logement']); ?>" class="btn btn-primary">Consulter</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune demande trouvée.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
