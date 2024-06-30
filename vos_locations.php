<?php
session_start();
include_once 'init.php';
require 'include/connection_db.php';

$id_user = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/header.css">

  <title><?php echo $translations['Vos locations']; ?></title>
</head>
<body>
  <?php require_once 'header.php'; ?>

  <main class="container mt-3 main-background">
      <div class="mb-2">
        <button type="button" class="btn btn-primary-custom" onclick="window.location.href='mettre_en_location_un_logement.php'"><?php echo $translations['Mettre en location un logement']; ?></button>
      </div>
    <div class="row">
      <?php
        $stmt = $bdd->prepare("SELECT LOGEMENT.id, LOGEMENT.nom, LOGEMENT.prix, LOGEMENT.capacite_location, PHOTO_LOGEMENT.emplacement FROM LOGEMENT LEFT JOIN PHOTO_LOGEMENT ON LOGEMENT.id = PHOTO_LOGEMENT.id_LOGEMENT WHERE LOGEMENT.id_user = :id_user GROUP BY LOGEMENT.id");
        $stmt->execute([':id_user' => $id_user]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<div class="col-md-4 mb-4">';
          echo '<div class="card">';
          echo '<img src="' . htmlspecialchars($row['emplacement']) . '" class="card-img-top" alt="Photo de logement">';
          echo '<div class="card-body">';
          echo '<h5 class="card-title">' . htmlspecialchars($row['nom']) . '</h5>';
          echo '<p class="card-text">' . $translations['Prix par nuit'] . ': ' . htmlspecialchars($row['prix']) . '€</p>';
          echo '<p class="card-text">' . $translations['Capacité'] . ': ' . htmlspecialchars($row['capacite_location']) . ' ' . $translations['voyageurs'] . '</p>';
          echo '<div>';
          echo '<a href="modifier_logement.php?id=' . $row['id'] . '" class="btn btn-primary me-2">' . $translations['Modifier'] . '</a>';
          echo '<a href="supprimer_logement.php?id=' . $row['id'] . '" class="btn btn-danger me-2" onclick="return confirm(\'' . $translations['Êtes-vous sûr de vouloir supprimer ce logement ?'] . '\');">' . $translations['Supprimer'] . '</a>';
          echo '<a href="planning_location.php?id_logement=' . $row['id'] . '" class="btn btn-info">Planning Location</a>'; // Direct text
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
        }
      ?>
    </div>
  </main>
  
  <?php require_once 'footer.php'; ?>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
