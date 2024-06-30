<?php
require('fpdf/makefont/makefont.php');

// Définir les chemins des fichiers
$ttfFile = 'fpdf/font/ttf/DejaVuSansCondensed.ttf';
$encFile = 'fpdf/makefont/cp1252.map'; // Utiliser l'encodage cp1252

// Utilisation de MakeFont pour générer les fichiers
MakeFont($ttfFile, $encFile);

// Déplacement des fichiers générés vers le répertoire fpdf/font
$generatedPhpFile = 'DejaVuSansCondensed.php';
$generatedZFile = 'DejaVuSansCondensed.z';

if (file_exists($generatedPhpFile) && file_exists($generatedZFile)) {
    rename($generatedPhpFile, 'fpdf/font/' . $generatedPhpFile);
    rename($generatedZFile, 'fpdf/font/' . $generatedZFile);
    echo "Les fichiers de police ont été générés et déplacés avec succès.";
} else {
    echo "Erreur : Les fichiers de police générés ne se trouvent pas dans le répertoire attendu.";
}
?>
