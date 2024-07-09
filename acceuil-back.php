<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Admin</title>
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
    ?>
    <main class="container mt-4">
        <h1 class="mb-4">Administration</h1>
        <div class="list-group">
            <a href="admin_dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="admin_users.php" class="list-group-item list-group-item-action">Gestion des Utilisateurs</a>
            <a href="admin_logements.php" class="list-group-item list-group-item-action">Gestion des Logements</a>
            <a href="admin_reservations.php" class="list-group-item list-group-item-action">Gestion des Réservations</a>
            <a href="admin_services.php" class="list-group-item list-group-item-action">Gestion des Services</a>
            <a href="admin_chats.php" class="list-group-item list-group-item-action">Gestion des Chats</a>
            <a href="admin_reports.php" class="list-group-item list-group-item-action">Rapports</a>
            <a href="admin_settings.php" class="list-group-item list-group-item-action">Paramètres</a>
        </div>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
