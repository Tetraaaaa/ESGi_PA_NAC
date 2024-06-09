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
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <?php 
    require_once 'include/connection_db.php'; 
    session_start();
    require_once 'header.php'; 
    
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['id'])) {
        echo '<p>Veuillez vous connecter pour voir vos appels.</p>';
        exit;
    }

    $userId = $_SESSION['id'];
    
    // Rechercher les services attribués à l'utilisateur
    $stmt = $bdd->prepare("SELECT id FROM SERVICE WHERE id_USER = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Si aucun service n'est trouvé, afficher un message et arrêter
    if (empty($services)) {
        echo '<p>Aucun service trouvé pour cet utilisateur.</p>';
        exit;
    }

    // Préparation de la requête pour récupérer les appels
    $placeholders = implode(',', array_fill(0, count($services), '?'));
    $stmt = $bdd->prepare("
        SELECT FAIT_APPELLE.*, SERVICE.type, SERVICE.description, USER.nom, USER.prenom 
        FROM FAIT_APPELLE
        JOIN SERVICE ON FAIT_APPELLE.id_SERVICE = SERVICE.id
        JOIN LOCATION ON FAIT_APPELLE.id_LOCATION = LOCATION.id
        JOIN USER ON LOCATION.id_USER = USER.id
        WHERE FAIT_APPELLE.id_SERVICE IN ($placeholders)
    ");
    $stmt->execute($services);
    $appels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <h2>Liste des Appels</h2>
        <div class="row">
            <?php if (count($appels) > 0): ?>
                <?php foreach ($appels as $appel): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Demande #<?php echo htmlspecialchars($appel['id']); ?></h5>
                                <p class="card-text"><strong>Nom du Locataire:</strong> <?php echo htmlspecialchars($appel['nom'] . ' ' . $appel['prenom']); ?></p>
                                <p class="card-text"><strong>Nom du Service:</strong> <?php echo htmlspecialchars($appel['description']); ?></p>
                                <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($appel['status']); ?></p>
                                <p class="card-text"><strong>Demande:</strong> <?php echo htmlspecialchars($appel['demande']); ?></p>
                                <p class="card-text"><strong>Type de Service:</strong> <?php echo htmlspecialchars($appel['type']); ?></p>
                                <a href="consulter_demande.php?id=<?php echo htmlspecialchars($appel['id']); ?>" class="btn btn-primary">Consulter</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun appel trouvé.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <?php // mettre le footer quand il sera bon ?>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
