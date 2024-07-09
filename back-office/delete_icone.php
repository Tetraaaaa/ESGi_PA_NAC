<?php
session_start();
//require_once 'include/connection_db.php';
include 'include/db.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 0) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $bdd->prepare("DELETE FROM ICONE_FILTRE WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: filtre_icone.php");
    exit;
} else {
    header("Location: filtre_icone.php");
    exit;
}
?>
