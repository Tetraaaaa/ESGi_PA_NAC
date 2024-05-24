<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gérer le Service</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="css/header.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
</head>
<body>
<?php
session_start();
require_once 'include/connection_db.php'; // Connexion à la BDD

// Récupération de l'ID du service depuis l'URL
$serviceId = isset($_GET['id']) ? $_GET['id'] : '';

// Assurez-vous que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

if ($serviceId) {
    // Récupération de l'ID de l'utilisateur qui possède le service
    $query = $bdd->prepare("SELECT id, id_USER FROM SERVICE WHERE id = :id");
    $query->execute(['id' => $serviceId]);
    $service = $query->fetch(PDO::FETCH_ASSOC);

    // Si le service n'existe pas ou si l'utilisateur actuel n'est pas le propriétaire
    if (!$service || $service['id_USER'] != $_SESSION['id']) {
        echo '<div class="alert alert-danger">Vous n\'êtes pas autorisé à modifier ce service.</div>';
        exit; // Arrêt du script
    }
}

if ($serviceId) {
    // Récupération des informations du service
    $query = $bdd->prepare("SELECT * FROM SERVICE WHERE id = :id");
    $query->execute(['id' => $serviceId]);
    $service = $query->fetch(PDO::FETCH_ASSOC);

    // Récupération des dates associées
    $dateQuery = $bdd->prepare("SELECT date FROM CALENDRIER WHERE id_service = :id_service");
    $dateQuery->execute(['id_service' => $serviceId]);
    $dates = $dateQuery->fetchAll(PDO::FETCH_COLUMN);
}

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $description = $_POST['description'];
    $selectedDates = explode(',', $_POST['dates_disponibles']); // Transforme la chaîne de dates en tableau

    // Mettre à jour le service
    $updateQuery = $bdd->prepare("UPDATE SERVICE SET type = :type, description = :description WHERE id = :id");
    $updateQuery->execute(['type' => $type, 'description' => $description, 'id' => $serviceId]);

    // Récupération des dates déjà présentes pour ce service
    $existingDatesQuery = $bdd->prepare("SELECT date FROM CALENDRIER WHERE id_service = :id_service");
    $existingDatesQuery->execute(['id_service' => $serviceId]);
    $existingDates = $existingDatesQuery->fetchAll(PDO::FETCH_COLUMN);

    // Fusion des dates existantes avec les nouvelles, sans doublons
    $allDates = array_unique(array_merge($existingDates, $selectedDates));

    // Mise à jour des dates dans CALENDRIER
    $bdd->beginTransaction();
    $deleteDates = $bdd->prepare("DELETE FROM CALENDRIER WHERE id_service = :id_service");
    $deleteDates->execute(['id_service' => $serviceId]);

    $insertDate = $bdd->prepare("INSERT INTO CALENDRIER (id_service, date) VALUES (:id_service, :date)");
    foreach ($allDates as $date) {
        $insertDate->execute(['id_service' => $serviceId, 'date' => $date]);
    }
    $bdd->commit();

    echo '<div class="alert alert-success" role="alert">Service et dates mis à jour avec succès!</div>';
}
?>
<body>
<?php require_once 'header.php'; ?>
<main class="container">
    <h1>Éditer le Service</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="type" class="form-label">Type de Service</label>
            <input type="text" class="form-control" id="type" name="type" required value="<?php echo htmlspecialchars($service['type']); ?>">
        </div>
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($service['description']); ?></textarea>
        </div>
        <div>
            <?php
            try {
                // Préparation de la requête pour récupérer les dates du service spécifié
                $query = $bdd->prepare("SELECT date FROM CALENDRIER WHERE id_service = :id_service");
                $query->execute(['id_service' => $serviceId]);
                $dates = $query->fetchAll(PDO::FETCH_ASSOC);
            
                echo "<h1>Dates disponibles pour le service ID: $serviceId</h1>";
                echo "<ul>";
                foreach ($dates as $date) {
                    echo "<li>" . htmlspecialchars($date['date']) . "</li>"; // Affichage sécurisé des dates
                }
                echo "</ul>";
            } catch (PDOException $e) {
                echo "Erreur lors de la récupération des dates: " . $e->getMessage();
            }
            ?>
        </div>
        <div class="form-group">
        <label for="dates_disponibles">Ajouter une date:</label>
        <input type="text" id="dates_disponibles" name="dates_disponibles" class="form-control" readonly>
      </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</main>
<script>
    $(function() {
      var datesArray = [];

      $("#dates_disponibles").datepicker({
        minDate: 0,
        onSelect: function(dateText, inst) {
          if (!datesArray.includes(dateText)) {
            datesArray.push(dateText);
          } else {
            var index = datesArray.indexOf(dateText);
            datesArray.splice(index, 1);
          }
          $("#dates_disponibles").val(datesArray.join(", "));
        },
        beforeShowDay: function(date) {
          var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
          return [true, datesArray.indexOf(string) >= 0 ? "selected-date" : ""];
        },
        numberOfMonths: [1, 1],
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd'
      });

      // Style pour les dates sélectionnées
      $("<style type='text/css'> .selected-date a{ background-color: #F00 !important; color: #FFF !important;} </style>").appendTo("head");
    });
  </script>
</body>
</html>