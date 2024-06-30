<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Demande Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <style>
        .chat-box {
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .chat-input-wrapper {
            display: flex;
            align-items: center;
        }
        .chat-input {
            flex: 1;
            resize: none;
            border-radius: 15px;
            background-color: #f1f1f1;
            padding: 10px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }
        .chat-box-content {
            display: flex;
            flex-direction: column;
        }
        .message-sent {
            align-self: flex-end;
            background-color: #d1e7dd;
            padding: 5px 10px;
            border-radius: 10px;
            margin-bottom: 5px;
            max-width: 80%;
        }
        .message-received {
            align-self: flex-start;
            background-color: #f8d7da;
            padding: 5px 10px;
            border-radius: 10px;
            margin-bottom: 5px;
            max-width: 80%;
        }
        .upload-btn-wrapper {
            position: relative;
        }
        .upload-btn-wrapper .btn {
            border-radius: 50%;
            padding: 10px 15px;
        }
        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .transparent-background {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .transparent-background .list-group-item {
            background-color: rgba(255, 255, 255, 0.5);
            color: #000;
        }
        .transparent-background .btn {
            color: #000;
        }
    </style>
</head>
<body>
    <?php 
    require_once 'include/connection_db.php'; 
    session_start();
    require_once 'header.php'; 
    
    if (!isset($_GET['id_service']) || (!isset($_GET['id_location']) && !isset($_GET['id_logement']))) {
        echo '<p>Erreur : Données manquantes.</p>';
        exit;
    }

    $id_service = $_GET['id_service'];
    $id_location = isset($_GET['id_location']) ? $_GET['id_location'] : null;
    $id_logement = isset($_GET['id_logement']) ? $_GET['id_logement'] : null;

    if ($id_location) {
        $stmt = $bdd->prepare("
            SELECT FAIT_APPELLE.*, SERVICE.type, SERVICE.description, USER.nom, USER.prenom, LOCATION.id_USER
            FROM FAIT_APPELLE
            JOIN SERVICE ON FAIT_APPELLE.id_service = SERVICE.id
            JOIN LOCATION ON FAIT_APPELLE.id_location = LOCATION.id
            JOIN USER ON LOCATION.id_USER = USER.id
            WHERE FAIT_APPELLE.id_location = :id_location AND FAIT_APPELLE.id_service = :id_service
        ");
        $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
        $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    } else {
        $stmt = $bdd->prepare("
            SELECT SELECTIONNE.*, SERVICE.type, SERVICE.description, USER.nom, USER.prenom, LOGEMENT.id_USER
            FROM SELECTIONNE
            JOIN SERVICE ON SELECTIONNE.id_service = SERVICE.id
            JOIN LOGEMENT ON SELECTIONNE.id_logement = LOGEMENT.id
            JOIN USER ON LOGEMENT.id_USER = USER.id
            WHERE SELECTIONNE.id_logement = :id_logement AND SELECTIONNE.id_service = :id_service
        ");
        $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
        $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    }

    $stmt->execute();
    $demande = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$demande) {
        echo '<p>Aucune demande trouvée.</p>';
        exit;
    }
    
    $demande_user_id = $demande['id_USER'];

    // Récupérer les factures associées
    if ($id_location) {
        $stmtFactures = $bdd->prepare("
            SELECT * FROM FACTURE
            WHERE id_service = :id_service AND id_location = :id_location
        ");
        $stmtFactures->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        $stmtFactures->bindParam(':id_location', $id_location, PDO::PARAM_INT);
    } else {
        $stmtFactures = $bdd->prepare("
            SELECT * FROM FACTURE
            WHERE id_service = :id_service AND id_logement = :id_logement
        ");
        $stmtFactures->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        $stmtFactures->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
    }

    $stmtFactures->execute();
    $factures = $stmtFactures->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les interventions associées
    if ($id_location) {
        $stmtInterventions = $bdd->prepare("
            SELECT * FROM INTERVENTION_SERVICE
            WHERE id_service = :id_service AND id_location = :id_location
        ");
        $stmtInterventions->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        $stmtInterventions->bindParam(':id_location', $id_location, PDO::PARAM_INT);
    } else {
        $stmtInterventions = $bdd->prepare("
            SELECT * FROM INTERVENTION_SERVICE
            WHERE id_service = :id_service AND id_logement = :id_logement
        ");
        $stmtInterventions->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        $stmtInterventions->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
    }

    $stmtInterventions->execute();
    $interventions = $stmtInterventions->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <h2>Consulter Demande Client</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Demande #<?php echo isset($demande['id']) ? htmlspecialchars($demande['id']) : 'N/A'; ?></h5>
                        <p class="card-text"><strong>Nom du Locataire:</strong> <?php echo isset($demande['nom']) ? htmlspecialchars($demande['nom'] . ' ' . $demande['prenom']) : 'N/A'; ?></p>
                        <p class="card-text"><strong>Nom du Service:</strong> <?php echo isset($demande['description']) ? htmlspecialchars($demande['description']) : 'N/A'; ?></p>
                        <p class="card-text"><strong>Status:</strong> <?php echo isset($demande['status']) ? htmlspecialchars($demande['status']) : 'N/A'; ?></p>
                        <p class="card-text"><strong>Demande:</strong> <?php echo isset($demande['demande']) ? htmlspecialchars($demande['demande']) : 'N/A'; ?></p>
                        <p class="card-text"><strong>Type de Service:</strong> <?php echo isset($demande['type']) ? htmlspecialchars($demande['type']) : 'N/A'; ?></p>
                        <?php if ($id_location): ?>
                            <p class="card-text"><strong>ID Location:</strong> <?php echo htmlspecialchars($id_location); ?></p>
                        <?php else: ?>
                            <p class="card-text"><strong>ID Logement:</strong> <?php echo htmlspecialchars($id_logement); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($factures)): ?>
                    <h3>Factures</h3>
                    <ul class="list-group mb-4 transparent-background">
                        <?php foreach ($factures as $facture): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Montant: <?php echo htmlspecialchars($facture['montant']); ?> €</span>
                                <div>
                                    <a href="<?php echo htmlspecialchars($facture['emplacement']); ?>" class="btn btn-primary btn-sm">Voir la facture</a>
                                    <a href="payer_facture.php?id_facture=<?php echo htmlspecialchars($facture['id']); ?>" class="btn btn-success btn-sm">Payer</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($interventions)): ?>
                    <h3>Interventions</h3>
                    <ul class="list-group mb-4 transparent-background">
                        <?php foreach ($interventions as $intervention): ?>
                            <li class="list-group-item">
                                <div><strong>Date et heure de début:</strong> <?php echo htmlspecialchars($intervention['debut_intervention']); ?></div>
                                <div><strong>Date et heure de fin:</strong> <?php echo htmlspecialchars($intervention['fin_intervention']); ?></div>
                                <div><strong>Nature de l'intervention:</strong> <?php echo htmlspecialchars($intervention['nature_intervention']); ?></div>
                                <a href="<?php echo htmlspecialchars($intervention['emplacement']); ?>" class="btn btn-primary btn-sm mt-2">Télécharger l'intervention</a>
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
            const id_service = <?php echo json_encode($id_service); ?>;
            const id_location = <?php echo json_encode($id_location); ?>;
            const id_logement = <?php echo json_encode($id_logement); ?>;
            const demande_user_id = <?php echo json_encode($demande_user_id); ?>;

            function loadMessages() {
                $.ajax({
                    url: 'load_messages_client.php',
                    method: 'GET',
                    data: {
                        id_service: id_service,
                        id_location: id_location,
                        id_logement: id_logement,
                        demande_user_id: demande_user_id
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
                    $.post('send_message_client.php', {
                        message: message,
                        id_service: id_service,
                        id_location: id_location,
                        id_logement: id_logement,
                        demande_user_id: demande_user_id
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
                formData.append('id_service', id_service);
                formData.append('id_location', id_location);
                formData.append('id_logement', id_logement);
                formData.append('demande_user_id', demande_user_id);

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
