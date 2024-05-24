<?php

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo 'Un problème est survenu';
    exit();
}

include("verif.php");
include('include/db.php');

$q_service = "DELETE FROM SERVICE WHERE id_USER=:id";
$req_service = $bdd->prepare($q_service);
$req_service->execute(["id" => $_GET["id"]]);

$q_user = "DELETE FROM USER WHERE id=:id";
$req_user = $bdd->prepare($q_user);
$request = $req_user->execute(["id" => $_GET["id"]]);

if ($request) {
    echo "Élément supprimé avec succès.";
} else {
    echo "Erreur lors de la suppression de l'élément : " . $req_user->errorInfo()[2];
}
?>
