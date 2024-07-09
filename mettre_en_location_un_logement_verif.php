<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'include/db.php'; 
    
    $id_user = $_SESSION['id'];
    $unique_id = mt_rand(1, 2147483647);
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $prix = $_POST['prix'];
    if($prix < 0){
        header("Location: mettre_en_location_un_logement.php");
        exit;
    }
    $code = $_POST['code'];
    $pays = $_POST['pays'];

    $type_logement_map = [
        "appartement" => 1,
        "maison" => 2,
        "chalet" => 3,
        "villa" => 4,
        "maison_partagee" => 5
    ];


    $type_logement = $type_logement_map[$_POST['type_logement']] ?? null;  

    $horaires = isset($_POST['horaires']) ? join(', ', $_POST['horaires']) : '';
   
    $selected_times = isset($_POST['horaires']) ? $_POST['horaires'] : [];
    $first_time = !empty($selected_times) ? substr($selected_times[0], 0, 5) : '00:00'; 

    $capacite_location = $_POST['capacite_location'];
    $insertQuery = "INSERT INTO LOGEMENT (id_user, id, nom, description, adresse, ville, prix, code_postal, pays, type_bien, heure_de_contacte, capacite_location) VALUES (:id_user, :id, :nom, :description, :adresse, :ville, :prix, :code_postal, :pays, :type_bien, :heure_de_contacte, :capacite_location)";
    $insertStmt = $bdd->prepare($insertQuery);
    $insertStmt->execute([
        ':id_user' => $id_user,
        ':id' => $unique_id,
        ':nom' => $nom,
        ':description' => $description,
        ':adresse' => $adresse,
        ':ville' => $ville,
        ':prix' => $prix,
        ':code_postal' => $code,
        ':pays' => $pays,
        ':type_bien' => $type_logement,
        ':heure_de_contacte' => $first_time,
        ':capacite_location' => $capacite_location
    ]);

    if ($insertStmt) {
        
        if (!empty($_POST['caracteristiques'])) {
            foreach ($_POST['caracteristiques'] as $caracteristique_id) {
                $insertCaracQuery = "INSERT INTO CARACTERISTIQUE_LOGEMENT (id_logement, id_caracteristique) VALUES (:id_logement, :id_caracteristique)";
                $insertCaracStmt = $bdd->prepare($insertCaracQuery);
                $insertCaracStmt->execute([
                    ':id_logement' => $unique_id,
                    ':id_caracteristique' => $caracteristique_id
                ]);
            }
        }

        
        if (!empty($_FILES['photos']['name'][0])) {
            foreach ($_FILES['photos']['tmp_name'] as $key => $value) {
                $file_tmp = $_FILES['photos']['tmp_name'][$key];
                $file_name = $_FILES['photos']['name'][$key];
                $file_error = $_FILES['photos']['error'][$key];
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = uniqid('img_', true) . '.' . $file_extension;
                $file_destination = 'photo_logement/' . $new_file_name;

                if ($file_error === 0) {
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        list($width, $height) = getimagesize($file_destination);

                        $photo_id = mt_rand(1, 2147483647);

                        $sql_img = "INSERT INTO PHOTO_LOGEMENT (id, id_LOGEMENT, nom, largeur, hauteur, emplacement) VALUES (:id, :id_logement, :nom, :largeur, :hauteur, :emplacement)";
                        $stmt_img = $bdd->prepare($sql_img);
                        $stmt_img->execute([
                            ':id' => $photo_id,
                            ':id_logement' => $unique_id,
                            ':nom' => $new_file_name,
                            ':largeur' => $width,
                            ':hauteur' => $height,
                            ':emplacement' => $file_destination
                        ]);

                        if (!$stmt_img) {
                            echo "Erreur lors de l'enregistrement du fichier: " . $bdd->errorInfo();
                        }
                    } else {
                        echo "Erreur lors de l'upload du fichier.";
                    }
                } else {
                    echo "Erreur de fichier : " . $file_error;
                }
            }
        }
    } else {
        echo "Erreur lors de l'insertion : " . $bdd->errorInfo();
    }

} else {
    echo "Aucune donnée soumise.";
}

header("Location: vos_locations.php");
?>
