<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Services</title>
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

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : '';

    $query = "SELECT SERVICE.*, USER.nom AS utilisateur_nom, USER.prenom AS utilisateur_prenom 
              FROM SERVICE 
              JOIN USER ON SERVICE.id_USER = USER.id 
              WHERE (SERVICE.description LIKE :search OR SERVICE.type LIKE :search OR USER.nom LIKE :search OR USER.prenom LIKE :search)";
    if ($type_filter !== '') {
        $query .= " AND SERVICE.type = :type_filter";
    }
    $query .= " ORDER BY SERVICE.type";

    $stmt = $bdd->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    if ($type_filter !== '') {
        $stmt->bindValue(':type_filter', $type_filter, PDO::PARAM_STR);
    }
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <form action="acceuil-back.php" method="get">
            <button type="submit" class="btn btn-secondary mt-3">Retour</button>
        </form>
        <h2>Gestion des Services</h2>
        <form class="mb-4" method="get" action="admin_services.php">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search); ?>">
                <select class="form-control" name="type_filter">
                    <option value="">Tous les types</option>
                    <!-- Ajoutez ici les options de filtre pour les types de services -->
                    <option value="type1" <?php if ($type_filter === 'type1') echo 'selected'; ?>>Type 1</option>
                    <option value="type2" <?php if ($type_filter === 'type2') echo 'selected'; ?>>Type 2</option>
                </select>
                <button class="btn btn-primary" type="submit">Filtrer</button>
            </div>
        </form>
        <?php if (count($services) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Utilisateur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['id']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td><?php echo htmlspecialchars($service['type']); ?></td>
                            <td><?php echo htmlspecialchars($service['utilisateur_nom'] . ' ' . $service['utilisateur_prenom']); ?></td>
                            <td>
                                <a href="admin_modifier_service.php?id=<?php echo htmlspecialchars($service['id']); ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <a href="admin_supprimer_service.php?id=<?php echo htmlspecialchars($service['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun service trouvé.</p>
        <?php endif; ?>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
