<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Utilisateurs</title>
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
    
    function getStatusText($status) {
        switch ($status) {
            case 0:
                return "Administrateur";
            case 1:
                return "Inconnu";
            case 2:
                return "Utilisateur";
            case 3:
                return "En attente de validation";
            case 4:
                return "Prestataire";
            case 10:
                return "Banni";
            default:
                return "Statut non défini";
        }
    }

    // Bannir utilisateur
    if (isset($_GET['bannir_id'])) {
        $bannir_id = $_GET['bannir_id'];
        $stmt = $bdd->prepare("UPDATE USER SET status = 10 WHERE id = ?");
        $stmt->execute([$bannir_id]);
        header("Location: admin_users.php");
        exit();
    }

    // Supprimer utilisateur
    if (isset($_GET['supprimer_id'])) {
        $supprimer_id = $_GET['supprimer_id'];

        // Begin transaction
        $bdd->beginTransaction();
        try {
            // Désactiver les contraintes de clé étrangère
            $bdd->exec('SET FOREIGN_KEY_CHECKS = 0');

            // List of tables and corresponding columns to delete
            $delete_queries = [
                'SERVICE' => 'id_USER',
                'PHOTO_PROFIL' => 'id_USER',
                'MESSAGE' => ['id_USER_ENVOIE', 'id_USER_RECOIS'],
                'LOGEMENT' => 'id_USER',
                'LOCATION' => 'id_USER',
                'FAIT_APPELLE' => 'id_LOCATION',
                'SELECTIONNE' => 'id_LOGEMENT',
                'PHOTO_LOGEMENT' => 'id_LOGEMENT',
                'INTERVENTION_SERVICE' => ['id_location', 'id_logement', 'id_service'],
                'FACTURE' => ['id_service', 'id_location', 'id_logement'],
                'ETAT' => 'id_LOCATION',
                'DATE_RESERVE' => 'id_LOCATION',
                'DATE_INTERVENTION' => ['id_SERVICE', 'id_LOGEMENT', 'id_LOCATION'],
                'DATE_DISPO' => 'id_LOGEMENT',
                'CARACTERISTIQUE_LOGEMENT' => 'id_LOGEMENT',
                'CALENDRIER' => 'id_SERVICE'
            ];

            foreach ($delete_queries as $table => $columns) {
                if (is_array($columns)) {
                    foreach ($columns as $column) {
                        $stmt = $bdd->prepare("DELETE FROM $table WHERE $column = ?");
                        $stmt->execute([$supprimer_id]);
                    }
                } else {
                    $stmt = $bdd->prepare("DELETE FROM $table WHERE $columns = ?");
                    $stmt->execute([$supprimer_id]);
                }
            }

            // Finally, delete from USER table
            $stmt = $bdd->prepare("DELETE FROM USER WHERE id = ?");
            $stmt->execute([$supprimer_id]);

            // Réactiver les contraintes de clé étrangère
            $bdd->exec('SET FOREIGN_KEY_CHECKS = 1');

            $bdd->commit();
            header("Location: admin_users.php");
            exit();
        } catch (Exception $e) {
            $bdd->rollBack();
            echo "Failed to delete user: " . $e->getMessage();
        }
    }

    // Recherche et filtres
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

    $query = "SELECT * FROM USER WHERE (nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
    if ($status_filter !== '') {
        $query .= " AND status = :status_filter";
    }

    $stmt = $bdd->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    if ($status_filter !== '') {
        $stmt->bindValue(':status_filter', $status_filter, PDO::PARAM_INT);
    }
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <h2>Gestion des Utilisateurs</h2>
        <a href="acceuil-back.php" class="btn btn-secondary">Retour</a>
        <form class="mb-4" method="get" action="admin_users.php">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search); ?>">
                <select class="form-control" name="status_filter">
                    <option value="">Tous les statuts</option>
                    <option value="0" <?php if ($status_filter === '0') echo 'selected'; ?>>Administrateur</option>
                    <option value="1" <?php if ($status_filter === '1') echo 'selected'; ?>>Inconnu</option>
                    <option value="2" <?php if ($status_filter === '2') echo 'selected'; ?>>Utilisateur</option>
                    <option value="3" <?php if ($status_filter === '3') echo 'selected'; ?>>En attente de validation</option>
                    <option value="4" <?php if ($status_filter === '4') echo 'selected'; ?>>Prestataire</option>
                    <option value="10" <?php if ($status_filter === '10') echo 'selected'; ?>>Banni</option>
                </select>
                <button class="btn btn-primary" type="submit">Filtrer</button>
            </div>
        </form>
        <?php if (count($users) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Date de Naissance</th>
                        <th>Présentation</th>
                        <th>Sold</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['nom']); ?></td>
                        <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars(getStatusText($user['status'])); ?></td>
                        <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($user['age']))); ?></td>
                        <td><?php echo htmlspecialchars($user['presentation']); ?></td>
                        <td><?php echo htmlspecialchars($user['sold']); ?></td>
                        <td>
                            <a href="modifier_utilisateur.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="admin_users.php?bannir_id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir bannir cet utilisateur ?');">Bannir</a>
                            <a href="admin_users.php?supprimer_id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur et toutes les données associées ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php endif; ?>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
