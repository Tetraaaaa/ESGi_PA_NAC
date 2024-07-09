<?php
session_start();
include_once 'init.php';
require_once 'include/connection_db.php';

$id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['Voyages']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main>
        <div class="container">
            <?php 
                try {
                    $sql = "SELECT * FROM LOCATION WHERE id_USER = :id ORDER BY date_debut";  
                    $stmt = $bdd->prepare($sql);  
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);  
                    $stmt->execute();  
                    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);  
            
                    if ($locations) {
                        foreach ($locations as $location) {
                            $idLogement = $location['id_LOGEMENT'];
                            $sqlLogement = "SELECT * FROM LOGEMENT WHERE id = :idLogement";  
                            $stmtLogement = $bdd->prepare($sqlLogement);  
                            $stmtLogement->bindParam(':idLogement', $idLogement, PDO::PARAM_INT);  
                            $stmtLogement->execute();  
                            $logements = $stmtLogement->fetchAll(PDO::FETCH_ASSOC); 
            
                            foreach ($logements as $logement) {
                                echo '<div class="card mb-3">';
                                echo '<div class="card-header">' . $translations['Logement à'] . ' ' . htmlspecialchars($logement['ville']) . '</div>';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title">' . htmlspecialchars($logement['nom']) . '</h5>';
                                echo '<p class="card-text">' . htmlspecialchars($logement['description']) . '</p>';
                                echo '<p>' . $translations['Adresse'] . ': ' . htmlspecialchars($logement['adresse']) . '</p>';
                                echo '<p>' . $translations['Code postal'] . ': ' . htmlspecialchars($logement['code_postal']) . '</p>';
                                echo '<p>' . $translations['Capacité'] . ': ' . htmlspecialchars($logement['capacite_location']) . ' ' . $translations['voyageurs'] . '</p>';
                                echo '<p>' . $translations['Date de début'] . ': ' . htmlspecialchars(date('d-m-Y', strtotime($location['date_debut']))) . '</p>';
                                echo '<p>' . $translations['Date de fin'] . ': ' . htmlspecialchars(date('d-m-Y', strtotime($location['date_fin']))) . '</p>';
                                $dateActuelle = date('Y-m-d');
                                echo '<div class="d-flex flex-wrap">';
                                if ($location['date_fin'] > $dateActuelle) {
                                    echo '<button class="btn btn-success me-2 mb-2" onclick="window.location.href=\'service_location.php?id=' . $location['id'] . '\'">' . $translations['Service'] . '</button>';
                                    echo '<button class="btn btn-danger me-2 mb-2" onclick="annulerReservation(' . $location['id'] . ')">' . $translations['Annuler'] . '</button> ';
                                }
                                echo '<button class="btn btn-primary me-2 mb-2" onclick="window.location.href=\'consulter_location_client.php?id=' . $location['id'] . '\'">' . (isset($translations['Consulter']) ? $translations['Consulter'] : 'Consulter') . '</button>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                    } else {
                        echo '<p class="text-center">' . $translations['Aucun logement trouvé'] . ' ' . htmlspecialchars($id) . '</p>';
                    }
                } catch(PDOException $e) {
                    die("Erreur lors de la requête SQL: " . $e->getMessage());
                }
            ?>
        </div>
    </main>
   <?php require_once 'footer.php'; ?>
    <script>
    function annulerReservation(locationId) {
      if (confirm("<?php echo $translations['Êtes-vous sûr de vouloir annuler cette réservation ?']; ?>")) {
        window.location.href = 'annuler_reservation.php?id=' + locationId;
      }
    }
  </script>
</body>
</html>
