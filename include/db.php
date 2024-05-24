<?php
$servername = "91.134.89.127";
$username = "admin";
$password = "SuDGhKBjzs9d";
$dbname = "NAC_BDD";

try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur de PDO sur exception
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
