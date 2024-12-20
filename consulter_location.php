<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Location</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/consulter_location.css">
</head>
<body>
    <?php
    require_once 'include/connection_db.php';
    session_start();

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo '<p>Erreur : Aucun ID de location fourni.</p>';
        exit;
    }

    $id_location = $_GET['id'];

    $stmt = $bdd->prepare("
        SELECT LOCATION.*, USER.id AS locataire_id
        FROM LOCATION
        JOIN USER ON LOCATION.id_USER = USER.id
        WHERE LOCATION.id = :id_location
    ");
    $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
    $stmt->execute();
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$location) {
        echo '<p>Aucune location trouvée.</p>';
        exit;
    }

    $locataire_id = $location['id_USER'];
    $logement_id = $location['id_LOGEMENT'];

    // Récupérer l'ID du propriétaire du logement
    $stmt = $bdd->prepare("
        SELECT id_USER AS proprietaire_id
        FROM LOGEMENT
        WHERE id = :logement_id
    ");
    $stmt->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
    $stmt->execute();
    $logement = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$logement) {
        echo '<p>Aucun logement trouvé.</p>';
        exit;
    }

    $proprietaire_id = $logement['proprietaire_id'];

    // Récupérer les états des lieux associés à la location
    $stmt = $bdd->prepare("
        SELECT * FROM ETAT
        WHERE id_location = :id_location
    ");
    $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
    $stmt->execute();
    $etats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <h2>Détails de la Location</h2>
                <div class="card mb-4 transparent-background">
                    <div class="card-body">
                        <h5 class="card-title">Location #<?php echo isset($location['id']) ? htmlspecialchars($location['id']) : 'N/A'; ?></h5>
                        <p class="card-text"><strong>ID Locataire:</strong> <?php echo htmlspecialchars($locataire_id); ?></p>
                        <p class="card-text"><strong>Date de début:</strong> <?php echo isset($location['date_debut']) ? htmlspecialchars(date('d-m-Y', strtotime($location['date_debut']))) : 'N/A'; ?></p>
                        <p class="card-text"><strong>Date de fin:</strong> <?php echo isset($location['date_fin']) ? htmlspecialchars(date('d-m-Y', strtotime($location['date_fin']))) : 'N/A'; ?></p>
                        <button class="btn btn-primary" onclick="window.location.href='creer_etat_des_lieux.php?id_location=<?php echo htmlspecialchars($id_location); ?>'">Créer un état des lieux</button>
                    </div>
                </div>
                <?php if (!empty($etats)): ?>
                    <h3>États des lieux</h3>
                    <ul class="list-group mb-4">
                        <?php foreach ($etats as $etat): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center etat-des-lieux-item">
                                <span><?php echo htmlspecialchars($etat['date']); ?> - <?php echo htmlspecialchars($etat['type']); ?></span>
                                <div>
                                    <a href="<?php echo htmlspecialchars($etat['emplacement']); ?>" class="btn btn-primary btn-sm">Voir l'état des lieux</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h2>Chat</h2>
                <div class="chat-box chat-box-content" id="chatBox">
                    <!-- Les messages seront chargés ici -->
                </div>
                <div class="chat-input-wrapper">
                    <textarea id="chatInput" class="chat-input" placeholder="Tapez votre message..."></textarea>
                    <div class="upload-btn-wrapper">
                        <button class="btn btn-primary">📷</button>
                        <input type="file" id="imageInput" name="image" accept="image/*">
                    </div>
                </div>
                <!-- Modal pour afficher l'image agrandie -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="modalImage" src="" alt="Image" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        const id_location = <?php echo json_encode($id_location); ?>;
        const id_locataire = <?php echo json_encode($locataire_id); ?>;
        const id_proprietaire = <?php echo json_encode($proprietaire_id); ?>;

        function loadMessages() {
            $.ajax({
                url: 'load_messages_location_proprio.php',
                method: 'GET',
                data: {
                    id_location: id_location,
                    id_locataire: id_locataire,
                    id_proprietaire: id_proprietaire
                },
                success: function(data) {
                    $('#chatBox').html(data);
                    $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                }
            });
        }

        function sendMessage() {
            const message = $('#chatInput').val().trim();
            if (message !== '') {
                $.post('send_message_location_proprio.php', {
                    message: message,
                    id_location: id_location,
                    id_locataire: id_locataire,
                    id_proprietaire: id_proprietaire
                }, function() {
                    $('#chatInput').val('');
                    loadMessages();
                });
            }
        }

        $('#chatInput').on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                sendMessage();
                e.preventDefault();
            }
        });

        $('#imageInput').on('change', function() {
            const formData = new FormData();
            formData.append('image', this.files[0]);
            formData.append('id_location', id_location);
            formData.append('id_locataire', id_locataire);
            formData.append('id_proprietaire', id_proprietaire);

            $.ajax({
                url: 'upload_image.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    loadMessages();
                }
            });
        });

        $('#chatBox').on('click', 'img', function() {
            const src = $(this).attr('src');
            $('#modalImage').attr('src', src);
            $('#imageModal').modal('show');
        });

        loadMessages();

        // Charger les messages toutes les 5 secondes
        setInterval(loadMessages, 5000);
    });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
