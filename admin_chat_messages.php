<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Messages du Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <style>
        body {
            color: black;
        }
        .chat-message {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .chat-message-sent {
            background-color: #d1e7dd;
        }
        .chat-message-received {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>
    <?php 
    require_once 'include/connection_db.php'; 
    session_start();
    require_once 'header.php'; 

    if (!isset($_GET['user1']) || !isset($_GET['user2'])) {
        echo '<p>Erreur : Utilisateurs non spécifiés.</p>';
        exit;
    }

    $user1_id = $_GET['user1'];
    $user2_id = $_GET['user2'];

    $stmt = $bdd->prepare("
        SELECT m.*
        FROM MESSAGE m
        WHERE (m.id_USER_ENVOIE = :user1 AND m.id_USER_RECOIS = :user2)
           OR (m.id_USER_ENVOIE = :user2 AND m.id_USER_RECOIS = :user1)
        ORDER BY m.id ASC
    ");
    $stmt->bindParam(':user1', $user1_id, PDO::PARAM_INT);
    $stmt->bindParam(':user2', $user2_id, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <a href="admin_chats.php" class="btn btn-secondary mb-3">Retour</a>
        <h2>Messages du Chat</h2>
        <div class="chat-messages">
            <?php foreach ($messages as $message): ?>
                <?php
                $stmtUser = $bdd->prepare("SELECT nom, prenom FROM USER WHERE id = :user_id");
                $stmtUser->bindParam(':user_id', $message['id_USER_ENVOIE'], PDO::PARAM_INT);
                $stmtUser->execute();
                $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="chat-message <?php echo $message['id_USER_ENVOIE'] == $user1_id ? 'chat-message-sent' : 'chat-message-received'; ?>">
                    <p><strong>
                        <?php 
                        echo htmlspecialchars("{$user['prenom']} {$user['nom']}");
                        ?>:</strong></p>
                    <p><?php echo htmlspecialchars($message['text']); ?></p>
                    <p><small><?php echo htmlspecialchars($message['date_envoie']); ?></small></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
