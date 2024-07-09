<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Modifier Service</title>
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

    // Récupérer les informations du service
    $stmt = $bdd->prepare("SELECT * FROM SERVICE WHERE id = ?");
    $stmt->execute([$id_service]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo '<p>Service introuvable.</p>';
        exit;
    }

    // Mettre à jour les informations du service
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $description = $_POST['description'];
        $type = $_POST['type'];

        $updateStmt = $bdd->prepare("UPDATE SERVICE SET description = ?, type = ? WHERE id = ?");
        $updateStmt->execute([$description, $type, $id_service]);

        echo "<p>Le service a été mis à jour avec succès.</p>";
    }

    // Supprimer le service
    if (isset($_POST['supprimer'])) {
        // Begin transaction
        $bdd->beginTransaction();
        try {
            // Désactiver les contraintes de clé étrangère
            $bdd->exec('SET FOREIGN_KEY_CHECKS = 0');

            // List of tables and corresponding columns to delete
            $delete_queries = [
                'SELECTIONNE' => 'id_SERVICE',
                'FAIT_APPELLE' => 'id_SERVICE',
                'INTERVENTION_SERVICE' => 'id_service',
                'FACTURE' => 'id_service',
                'CALENDRIER' => 'id_SERVICE'
            ];

            foreach ($delete_queries as $table => $columns) {
                $stmt = $bdd->prepare("DELETE FROM $table WHERE $columns = ?");
                $stmt->execute([$id_service]);
            }

            // Finally, delete from SERVICE table
            $stmt = $bdd->prepare("DELETE FROM SERVICE WHERE id = ?");
            $stmt->execute([$id_service]);

            // Réactiver les contraintes de clé étrangère
            $bdd->exec('SET FOREIGN_KEY_CHECKS = 1');

            $bdd->commit();
            header("Location: admin_services.php");
            exit();
        } catch (Exception $e) {
            $bdd->rollBack();
            echo "Failed to delete service: " . $e->getMessage();
        }
    }
    ?>
    <main class="container mt-4">
        <a href="admin_services.php" class="btn btn-secondary mb-3">Retour</a>
        <h2>Modifier le Service</h2>
        <form method="post" action="admin_modifier_service.php?id=<?php echo htmlspecialchars($id_service); ?>">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($service['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($service['type']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
        <form method="post" action="admin_modifier_service.php?id=<?php echo htmlspecialchars($id_service); ?>" class="mt-3">
            <button type="submit" name="supprimer" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">Supprimer</button>
        </form>
        <a href="admin_service_info.php?id=<?php echo htmlspecialchars($id_service); ?>" class="btn btn-info mt-3">Informations supplémentaires</a>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
