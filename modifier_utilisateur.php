<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
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
        echo '<p>Erreur : Aucun ID d\'utilisateur fourni.</p>';
        exit;
    }

    $user_id = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $stmt = $bdd->prepare("SELECT * FROM USER WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo '<p>Utilisateur introuvable.</p>';
        exit;
    }

    // Mise à jour des informations de l'utilisateur
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fieldsToUpdate = [];
        $values = [];

        if (!empty($_POST['nom'])) {
            $fieldsToUpdate[] = 'nom = ?';
            $values[] = $_POST['nom'];
        }
        if (!empty($_POST['prenom'])) {
            $fieldsToUpdate[] = 'prenom = ?';
            $values[] = $_POST['prenom'];
        }
        if (!empty($_POST['email'])) {
            $fieldsToUpdate[] = 'email = ?';
            $values[] = $_POST['email'];
        }
        if (isset($_POST['status'])) {
            $fieldsToUpdate[] = 'status = ?';
            $values[] = $_POST['status'];
        }
        if (!empty($_POST['age'])) {
            $fieldsToUpdate[] = 'age = ?';
            $values[] = $_POST['age'];
        }
        if (!empty($_POST['presentation'])) {
            $fieldsToUpdate[] = 'presentation = ?';
            $values[] = $_POST['presentation'];
        }
        if (!empty($_POST['sold'])) {
            $fieldsToUpdate[] = 'sold = ?';
            $values[] = $_POST['sold'];
        }

        if (!empty($fieldsToUpdate)) {
            $values[] = $user_id;
            $updateStmt = $bdd->prepare("UPDATE USER SET " . implode(', ', $fieldsToUpdate) . " WHERE id = ?");
            $updateStmt->execute($values);

            echo "<p>L'utilisateur a été mis à jour avec succès.</p>";
            header('Refresh: 2; URL=admin_users.php');
            exit;
        } else {
            echo "<p>Aucune information à mettre à jour.</p>";
        }
    }
    ?>
    <main class="container mt-4">
        <h2>Modifier l'utilisateur</h2>
        <a href="admin_users.php" class="btn btn-secondary">Retour</a>
        <form action="modifier_utilisateur.php?id=<?php echo htmlspecialchars($user_id); ?>" method="post">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="" disabled selected>Choisir un statut</option>
                    <option value="0" <?php echo $user['status'] == 0 ? 'selected' : ''; ?>>Administrateur</option>
                    <option value="1" <?php echo $user['status'] == 1 ? 'selected' : ''; ?>>Inconnu</option>
                    <option value="2" <?php echo $user['status'] == 2 ? 'selected' : ''; ?>>Utilisateur</option>
                    <option value="3" <?php echo $user['status'] == 3 ? 'selected' : ''; ?>>En attente de validation</option>
                    <option value="4" <?php echo $user['status'] == 4 ? 'selected' : ''; ?>>Prestataire</option>
                </select>
            </div>
            <div class="form-group">
                <label for="age">Date de Naissance</label>
                <input type="date" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>">
            </div>
            <div class="form-group">
                <label for="presentation">Présentation</label>
                <textarea class="form-control" id="presentation" name="presentation" rows="4"><?php echo htmlspecialchars($user['presentation']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="sold">Solde</label>
                <input type="number" step="0.01" class="form-control" id="sold" name="sold" value="<?php echo htmlspecialchars($user['sold']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
        <button class="btn btn-secondary mt-3" onclick="window.history.back()">Retour</button>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
