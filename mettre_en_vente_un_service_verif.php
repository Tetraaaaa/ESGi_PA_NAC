<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'include/connection_db.php';

    // Récupération des données du formulaire
    $type = $_POST['type'] ?? null;
    $presentation = $_POST['presentation'] ?? null;
    $dates_disponibles = $_POST['dates_disponibles'] ?? '';
    $departements = $_POST['departements'] ?? [];
    $dates = explode(',', $dates_disponibles); // Transformation de la chaîne de dates en tableau
    $id = mt_rand(1, 2147483647);
    try {
        $query = "INSERT INTO SERVICE (id, type, description, id_USER) VALUES (?, ?, ?, ?)";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id, $type, $presentation,$_SESSION['id']]);

        $serviceId = $bdd->lastInsertId();

        foreach ($dates as $date) {
            $date = trim($date); 
            $queryDate = "INSERT INTO CALENDRIER (date, id_SERVICE) VALUES (?, ?)";
            $stmtDate = $bdd->prepare($queryDate);
            $stmtDate->execute([$date, $id]);
        }
        foreach ($departements as $departement) {
            $queryDept = "INSERT INTO INTERVENTION (id_SERVICE, id_DEPARTEMENT) VALUES (?, ?)";
            $stmtDept = $bdd->prepare($queryDept);
            $stmtDept->execute([$id, $departement]);
        }

    } catch (PDOException $e) {;
    }
    header("Location: compte.php");
    exit;
} else {
    // Redirection si la page est accédée sans soumission de formulaire
    header("Location: mettre_en_vente_un_service.php");
    exit;
}
?>
