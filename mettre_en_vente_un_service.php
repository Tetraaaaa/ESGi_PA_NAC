<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mettre en vente un service</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="css/header.css">
</head>
<body>
  <?php
    session_start();
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $nom = $_SESSION['nom'];
    $age = $_SESSION['age'];
    $status = $_SESSION['status'];
    $prenom = $_SESSION['prenom'];
    $password = $_SESSION['password'];
  ?>

  <?php require_once 'header.php'; ?>

  <main class="container">
    <form method="POST" action="mettre_en_vente_un_service_verif.php" class="my-registration-validation">
      <div class="form-group">
        <label for="type" class="form-label">Quel type de service ?</label>
        <input type="text" class="form-control" id="type" name="type" required>
      </div>
      <div class="form-group" id="presentationField">
        <label for="presentation">Présentation complète du service (Ce texte sera votre page de présentation, vous devez y faire figurer tous les éléments, y compris les prix)</label>
        <textarea id="presentation" class="form-control" name="presentation"></textarea>
      </div>
      <div class="form-group">
  <label for="departements">Zones d'intervention</label>
  <select id="departements" name="departements[]" class="form-control" multiple>
  <?php
  require_once 'include/connection_db.php';

  try {
      $stmt = $bdd->query('SELECT id, nom FROM DEPARTEMENT ORDER BY nom');
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nom']) . '</option>';
      }
  } catch (PDOException $e) {
      echo 'Erreur lors de la récupération des départements : ' . $e->getMessage();
  }
  ?>
</select>

</div>

      <div class="form-group">
        <label for="dates_disponibles">Dates disponibles:</label>
        <input type="text" id="dates_disponibles" name="dates_disponibles" class="form-control" readonly>
      </div>
      <button type="submit" class="btn btn-primary">Mettre en vente</button>
    </form>
  </main>

  

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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

      
      $("<style type='text/css'> .selected-date a{ background-color: #F00 !important; color: #FFF !important;} </style>").appendTo("head");
    });
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>