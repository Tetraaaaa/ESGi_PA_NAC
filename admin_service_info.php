<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Informations sur le Service</title>
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

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo '<p>Erreur : Aucun ID de service fourni.</p>';
        exit;
    }

    $id_service = $_GET['id'];

    // Suppression d'un appel
    if (isset($_GET['supprimer_appel_id'])) {
        $supprimer_appel_id = $_GET['supprimer_appel_id'];
        $stmt = $bdd->prepare("DELETE FROM FAIT_APPELLE WHERE id = ?");
        $stmt->execute([$supprimer_appel_id]);
        header("Location: admin_service_info.php?id=" . htmlspecialchars($id_service));
        exit();
    }

    // Suppression d'une sélection
    if (isset($_GET['supprimer_selection_id'])) {
        $supprimer_selection_id = $_GET['supprimer_selection_id'];
        $stmt = $bdd->prepare("DELETE FROM SELECTIONNE WHERE id = ?");
        $stmt->execute([$supprimer_selection_id]);
        header("Location: admin_service_info.php?id=" . htmlspecialchars($id_service));
        exit();
    }

    // Récupérer les appels et sélections de service
    $stmtAppel = $bdd->prepare("
        SELECT FAIT_APPELLE.*, USER.nom, USER.prenom
        FROM FAIT_APPELLE
        JOIN LOCATION ON FAIT_APPELLE.id_location = LOCATION.id
        JOIN USER ON LOCATION.id_USER = USER.id
        WHERE FAIT_APPELLE.id_service = ?
    ");
    $stmtAppel->execute([$id_service]);
    $appels = $stmtAppel->fetchAll(PDO::FETCH_ASSOC);

    $stmtSelection = $bdd->prepare("
        SELECT SELECTIONNE.*, USER.nom, USER.prenom
        FROM SELECTIONNE
        JOIN LOGEMENT ON SELECTIONNE.id_logement = LOGEMENT.id
        JOIN USER ON LOGEMENT.id_USER = USER.id
        WHERE SELECTIONNE.id_service = ?
    ");
    $stmtSelection->execute([$id_service]);
    $selections = $stmtSelection->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <a href="admin_modifier_service.php?id=<?php echo htmlspecialchars($id_service); ?>" class="btn btn-secondary mb-3">Retour</a>
        <h2>Informations sur le Service</h2>
        <?php if (count($appels) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du Client</th>
                        <th>Status</th>
                        <th>Demande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appels as $appel): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appel['id']); ?></td>
                        <td><?php echo htmlspecialchars($appel['nom'] . ' ' . $appel['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($appel['status']); ?></td>
                        <td><?php echo htmlspecialchars($appel['demande']); ?></td>
                        <td>
                            <a href="admin_service_info.php?id=<?php echo htmlspecialchars($id_service); ?>&supprimer_appel_id=<?php echo htmlspecialchars($appel['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet appel ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
        <?php endif; ?>
        <?php if (count($selections) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du Propriétaire</th>
                        <th>Status</th>
                        <th>Demande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selections as $selection): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($selection['id']); ?></td>
                        <td><?php echo htmlspecialchars($selection['nom'] . ' ' . $selection['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($selection['status']); ?></td>
                        <td><?php echo htmlspecialchars($selection['demande']); ?></td>
                        <td>
                            <a href="admin_service_info.php?id=<?php echo htmlspecialchars($id_service); ?>&supprimer_selection_id=<?php echo htmlspecialchars($selection['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette sélection ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
        <?php endif; ?>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
