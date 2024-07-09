<?php
session_start();
require 'include/db.php';
ini_set('display_errors', 1);

if (!isset($_SESSION['selectedDates'], $_SESSION['logementId'], $_SESSION['totalCost'], $_SESSION['paymentIntentId'])) {
    header("Location: reservation_error.php?error=sessionData");
    exit;
}

$selectedDates = $_SESSION['selectedDates'];
$id_LOGEMENT = $_SESSION['logementId'];
$id_USER = $_SESSION['id'];

if (empty($selectedDates)) {
    header("Location: reservation_error.php?error=nodates");
    exit;
}

$isValidDates = array_reduce($selectedDates, function ($carry, $date) {
    return $carry && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
}, true);

if (!$isValidDates) {
    header("Location: reservation_error.php?error=invalidDates");
    exit;
}

sort($selectedDates);
$date_debut = $selectedDates[0];
$date_fin = end($selectedDates);

$id = mt_rand(1000000000, 2147483647);

$query = $bdd->prepare("INSERT INTO LOCATION (id, id_LOGEMENT, id_USER, date_debut, date_fin) VALUES (:id, :id_LOGEMENT, :id_USER, :date_debut, :date_fin)");
$success = $query->execute([
    ':id' => $id,
    ':id_LOGEMENT' => $id_LOGEMENT,
    ':id_USER' => $id_USER,
    ':date_debut' => $date_debut,
    ':date_fin' => $date_fin
]);

if ($success && $query->rowCount() > 0) {
    foreach ($selectedDates as $date) {
        $insertDate = $bdd->prepare("INSERT INTO DATE_RESERVE (id, id_LOCATION, id_LOGEMENT, date) VALUES (:id, :id_LOCATION, :id_LOGEMENT, :date)");
        $dateId = mt_rand(1000000000, 2147483647);
        $insertDate->execute([
            ':id' => $dateId,
            ':id_LOCATION' => $id,
            ':id_LOGEMENT' => $id_LOGEMENT,
            ':date' => $date
        ]);
    }
    header("Location: index.php");
    exit;
} else {
    header("Location: reservation_error.php");
    exit;
}
?>
