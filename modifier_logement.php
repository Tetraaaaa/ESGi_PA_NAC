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
  <title>Document</title>
</head>
<body>
  <?php
    session_start();
    require_once 'header.php';
    require 'include/db.php'; // Assurez-vous que ce fichier contient les informations de connexion à votre base de données.

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo '<p>Erreur : Aucun ID de logement fourni.</p>';
        exit;
    }

    $logement_id = $_GET['id'];

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mettre à jour le logement
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $adresse = $_POST['adresse'];
        $ville = $_POST['ville'];
        $prix = $_POST['prix'];
        $code_postal = $_POST['code_postal'];
        $pays = $_POST['pays'];
        $capacite_location = $_POST['capacite_location'];

        $updateStmt = $bdd->prepare("UPDATE LOGEMENT SET nom = ?, description = ?, adresse = ?, ville = ?, prix = ?, code_postal = ?, pays = ?, capacite_location = ? WHERE id = ?");
        $updateStmt->execute([$nom, $description, $adresse, $ville, $prix, $code_postal, $pays, $capacite_location, $logement_id]);

        echo "<p>Le logement a été mis à jour avec succès.</p>";
    }

    // Récupérer les informations du logement pour les afficher dans le formulaire
    $stmt = $bdd->prepare("SELECT * FROM LOGEMENT WHERE id = ?");
    $stmt->execute([$logement_id]);
    $logement = $stmt->fetch();

    if (!$logement) {
        echo '<p>Logement introuvable.</p>';
        exit;
    }
  ?>

<main class="container">
    <h2>Modifier le logement</h2>
    <!-- Modification de l'action pour pointer vers modifier_logement_verif.php -->
    <form action="modifier_logement_verif.php?id=<?php echo htmlspecialchars($logement_id); ?>" method="post">
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($logement['nom']); ?>" required>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($logement['description']); ?></textarea>
      </div>
      <div class="form-group">
        <label for="adresse">Adresse</label>
        <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo htmlspecialchars($logement['adresse']); ?>" required>
      </div>
      <div class="form-group">
        <label for="ville">Ville</label>
        <input type="text" class="form-control" id="ville" name="ville" value="<?php echo htmlspecialchars($logement['ville']); ?>" required>
      </div>
      <div class="form-group">
        <label for="prix">Prix par nuit</label>
        <input type="number" class="form-control" id="prix" name="prix" value="<?php echo htmlspecialchars($logement['prix']); ?>" required>
      </div>
      <div class="form-group">
        <label for="code_postal">Code postal</label>
        <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?php echo htmlspecialchars($logement['code_postal']); ?>" required>
      </div>
      <div class="form-group">
        <label for="pays">Pays</label>
        <input type="text" class="form-control" id="pays" name="pays" value="<?php echo htmlspecialchars($logement['pays']); ?>" required>
      </div>
      <div class="form-group">
        <label for="capacite_location">Capacité de location</label>
        <input type="number" class="form-control" id="capacite_location" name="capacite_location" value="<?php echo htmlspecialchars($logement['capacite_location']); ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Mettre à jour</button><br><br>
    </form>
    <form action="calendrier_logement.php" method="post">
      <input type="hidden" id="id_logement" name="id_logement" value="<?php echo htmlspecialchars($logement_id); ?>">
      <button type="submit" class="btn btn-primary">Calendrier</button>
    </form>
    <div class="container mt-4">
  <h3>Photos du logement</h3>
  <div class="row">
    <?php
    // Récupérer les photos du logement
    $photoStmt = $bdd->prepare("SELECT * FROM PHOTO_LOGEMENT WHERE id_LOGEMENT = ?");
    $photoStmt->execute([$logement_id]);
    while ($photo = $photoStmt->fetch()) {
      echo '<div class="col-md-4 mb-3">';
      echo '<div class="card">';
      echo '<img src="' . htmlspecialchars($photo['emplacement']) . '" class="card-img-top" alt="Photo du logement">';
      echo '<div class="card-body">';
      echo '<a href="supprimer_photo.php?photo_id=' . $photo['id'] . '&logement_id=' . $logement_id . '" class="btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette photo ?\');">Supprimer</a>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
    ?>
  </div>
  <div class="container mt-4">
  <h3>Ajouter une nouvelle photo</h3>
  <form action="ajouter_photo.php?logement_id=<?php echo htmlspecialchars($logement_id); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="photo">Choisir une photo</label>
      <input type="file" class="form-control-file" id="photo" name="photo" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
  </form>
  </div>
  </div>
</main>
<?php require_once 'footer.php'; ?>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>