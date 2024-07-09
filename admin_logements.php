<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Logements</title>
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

    $query = "SELECT LOGEMENT.*, USER.nom AS proprietaire_nom, USER.prenom AS proprietaire_prenom, TYPE_LOGEMENT.name AS type_logement
              FROM LOGEMENT
              JOIN USER ON LOGEMENT.id_USER = USER.id
              JOIN TYPE_LOGEMENT ON LOGEMENT.type_bien = TYPE_LOGEMENT.id
              WHERE (LOGEMENT.nom LIKE :search OR USER.nom LIKE :search OR USER.prenom LIKE :search)";
    if ($type_filter !== '') {
        $query .= " AND LOGEMENT.type_bien = :type_filter";
    }

    $stmt = $bdd->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    if ($type_filter !== '') {
        $stmt->bindValue(':type_filter', $type_filter, PDO::PARAM_INT);
    }
    $stmt->execute();
    $logements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <form action="acceuil-back.php" method="get">
            <button type="submit" class="btn btn-secondary mt-3">Retour</button>
        </form>
        <h2>Gestion des Logements</h2>
        <form class="mb-4" method="get" action="admin_logements.php">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search); ?>">
                <select class="form-control" name="type_filter">
                    <option value="">Tous les types</option>
                    <?php
                    $typeStmt = $bdd->prepare("SELECT * FROM TYPE_LOGEMENT");
                    $typeStmt->execute();
                    $types = $typeStmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($types as $type) {
                        echo '<option value="' . $type['id'] . '"' . ($type_filter == $type['id'] ? ' selected' : '') . '>' . htmlspecialchars($type['name']) . '</option>';
                    }
                    ?>
                </select>
                <button class="btn btn-primary" type="submit">Filtrer</button>
            </div>
        </form>
        <?php if (count($logements) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Propriétaire</th>
                        <th>Type</th>
                        <th>Ville</th>
                        <th>Adresse</th>
                        <th>Prix</th>
                        <th>Capacité</th>
                        <th>Validation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logements as $logement): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($logement['nom']); ?></td>
                            <td><?php echo htmlspecialchars($logement['proprietaire_nom'] . ' ' . $logement['proprietaire_prenom']); ?></td>
                            <td><?php echo htmlspecialchars($logement['type_logement']); ?></td>
                            <td><?php echo htmlspecialchars($logement['ville']); ?></td>
                            <td><?php echo htmlspecialchars($logement['adresse']); ?></td>
                            <td><?php echo htmlspecialchars($logement['prix']); ?> €</td>
                            <td><?php echo htmlspecialchars($logement['capacite_location']); ?></td>
                            <td><?php echo htmlspecialchars($logement['validation'] ? 'Validé' : 'Non validé'); ?></td>
                            <td>
                                <a href="admin_modifier_logement.php?id=<?php echo htmlspecialchars($logement['id']); ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <a href="admin_supprimer_logement.php?id=<?php echo htmlspecialchars($logement['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce logement ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun logement trouvé.</p>
        <?php endif; ?>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
