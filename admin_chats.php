<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Chats</title>
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
        <h2>Gestion des Chats</h2>
        <a href="acceuil-back.php" class="btn btn-secondary mb-3">Retour</a>
        <?php
        // Requête pour récupérer les interactions uniques entre utilisateurs
        $stmt = $bdd->prepare("
            SELECT 
                LEAST(u1.id, u2.id) AS user1_id,
                GREATEST(u1.id, u2.id) AS user2_id,
                LEAST(CONCAT(u1.nom, ' ', u1.prenom), CONCAT(u2.nom, ' ', u2.prenom)) AS user1_name,
                GREATEST(CONCAT(u1.nom, ' ', u1.prenom), CONCAT(u2.nom, ' ', u2.prenom)) AS user2_name
            FROM MESSAGE m
            JOIN USER u1 ON m.id_USER_ENVOIE = u1.id
            JOIN USER u2 ON m.id_USER_RECOIS = u2.id
            GROUP BY user1_id, user2_id
        ");
        $stmt->execute();
        $interactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Utilisateur 1</th>
                    <th>Utilisateur 2</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($interactions as $interaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($interaction['user1_name']); ?></td>
                    <td><?php echo htmlspecialchars($interaction['user2_name']); ?></td>
                    <td>
                        <form action="admin_chat_messages.php" method="get">
                            <input type="hidden" name="user1" value="<?php echo htmlspecialchars($interaction['user1_id']); ?>">
                            <input type="hidden" name="user2" value="<?php echo htmlspecialchars($interaction['user2_id']); ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Consulter Chat</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
