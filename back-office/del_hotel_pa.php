<?php

if(!isset($_GET["id"])||empty($_GET["id"])){
    echo 'Un problème est survenue';
    exit();
}
include ("verif.php");
include ('include/db.php');

$q="DELETE FROM hotel WHERE id=:id;";
$req=$bdd->prepare($q);
$request=$req->execute(["id"=>$_GET["id"]]);

if ($request) {

    echo "Élément supprimé avec succès.";
} else {

    echo "Erreur lors de la suppression de l'élément : " . $req->errorInfo()[2];
}