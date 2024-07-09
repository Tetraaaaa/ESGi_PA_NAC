<?php
session_start();
require_once 'include/connection_db.php';
require('fpdf/fpdf.php');  

if (!isset($_GET['id_location']) || empty($_GET['id_location'])) {
    echo '<p>Erreur : Aucun ID de location fourni.</p>';
    exit;
}

$id_location = $_GET['id_location'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $descriptif = $_POST['descriptif'];
    $type = $_POST['type'];

    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Etat des Lieux');
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Date: ' . $date);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Type: ' . $type);
    $pdf->Ln();
    $pdf->MultiCell(0, 10, 'Descriptif: ' . $descriptif);
    
    
    $directory = 'etat_des_lieux/';
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    $file_name = 'etat_des_lieux_' . time() . '.pdf';
    $file_path = $directory . $file_name;

    
    $pdf->Output($file_path, 'F');

   
    $stmt = $bdd->prepare("
        INSERT INTO ETAT (id_location, date, descriptif, emplacement, type)
        VALUES (:id_location, :date, :descriptif, :emplacement, :type)
    ");
    $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':descriptif', $descriptif, PDO::PARAM_STR);
    $stmt->bindParam(':emplacement', $file_path, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->execute();

 
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    readfile($file_path);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un état des lieux</title>
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
        <h2>Créer un état des lieux</h2>
        <form action="creer_etat_des_lieux.php?id_location=<?php echo htmlspecialchars($id_location); ?>" method="POST">
            <input type="hidden" name="id_location" value="<?php echo htmlspecialchars($id_location); ?>">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="descriptif" class="form-label">Descriptif</label>
                <textarea class="form-control" id="descriptif" name="descriptif" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Entrée">Entrée</option>
                    <option value="Sortie">Sortie</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </main>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
