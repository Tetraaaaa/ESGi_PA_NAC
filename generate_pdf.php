<?php
require('fpdf/fpdf.php');
require_once 'include/connection_db.php'; 


if (!file_exists('factures')) {
    mkdir('factures', 0777, true);
}


$nomEntreprise = $_POST['nomEntreprise'];
$factureA = $_POST['factureA'];
$numeroFacture = $_POST['numeroFacture'];
$dateFacture = $_POST['dateFacture'];
$designation = $_POST['designation'];
$montant = $_POST['montant'];
$nomTaxe = $_POST['nomTaxe'];
$montantTaxe = $_POST['montantTaxe'];
$conditionPaiement = $_POST['conditionPaiement'];
$signature = $_FILES['signature'];
$id_service = $_POST['id_service'];
$id_location = isset($_POST['id_location']) ? $_POST['id_location'] : null;
$id_logement = isset($_POST['id_logement']) ? $_POST['id_logement'] : null;


$montantTotal = 0;
for ($i = 0; $i < count($montant); $i++) {
    $montantTotal += $montant[$i] * (1 + $montantTaxe[$i] / 100);
}


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);


$pdf->Cell(0, 10, "Nom de l'entreprise: $nomEntreprise", 0, 1);
$pdf->Cell(0, 10, "Facture à: $factureA", 0, 1);
$pdf->Cell(0, 10, "Facture n°: $numeroFacture", 0, 1);
$pdf->Cell(0, 10, "Date: $dateFacture", 0, 1);
$pdf->Ln(10);


$pdf->Cell(60, 10, "Désignation", 1);
$pdf->Cell(40, 10, "Montant", 1);
$pdf->Cell(40, 10, "Nom Taxe", 1);
$pdf->Cell(40, 10, "Montant de la taxe", 1);
$pdf->Ln();

for ($i = 0; $i < count($designation); $i++) {
    $pdf->Cell(60, 10, $designation[$i], 1);
    $pdf->Cell(40, 10, number_format($montant[$i], 2), 1);
    $pdf->Cell(40, 10, $nomTaxe[$i], 1);
    $pdf->Cell(40, 10, number_format($montantTaxe[$i], 2) . '%', 1);
    $pdf->Ln();
}

$pdf->Ln(10);
$pdf->Cell(0, 10, "Condition de paiement: $conditionPaiement", 0, 1);
$pdf->Cell(0, 10, "Montant Total TTC: " . number_format($montantTotal, 2) . " EUR", 0, 1);


if ($signature['error'] == UPLOAD_ERR_OK) {
    $signaturePath = 'uploads/' . basename($signature['name']);
    move_uploaded_file($signature['tmp_name'], $signaturePath);
    $pdf->Image($signaturePath, null, null, 40, 40);
}


$uniqueId = uniqid();
$pdfFilename = 'facture_' . $uniqueId . '.pdf';
$pdfPath = 'factures/' . $pdfFilename;


$pdf->Output($pdfPath, 'F');


try {
    $stmt = $bdd->prepare("
        INSERT INTO FACTURE (montant, id_service, id_location, id_logement, emplacement)
        VALUES (:montant, :id_service, :id_location, :id_logement, :emplacement)
    ");
    $stmt->bindParam(':montant', $montantTotal, PDO::PARAM_INT);
    $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $stmt->bindParam(':id_location', $id_location, PDO::PARAM_INT);
    $stmt->bindParam(':id_logement', $id_logement, PDO::PARAM_INT);
    $stmt->bindParam(':emplacement', $pdfPath, PDO::PARAM_STR);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}


header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
readfile($pdfPath);
?>
