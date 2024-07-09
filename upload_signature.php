<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['signature'])) {
    $target_dir = "signatures/";
    $target_file = $target_dir . basename($_FILES["signature"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    $check = getimagesize($_FILES["signature"]["tmp_name"]);
    if ($check !== false) {
        echo "Le fichier est une image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }


    if (file_exists($target_file)) {
        echo "Désolé, le fichier existe déjà.";
        $uploadOk = 0;
    }


    if ($_FILES["signature"]["size"] > 500000) {
        echo "Désolé, votre fichier est trop grand.";
        $uploadOk = 0;
    }


    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        $uploadOk = 0;
    }


    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.";

    } else {
        if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file)) {
            echo "Le fichier " . htmlspecialchars(basename($_FILES["signature"]["name"])) . " a été téléchargé.";
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}
?>
