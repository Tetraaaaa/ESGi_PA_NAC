<?php
include('include/db.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_USER = $_POST['id_USER'];
    $prix = $_POST['prix'];
    $validation = $_POST['validation'];
    $type_conciergerie = $_POST['type_conciergerie'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $pays = $_POST['pays'];
    $type_bien = $_POST['type_bien'];
    $type_location = $_POST['type_location'];
    $capacite_location = $_POST['capacite_location'];
    $description = $_POST['description'];
    $heure_de_contacte = $_POST['heure_de_contacte'];

    // Récupérer l'ID maximum actuel dans la table LOGEMENT et incrémenter
    $query_max_id = "SELECT MAX(id) as max_id FROM LOGEMENT";
    $stmt_max_id = $bdd->prepare($query_max_id);
    $stmt_max_id->execute();
    $row = $stmt_max_id->fetch(PDO::FETCH_ASSOC);
    $id = $row['max_id'] + 1;

    // Préparer la requête SQL pour insérer la nouvelle location
    $query = "INSERT INTO LOGEMENT (id,id_USER, prix, validation, type_conciergerie, adresse, ville, code_postal, pays, type_bien, type_location, capacite_location, description, heure_de_contacte) VALUES (:id,:id_USER, :prix, :validation, :type_conciergerie, :adresse, :ville, :code_postal, :pays, :type_bien, :type_location, :capacite_location, :description, :heure_de_contacte)";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':id_USER', $id_USER);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':validation', $validation);
    $stmt->bindParam(':type_conciergerie', $type_conciergerie);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':code_postal', $code_postal);
    $stmt->bindParam(':pays', $pays);
    $stmt->bindParam(':type_bien', $type_bien);
    $stmt->bindParam(':type_location', $type_location);
    $stmt->bindParam(':capacite_location', $capacite_location);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':heure_de_contacte', $heure_de_contacte);

    // Exécuter la requête
    if ($stmt->execute()) {
        header("Location: ajout_location_succes.php");
        exit();
    } else {
        header("Location: ajout_location_erreur.php");
        exit();
    }
} else {
    header("Location: ajout_location_erreur.php");
    exit();
}

