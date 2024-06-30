<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Intervention</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <style>
        .form-container {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .half-width {
            width: 50%;
        }
        .tall-input {
            height: 3rem;
        }
        .wide-input {
            width: 80%;
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
    ?>
    <main class="container mt-4">
        <h2>Créer une Intervention</h2>
        <div class="form-container">
            <form action="soumettre_intervention.php" method="post">
                <input type="hidden" name="id_service" value="<?php echo htmlspecialchars($id_service); ?>">
                <input type="hidden" name="id_location" value="<?php echo htmlspecialchars($id_location); ?>">
                <input type="hidden" name="id_logement" value="<?php echo htmlspecialchars($id_logement); ?>">
                <div class="mb-3">
                    <label for="entreprise" class="form-label">Entreprise</label>
                    <input type="text" class="form-control" id="entreprise" name="entreprise" required>
                </div>
                <div class="mb-3">
                    <label for="nature_intervention" class="form-label">Nature de l'intervention</label>
                    <input type="text" class="form-control" id="nature_intervention" name="nature_intervention" required>
                </div>
                <div class="mb-3">
                    <label for="debut_intervention" class="form-label">Date et heure du début de l'intervention</label>
                    <input type="datetime-local" class="form-control" id="debut_intervention" name="debut_intervention" required>
                </div>
                <div class="mb-3">
                    <label for="description_intervention" class="form-label">Description de l'intervention</label>
                    <textarea class="form-control" id="description_intervention" name="description_intervention" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="problemes_rencontres" class="form-label">Problèmes rencontrés</label>
                    <textarea class="form-control" id="problemes_rencontres" name="problemes_rencontres" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="fin_intervention" class="form-label">Date et heure de la fin de l'intervention</label>
                    <input type="datetime-local" class="form-control" id="fin_intervention" name="fin_intervention" required>
                </div>
                <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>
        </div>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
