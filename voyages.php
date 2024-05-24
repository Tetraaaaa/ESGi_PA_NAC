<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voyages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <?php 
    require_once 'include/connection_db.php'; 
    session_start();
    $id = $_SESSION['id'];
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);


    require_once 'header.php'; ?>
    <main>
        <?php 
            try {
                $sql = "SELECT * FROM LOCATION WHERE id_USER = :id";  
                $stmt = $bdd->prepare($sql);  
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);  
                $stmt->execute();  
                $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);  
        
                if ($locations) {
                    echo '<div class="container">';
                    foreach ($locations as $location) {
                        $idLogement = $location['id_LOGEMENT'];
                        $sqlLogement = "SELECT * FROM LOGEMENT WHERE id = :idLogement";  
                        $stmtLogement = $bdd->prepare($sqlLogement);  
                        $stmtLogement->bindParam(':idLogement', $idLogement, PDO::PARAM_INT);  
                        $stmtLogement->execute();  
                        $logements = $stmtLogement->fetchAll(PDO::FETCH_ASSOC); 
        
                        foreach ($logements as $logement) {
                            echo '<div class="card mb-3">';
                            echo '<div class="card-header">Logement à ' . htmlspecialchars($logement['ville']) . '</div>';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . htmlspecialchars($logement['nom']) . '</h5>';
                            echo '<p class="card-text">' . htmlspecialchars($logement['description']) . '</p>';
                            echo '<p>Adresse: ' . htmlspecialchars($logement['adresse']) . '</p>';
                            echo '<p>Code Postal: ' . htmlspecialchars($logement['code_postal']) . '</p>';
                            echo '<p>Capacité: ' . htmlspecialchars($logement['capacite_location']) . ' personnes</p>';
                            echo '<p>Date de début: ' . htmlspecialchars($location['date_debut']) . '</p>';
                            echo '<p>Date de fin: ' . htmlspecialchars($location['date_fin']) . '</p>';
                            echo '<button class="btn btn-success" onclick="window.location.href=\'service_location.php?id=' . $location['id'] . '\'">Service</button>';
                            echo '<button class="btn btn-danger" onclick="annulerReservation(' . $location['id'] . ')">Annuler</button> ';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                } else {
                    echo '<p class="text-center">Aucun logement trouvé pour l\'utilisateur avec l\'ID: ' . htmlspecialchars($id) . '</p>';
                }
            } catch(PDOException $e) {
                die("Erreur lors de la requête SQL: " . $e->getMessage());
            }
        ?>
    </main>
    <footer>
    </footer>
    <script>
    function annulerReservation(locationId) {
      if (confirm("Êtes-vous sûr de vouloir annuler cette réservation ?")) {
        window.location.href = 'annuler_reservation.php?id=' + locationId;
      }
    }
  </script>
</body>
</html>
