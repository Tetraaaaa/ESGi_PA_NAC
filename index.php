<?php
session_start();
require_once 'include/connection_db.php';
include_once 'init.php';

function truncate_text($text, $chars = 150) {
    if (strlen($text) > $chars) {
        $text = substr($text, 0, $chars) . "…";
    }
    return $text;
}

$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$filter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : '';
$pays = isset($_GET['pays']) ? htmlspecialchars($_GET['pays']) : '';
$arrivee = isset($_GET['arrivee']) ? htmlspecialchars($_GET['arrivee']) : '';
$depart = isset($_GET['depart']) ? htmlspecialchars($_GET['depart']) : '';
$voyageurs = isset($_GET['voyageurs']) ? (int) $_GET['voyageurs'] : 0;

$hasFilter = $searchTerm || $filter || $pays || $arrivee || $depart || $voyageurs;

$icons = $bdd->query("SELECT * FROM ICONE_FILTRE")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['Liste des Logements']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/icons.css"> 
    <script src="js/icons.js" defer></script>
    <style>
        .icon-filters {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .filter-icon {
            text-align: center;
            text-decoration: none;
            color: #333;
            margin: 10px;
        }
        .filter-icon img {
            width: 50px;
            height: 50px;
            margin-bottom: 5px;
        }
        .filter-icon span {
            display: block;
            font-size: 14px;
        }
        .search-form {
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-radius: 50px;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .search-form div {
            margin: 5px 0;
        }
        .search-form input, .search-form select {
            border: none;
            background: none;
            outline: none;
            margin: 0 10px;
            flex: 1;
        }
        .search-form button {
            background-color: #ff5a5f;
            border: none;
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .search-form button img {
            width: 20px;
            height: 20px;
        }
        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }
            .search-form input, .search-form select {
                margin: 10px 0;
            }
            .search-form button {
                width: 100%;
                margin-top: 10px;
                border-radius: 20px;
            }
            .filter-icon img {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main class="container main-background">
        <h1 class="text-center"><?php echo $translations['Liste des Logements']; ?></h1>

        <div id="icon-filters-container" class="icon-filters">
            <div class="icon-filters-inner">
                <?php foreach ($icons as $icon): ?>
                    <div class="filter-icon-wrapper">
                        <a href="index.php?filter=<?php echo htmlspecialchars($icon['nom']); ?>" class="filter-icon">
                            <img src="image/<?php echo htmlspecialchars(basename($icon['emplacement'])); ?>" alt="<?php echo htmlspecialchars($icon['nom']); ?>">
                            <span><?php echo htmlspecialchars($icon['nom']); ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination-controls">
                <span id="prev-btn" class="pagination-btn">&laquo;</span>
                <span id="next-btn" class="pagination-btn">&raquo;</span>
            </div>
        </div>

        <form method="get" action="index.php" class="search-form">
            <div>
                <label for="search"><?php echo isset($translations['Destination']) ? $translations['Destination'] : 'Destination'; ?></label>
                <input type="text" id="search" name="search" class="form-control" placeholder="<?php echo isset($translations['Rechercher une destination...']) ? $translations['Rechercher une destination...'] : 'Rechercher une destination...'; ?>" value="<?php echo $searchTerm; ?>">
            </div>
            <div>
                <label for="arrivee"><?php echo isset($translations['Arrivée']) ? $translations['Arrivée'] : 'Arrivée'; ?></label>
                <input type="date" id="arrivee" name="arrivee" class="form-control" value="<?php echo $arrivee; ?>">
            </div>
            <div>
                <label for="depart"><?php echo isset($translations['Départ']) ? $translations['Départ'] : 'Départ'; ?></label>
                <input type="date" id="depart" name="depart" class="form-control" value="<?php echo $depart; ?>">
            </div>
            <div>
                <label for="voyageurs"><?php echo isset($translations['Voyageurs']) ? $translations['Voyageurs'] : 'Voyageurs'; ?></label>
                <input type="number" id="voyageurs" name="voyageurs" class="form-control" placeholder="<?php echo isset($translations['Ajouter des voyageurs']) ? $translations['Ajouter des voyageurs'] : 'Ajouter des voyageurs'; ?>" value="<?php echo $voyageurs; ?>">
            </div>
            <button type="submit"><img src="image/loupe.png" alt="Search"></button>
        </form>

        <div class="row">
            <?php
            try {
                $query = "SELECT LOGEMENT.*, PHOTO_LOGEMENT.emplacement AS photo 
                          FROM LOGEMENT 
                          LEFT JOIN PHOTO_LOGEMENT ON LOGEMENT.id = PHOTO_LOGEMENT.id_LOGEMENT 
                          WHERE 1=1";

                $params = [];

                if ($searchTerm) {
                    $query .= " AND (LOGEMENT.description LIKE :search OR LOGEMENT.nom LIKE :search)";
                    $params['search'] = '%' . $searchTerm . '%';
                }

                if ($filter) {
                    $query .= " AND (LOGEMENT.description LIKE :filter OR LOGEMENT.nom LIKE :filter)";
                    $params['filter'] = '%' . $filter . '%';
                }

                if ($pays) {
                    $query .= " AND LOGEMENT.pays = :pays";
                    $params['pays'] = $pays;
                }

                if ($voyageurs > 0) {
                    $query .= " AND LOGEMENT.capacite_location >= :voyageurs";
                    $params['voyageurs'] = $voyageurs;
                }

                if ($arrivee && $depart) {
                    $query .= " AND LOGEMENT.id IN (
                                    SELECT id_LOGEMENT 
                                    FROM DATE_DISPO 
                                    WHERE date BETWEEN :arrivee AND :depart
                                    AND id_LOGEMENT NOT IN (
                                        SELECT id_LOGEMENT 
                                        FROM DATE_RESERVE 
                                        WHERE date BETWEEN :arrivee AND :depart
                                    )
                                )
                                AND LOGEMENT.id NOT IN (
                                    SELECT id_LOGEMENT 
                                    FROM LOCATION 
                                    WHERE :arrivee BETWEEN date_debut AND date_fin
                                    OR :depart BETWEEN date_debut AND date_fin
                                    OR date_debut BETWEEN :arrivee AND :depart
                                    OR date_fin BETWEEN :arrivee AND :depart
                                )";
                    $params['arrivee'] = $arrivee;
                    $params['depart'] = $depart;
                }

                $query .= " GROUP BY LOGEMENT.id ORDER BY LOGEMENT.id";

                $stmt = $bdd->prepare($query);
                $stmt->execute($params);

                if ($stmt->rowCount() > 0) {
                    while ($logement = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="col-md-4 col-sm-6 mb-3">';
                        echo '<div class="card">';
                        echo '<img src="' . htmlspecialchars($logement['photo']) . '" class="card-img-top" alt="Photo du logement">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($logement['nom']) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars(truncate_text($logement['description'])) . '</p>';
                        echo '<p class="card-text"><small class="text-muted">' . $translations['Prix'] . ': ' . htmlspecialchars($logement['prix']) . '€ ' . $translations['par nuit'] . '</small></p>';
                        echo '<a href="detail_logement.php?id=' . $logement['id'] . '" class="btn btn-primary">' . $translations['Réserver'] . '</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12 text-center">';
                    echo '<p>' . $translations['Aucune location ne correspond à vos besoins.'] . '</p>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo 'Erreur lors de la récupération des logements: ' . $e->getMessage();
            }
            ?>
        </div>
    </main>
    <?php include 'chatbot.php'; ?>
    <?php require_once 'footer.php'; ?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
