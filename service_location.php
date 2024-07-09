<?php
session_start();
include_once 'init.php';
require_once 'include/connection_db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<p>' . $translations['Erreur : Aucun ID de logement fourni.'] . '</p>';
    exit;
}

$location_id = $_GET['id'];

$stmt = $bdd->prepare("
    SELECT FAIT_APPELLE.*, SERVICE.type, SERVICE.description, SERVICE.id AS service_id, USER.nom, USER.prenom, LOCATION.id_USER
    FROM FAIT_APPELLE
    JOIN SERVICE ON FAIT_APPELLE.id_service = SERVICE.id
    JOIN LOCATION ON FAIT_APPELLE.id_location = LOCATION.id
    JOIN USER ON LOCATION.id_USER = USER.id
    WHERE FAIT_APPELLE.id_location = :id_location
");
$stmt->bindParam(':id_location', $location_id, PDO::PARAM_INT);
$stmt->execute();
$services_lies = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['Liste des Logements']; ?></title>
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
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo $translations['Rechercher un Service']; ?></h4>
                <input type="text" id="searchInput" class="form-control" placeholder="<?php echo $translations['Rechercher un service...']; ?>">
                <select id="departmentSelect" class="form-control mt-2">
                    <option value=""><?php echo $translations['Sélectionner un département']; ?></option>
                    <?php
                    $stmt = $bdd->query("SELECT id, Nom FROM DEPARTEMENT ORDER BY Nom");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['Nom']) . '</option>';
                    }
                    ?>
                </select>
                <div id="searchResults" class="mt-3"></div>
                <div id="serviceDetails" class="mt-3">
                    <h4><?php echo $translations['Détails du Service']; ?></h4>
                    <p><?php echo $translations['Sélectionnez un service pour voir les détails ici.']; ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <h4><?php echo $translations['Services Liés']; ?></h4>
                <p><?php echo $translations['Informations sur les services liés à la location actuelle.']; ?></p>
                <?php if (count($services_lies) > 0): ?>
                    <?php foreach ($services_lies as $service): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Service #<?php echo isset($service['id']) ? htmlspecialchars($service['id']) : 'N/A'; ?></h5>
                                <p class="card-text"><strong>Nom du Locataire:</strong> <?php echo isset($service['nom']) ? htmlspecialchars($service['nom'] . ' ' . $service['prenom']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Nom du Service:</strong> <?php echo isset($service['description']) ? htmlspecialchars($service['description']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Type de Service:</strong> <?php echo isset($service['type']) ? htmlspecialchars($service['type']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Status:</strong> <?php echo isset($service['status']) ? htmlspecialchars($service['status']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>Demande:</strong> <?php echo isset($service['demande']) ? htmlspecialchars($service['demande']) : 'N/A'; ?></p>
                                <p class="card-text"><strong>ID du Service:</strong> <?php echo isset($service['service_id']) ? htmlspecialchars($service['service_id']) : 'N/A'; ?></p>
                                <?php if ($service['status'] !== 'Termine' && $service['status'] !== 'demande envoyée'): ?>
                                    <a href="consulter_demande_client.php?id_service=<?php echo htmlspecialchars($service['service_id']); ?>&id_location=<?php echo htmlspecialchars($location_id); ?>" class="btn btn-primary">Consulter</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?php echo $translations['Aucun service lié trouvé.']; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            function fetchServices() {
                var query = $('#searchInput').val();
                var department = $('#departmentSelect').val();
                if(query.length > 0 || department.length > 0){
                    $.ajax({
                        url: 'search_service.php',
                        method: 'GET',
                        data: {query: query, department: department},
                        success: function(data){
                            $('#searchResults').html(data);
                        }
                    });
                } else {
                    $('#searchResults').html('');
                }
            }

            $('#searchInput').on('keyup', fetchServices);
            $('#departmentSelect').on('change', fetchServices);

            $(document).on('click', '.list-group-item', function() {
                var serviceId = $(this).data('id');
                var serviceName = $(this).text();
                if(serviceId) {
                    $('#searchInput').val(serviceName);
                    $('#searchResults').html('');
                    $('#departmentSelect').val('');
                    $.ajax({
                        url: 'get_service_details.php',
                        method: 'GET',
                        data: {id: serviceId},
                        success: function(data){
                            $('#serviceDetails').html(data);
                            var url = 'selectionne_service.php?id_service=' + serviceId + '&id_location=' + '<?php echo $location_id; ?>';
                            $('#serviceDetails').append('<a href="' + url + '" class="btn btn-primary mt-3"><?php echo $translations['Sélectionner ce service']; ?></a>');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
