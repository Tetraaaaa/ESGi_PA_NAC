<?php
ini_set('display_errors', 1);
require 'include/db.php';
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'include/connection_db.php';
include_once 'init.php';

if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

function get_services($api, $userId) {
    $url = $api . 'services/user/' . $userId;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$userId = $_SESSION['id'];
$services = get_services($api, $userId);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['Liste des Services']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
<?php require_once 'header.php'; ?>
<main class="container mt-4">
    <h2><?php echo $translations['Liste des Services']; ?></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo $translations['Type de Service']; ?></th>
                <th><?php echo $translations['Description']; ?></th>
                <th><?php echo $translations['Actions']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($services) {
                foreach ($services as $service) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($service['type']) . '</td>';
                    echo '<td>' . htmlspecialchars($service['description']) . '</td>';
                    echo '<td>';
                    echo '<button class="btn btn-danger" onclick="deleteService(' . $service['id'] . ')">' . $translations['Supprimer'] . '</button> ';
                    echo '<button class="btn btn-info" onclick="window.location.href=\'gerer_service.php?id=' . $service['id'] . '\'">' . $translations['Gérer'] . '</button>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">' . $translations['Aucun service trouvé'] . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
</main>
<footer>
    <?php require_once 'footer.php'; ?>
</footer>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script>
    function deleteService(serviceId) {
        if (confirm("<?php echo $translations['Êtes-vous sûr de vouloir supprimer ce service ?']; ?>")) {
            window.location.href = 'delete_service.php?id=' + serviceId;
        }
    }
</script>
</body>
</html>
