<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Réservation</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/header.css">
</head>
<body>
  <?php
    session_start();
    require_once 'header.php';
    require 'include/connection_db.php';

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo '<p>Erreur : Aucun ID de réservation fourni.</p>';
        header('Refresh: 2; URL=admin_reservations.php');
        exit;
    }

    $reservation_id = $_GET['id'];

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mettre à jour la réservation
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;

        $updateFields = [];
        $updateValues = [];

        if ($date_debut) {
            $updateFields[] = 'date_debut = ?';
            $updateValues[] = $date_debut;
        }
        if ($date_fin) {
            $updateFields[] = 'date_fin = ?';
            $updateValues[] = $date_fin;
        }

        if (!empty($updateFields)) {
            $updateValues[] = $reservation_id;
            $updateStmt = $bdd->prepare('UPDATE LOCATION SET ' . implode(', ', $updateFields) . ' WHERE id = ?');
            $updateStmt->execute($updateValues);
        }

        echo "<p>La réservation a été mise à jour avec succès.</p>";
        header('Refresh: 2; URL=admin_reservations.php');
        exit;
    }

    // Récupérer les informations de la réservation pour les afficher dans le formulaire
    $stmt = $bdd->prepare("SELECT * FROM LOCATION WHERE id = ?");
    $stmt->execute([$reservation_id]);
    $reservation = $stmt->fetch();

    if (!$reservation) {
        echo '<p>Réservation introuvable.</p>';
        header('Refresh: 2; URL=admin_reservations.php');
        exit;
    }
  ?>

<main class="container">
    <h2>Modifier la réservation</h2>
    <form action="admin_modifier_reservation.php?id=<?php echo htmlspecialchars($reservation_id); ?>" method="post">
      <div class="form-group">
        <label for="date_debut">Date de début</label>
        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($reservation['date_debut']); ?>">
      </div>
      <div class="form-group">
        <label for="date_fin">Date de fin</label>
        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($reservation['date_fin']); ?>">
      </div>
      <button type="submit" class="btn btn-primary">Mettre à jour</button><br><br>
    </form>
    <form action="acceuil-back.php" method="get">
      <button type="submit" class="btn btn-secondary mt-3">Retour</button>
    </form>
</main>
<?php require_once 'footer.php'; ?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
