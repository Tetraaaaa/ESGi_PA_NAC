<?php
session_start();
require_once 'include/connection_db.php';

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

$id_service = $_GET['id_service'] ?? null;
$id_location = $_GET['id_location'] ?? null;
$id_logement = $_GET['id_logement'] ?? null;
$user_id = $_SESSION['id'];

if (!$id_service || (!$id_location && !$id_logement)) {
    header('Location: index.php');
    exit;
}

// Vérifier que l'utilisateur possède bien le service
$stmtService = $bdd->prepare("SELECT id_USER FROM SERVICE WHERE id = :id_service");
$stmtService->bindParam(':id_service', $id_service, PDO::PARAM_INT);
$stmtService->execute();
$service = $stmtService->fetch(PDO::FETCH_ASSOC);

if (!$service || $service['id_USER'] != $user_id) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Facture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/creer_facture.css">
</head>
<body>
    <?php 
    require_once 'header.php'; 
    ?>
    <main class="container mt-4">
        <h2>Créer une Facture</h2>
        <div class="form-container">
            <form action="generate_pdf.php" method="post" enctype="multipart/form-data">
                <div class="mb-3 half-width">
                    <label for="nomEntreprise" class="form-label">Nom de l'entreprise</label>
                    <input type="text" class="form-control tall-input" id="nomEntreprise" name="nomEntreprise" required>
                </div>
                <div class="mb-3 d-flex justify-content-between align-items-start">
                    <div class="half-width">
                        <label for="factureA" class="form-label">Facturé à</label>
                        <input type="text" class="form-control tall-input" id="factureA" name="factureA" required>
                    </div>
                    <div class="d-flex flex-column align-items-start">
                        <div class="mb-3">
                            <label for="numeroFacture" class="form-label">Facture n°</label>
                            <input type="number" class="form-control wide-input" id="numeroFacture" name="numeroFacture" pattern="\d+" title="Veuillez entrer uniquement des chiffres." required>
                        </div>
                        <div class="mb-3">
                            <label for="dateFacture" class="form-label">Date</label>
                            <input type="date" class="form-control wide-input" id="dateFacture" name="dateFacture" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="designation" class="form-label">Désignation</label>
                    <input type="text" class="form-control" id="designation" name="designation[]" required>
                </div>
                <div class="mb-3 inline-fields">
                    <div class="inline-field">
                        <label for="montant" class="form-label">Montant</label>
                        <input type="number" class="form-control" id="montant" name="montant[]" step="0.01" min="0" required>
                    </div>
                    <div class="inline-field">
                        <label for="nomTaxe" class="form-label">Nom Taxe</label>
                        <input type="text" class="form-control" id="nomTaxe" name="nomTaxe[]" required>
                    </div>
                    <div class="inline-field">
                        <label for="montantTaxe" class="form-label">Montant de la taxe</label>
                        <input type="number" class="form-control" id="montantTaxe" name="montantTaxe[]" step="0.01" min="0" required>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" id="ajouterArticle">Ajouter</button>
                <div class="mb-3">
                    <label for="conditionPaiement" class="form-label">Condition de paiement</label>
                    <input type="text" class="form-control" id="conditionPaiement" name="conditionPaiement" required>
                </div>
                <div class="mb-3">
                    <label for="signature" class="form-label">Signature</label>
                    <input type="file" class="form-control" id="signature" name="signature" accept="image/*">
                </div>
                <input type="hidden" name="id_service" value="<?php echo isset($_GET['id_service']) ? htmlspecialchars($_GET['id_service']) : ''; ?>">
                <input type="hidden" name="id_location" value="<?php echo isset($_GET['id_location']) ? htmlspecialchars($_GET['id_location']) : ''; ?>">
                <input type="hidden" name="id_logement" value="<?php echo isset($_GET['id_logement']) ? htmlspecialchars($_GET['id_logement']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Soumettre</button>
            </form>
        </div>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ajouterArticle').click(function() {
                const articleFields = `
                <div class="mb-3">
                    <label for="designation" class="form-label">Désignation</label>
                    <input type="text" class="form-control" name="designation[]" required>
                </div>
                <div class="mb-3 inline-fields">
                    <div class="inline-field">
                        <label for="montant" class="form-label">Montant</label>
                        <input type="number" class="form-control" name="montant[]" step="0.01" min="0" required>
                    </div>
                    <div class="inline-field">
                        <label for="nomTaxe" class="form-label">Nom Taxe</label>
                        <input type="text" class="form-control" name="nomTaxe[]" required>
                    </div>
                    <div class="inline-field">
                        <label for="montantTaxe" class="form-label">Montant de la taxe</label>
                        <input type="number" class="form-control" name="montantTaxe[]" step="0.01" min="0" required>
                    </div>
                </div>`;
                $(articleFields).insertBefore('#ajouterArticle');
            });
        });
    </script>
</body>
</html>
