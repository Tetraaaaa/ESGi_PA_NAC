<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Logements</title>
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
    require_once 'header.php'; 
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo '<p>Erreur : Aucun ID de logement fourni.</p>';
        exit;
    }
    
    $location_id = $_GET['id'];
    ?>
    <main class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="p-3 border bg-light">
                    <h4>Rechercher un Service</h4>
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un service...">
                    <select id="departmentSelect" class="form-control mt-2">
                        <option value="">Sélectionner un département</option>
                        <?php
                        $stmt = $bdd->query("SELECT id, Nom FROM DEPARTEMENT ORDER BY Nom");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['Nom']) . '</option>';
                        }
                        ?>
                    </select>
                    <div id="searchResults" class="mt-3"></div>
                </div>
                <div id="serviceDetails" class="p-3 border bg-light mt-3">
                    <h4>Détails du Service</h4>
                    <p>Sélectionnez un service pour voir les détails ici.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border bg-light">
                    <h4>Services Liés</h4>
                    <p>Informations sur les services liés à la location actuelle.</p>
                </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
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
                            $('#serviceDetails').append('<a href="' + url + '" class="btn btn-primary mt-3">Sélectionner ce service</a>');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
