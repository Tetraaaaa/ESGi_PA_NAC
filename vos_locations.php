<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="css/index.css">
  <title>Vos Locations</title>
</head>
<body>
<?php
    session_start();
    require_once 'header.php';
    require 'include/db.php'; // Assurez-vous que ce fichier contient les informations de connexion à votre base de données.

    $id_user = $_SESSION['id']; // Récupérer l'ID utilisateur de la session
  ?>

  <main class="container mt-3 main-background"> <!-- Ajustement ici de mt-5 à mt-3 pour réduire la marge -->
      <div class="mb-2"> <!-- Changer mb-4 à mb-2 pour réduire la marge inférieure -->
        <button type="button" class="btn btn-primary-custom" onclick="window.location.href='mettre_en_location_un_logement.php'">Mettre en location un logement</button>
      </div>
    <div class="row">
      <?php
        // Requête pour récupérer les logements de l'utilisateur
        $stmt = $bdd->prepare("SELECT LOGEMENT.id, LOGEMENT.nom, LOGEMENT.prix, LOGEMENT.capacite_location, PHOTO_LOGEMENT.emplacement FROM LOGEMENT LEFT JOIN PHOTO_LOGEMENT ON LOGEMENT.id = PHOTO_LOGEMENT.id_LOGEMENT WHERE LOGEMENT.id_user = :id_user GROUP BY LOGEMENT.id");
        $stmt->execute([':id_user' => $id_user]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo '<div class="col-md-4 mb-4">';
          echo '<div class="card">';
          echo '<img src="' . htmlspecialchars($row['emplacement']) . '" class="card-img-top" alt="Photo de logement">';
          echo '<div class="card-body">';
          echo '<h5 class="card-title">' . htmlspecialchars($row['nom']) . '</h5>';
          echo '<p class="card-text">Prix par nuit: ' . htmlspecialchars($row['prix']) . '€</p>';
          echo '<p class="card-text">Capacité: ' . htmlspecialchars($row['capacite_location']) . ' voyageurs</p>';
          echo '<div>'; // Ajouter un div pour contenir les boutons
          echo '<a href="modifier_logement.php?id=' . $row['id'] . '" class="btn btn-primary me-2">Modifier</a>';  // Ajouter la classe me-2 pour une marge droite
          echo '<a href="supprimer_logement.php?id=' . $row['id'] . '" class="btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce logement ?\');">Supprimer</a>';
          echo '</div>'; // Fermer le div contenant les boutons
          echo '</div>'; // Fermer card-body
          echo '</div>'; // Fermer card
          echo '</div>'; // Fermer col-md-4
        }
      ?>
    </div>
  </main>
  
  <?php require_once 'footer.php'; ?>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
