<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Locations</title>
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
    
    if (!isset($_GET['id_logement']) || empty($_GET['id_logement'])) {
        echo '<p>Erreur : Aucun ID de logement fourni.</p>';
        exit;
    }

    $id_logement = $_GET['id_logement'];
    
    // Requête pour récupérer les locations attribuées au logement
    $stmt = $bdd->prepare("
        SELECT LOCATION.id, USER.nom, USER.prenom, LOCATION.date_debut, LOCATION.date_fin 
        FROM LOCATION 
        JOIN USER ON LOCATION.id_USER = USER.id 
        WHERE LOCATION.id_LOGEMENT = :id_logement
        ORDER BY LOCATION.date_debut
    ");
    $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <?php if (count($locations) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom du Locataire</th>
                        <th>Date de Début</th>
                        <th>Date de Fin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $location): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($location['nom'] . ' ' . $location['prenom']); ?></td>
                            <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($location['date_debut']))); ?></td>
                            <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($location['date_fin']))); ?></td>
                            <td><a href="consulter_location.php?id=<?php echo htmlspecialchars($location['id']); ?>" class="btn btn-primary btn-sm">Consulter</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune location trouvée pour ce logement.</p>
        <?php endif; ?>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
