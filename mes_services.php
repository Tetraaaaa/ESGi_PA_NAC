<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once 'include/connection_db.php'; // Assurez-vous que ce fichier existe et qu'il n'y a pas d'erreurs ici également

// Initialisation des variables de session ou redirection si non définies
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php"); // Redirection si l'utilisateur n'est pas connecté
    exit;
}

$email = $_SESSION['email'];
$id = $_SESSION['id'];
$nom = $_SESSION['nom'];
$age = $_SESSION['age'];
$status = $_SESSION['status'];
$prenom = $_SESSION['prenom'];
$password = $_SESSION['password'];


$query = $bdd->prepare("SELECT * FROM SERVICE WHERE id_USER = :id");
$query->execute(['id' => $id]); 
$services = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
<?php require_once 'header.php'; ?>
<main class="container mt-4">
    <h2>Liste des Services</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Type de Service</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = $bdd->prepare("SELECT id, type, description FROM SERVICE WHERE id_USER = :id");
        $query->execute(['id' => $id]); 
        $services = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($services as $service) {
          echo '<tr>';
          echo '<td>' . htmlspecialchars($service['type']) . '</td>';
          echo '<td>' . htmlspecialchars($service['description']) . '</td>';
          echo '<td>';
          echo '<button class="btn btn-danger" onclick="deleteService(' . $service['id'] . ')">Supprimer</button> ';
          echo '<button class="btn btn-info" onclick="window.location.href=\'gerer_service.php?id=' . $service['id'] . '\'">Gérer</button>';
          echo '</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </main>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
  <script>
    function deleteService(serviceId) {
      if (confirm("Êtes-vous sûr de vouloir supprimer ce service ?")) {
        window.location.href = 'delete_service.php?id=' + serviceId;
      }
    }
  </script>
</body>
</html>