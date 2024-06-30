<?php
require_once 'include/connection_db.php';
require_once 'fpdf/fpdf.php';
session_start();
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entreprise = $_POST['entreprise'];
    $nature_intervention = $_POST['nature_intervention'];
    $debut_intervention = $_POST['debut_intervention'];
    $description_intervention = $_POST['description_intervention'];
    $problemes_rencontres = $_POST['problemes_rencontres'];
    $fin_intervention = $_POST['fin_intervention'];
    $id_service = $_POST['id_service'] ?? null;
    $id_location = $_POST['id_location'] ?? null;
    $id_logement = $_POST['id_logement'] ?? null;

    // Convert empty strings to null
    $id_location = $id_location === '' ? null : $id_location;
    $id_logement = $id_logement === '' ? null : $id_logement;

    // Créer le répertoire "intervention" s'il n'existe pas
    if (!is_dir('intervention')) {
        mkdir('intervention', 0777, true);
    }

    // Générer un nom de fichier aléatoire pour le PDF
    $pdf_filename = 'intervention_' . uniqid() . '.pdf';
    $pdf_filepath = 'intervention/' . $pdf_filename;

    // Création du PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Intervention');
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Entreprise: ' . $entreprise);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Nature de l\'intervention: ' . $nature_intervention);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Debut de l\'intervention: ' . $debut_intervention);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Description de l\'intervention: ' . $description_intervention);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Problemes rencontres: ' . $problemes_rencontres);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Fin de l\'intervention: ' . $fin_intervention);
    $pdf->Output('F', $pdf_filepath);

    // Stocker les informations dans la base de données
    $stmt = $bdd->prepare("INSERT INTO INTERVENTION_SERVICE (entreprise, nature_intervention, debut_intervention, description_intervention, problemes_rencontres, fin_intervention, id_location, id_logement, id_service, emplacement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$entreprise, $nature_intervention, $debut_intervention, $description_intervention, $problemes_rencontres, $fin_intervention, $id_location, $id_logement, $id_service, $pdf_filepath]);

    // Télécharger le PDF pour l'utilisateur
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($pdf_filepath) . '"');
    readfile($pdf_filepath);
    exit;
}
?>
