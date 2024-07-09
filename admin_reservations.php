<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Réservations</title>
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
        <h2>Gestion des Réservations</h2>
        <a href="acceuil-back.php" class="btn btn-secondary mb-3">Retour</a>

        <form method="GET" action="admin_reservations.php" class="mb-4">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="search" placeholder="Recherche...">
                </div>
                <div class="col">
                    <select class="form-control" name="filter">
                        <option value="">Tous</option>
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmé</option>
                        <option value="cancelled">Annulé</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </div>
        </form>

        <?php
        $search = $_GET['search'] ?? '';
        $filter = $_GET['filter'] ?? '';

        $query = "SELECT LOCATION.*, USER.nom AS nom_user, USER.prenom AS prenom_user, LOGEMENT.nom AS nom_logement FROM LOCATION JOIN USER ON LOCATION.id_USER = USER.id JOIN LOGEMENT ON LOCATION.id_LOGEMENT = LOGEMENT.id WHERE 1";

        if ($search) {
            $query .= " AND (USER.nom LIKE :search OR USER.prenom LIKE :search OR LOGEMENT.nom LIKE :search)";
        }

        if ($filter) {
            $query .= " AND LOCATION.status = :filter";
        }

        $stmt = $bdd->prepare($query);

        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        if ($filter) {
            $stmt->bindValue(':filter', $filter, PDO::PARAM_STR);
        }

        $stmt->execute();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Logement</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['nom_user'] . ' ' . $reservation['prenom_user']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['nom_logement']); ?></td>
                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($reservation['date_debut']))); ?></td>
                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($reservation['date_fin']))); ?></td>
                    <td>
                        <a href="admin_modifier_reservation.php?id=<?php echo htmlspecialchars($reservation['id']); ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="supprimer_reservation.php?id=<?php echo htmlspecialchars($reservation['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation et toutes les données associées ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
