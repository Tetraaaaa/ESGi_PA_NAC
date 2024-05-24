<?php
session_start();
include 'include/db.php';


function generateRandomId() {
    return mt_rand(1, 2147483647);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $icone = isset($_FILES['icone']) ? $_FILES['icone'] : null;

    if (empty($nom)) {
        die('Le champ nom est obligatoire.');
    }

    if ($icone['size'] > 20971520) { // 20 Mo
        die('La taille du fichier ne doit pas dépasser 20 Mo.');
    }

    $uploadDir = '../icone/';
    $file_name = $icone['name'];
    $file_tmp = $icone['tmp_name'];
    $file_error = $icone['error'];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid('icon_', true) . '.' . $file_extension;
    $file_destination = $uploadDir . $new_file_name;

    if ($file_error === 0) {
        if (move_uploaded_file($file_tmp, $file_destination)) {
            list($width, $height) = getimagesize($file_destination);

            $idCaracteristique = generateRandomId();
            $idIcone = generateRandomId();

            try {
                $bdd->beginTransaction();

                $queryCaracteristique = $bdd->prepare("INSERT INTO CARACTERISTIQUE (id, nom) VALUES (:id, :nom)");
                $queryCaracteristique->execute([
                    'id' => $idCaracteristique,
                    'nom' => $nom
                ]);

                $queryIcone = $bdd->prepare("INSERT INTO ICONE (id, id_CARACTERISTIQUE, nom, largeur, hauteur, emplacement) VALUES (:id, :id_CARACTERISTIQUE, :nom, :largeur, :hauteur, :emplacement)");
                $queryIcone->execute([
                    'id' => $idIcone,
                    'id_CARACTERISTIQUE' => $idCaracteristique,
                    'nom' => $new_file_name,
                    'largeur' => $width,
                    'hauteur' => $height,
                    'emplacement' => $file_destination
                ]);

                $bdd->commit();
                header('Location: caracteristique.php');
                exit;

                echo 'Caractéristique et icône ajoutées avec succès.';
            } catch (Exception $e) {
                $bdd->rollBack();
                die('Erreur : ' . $e->getMessage());
            }
        } else {
            die('Échec du téléchargement du fichier. Veuillez vérifier les permissions du répertoire et réessayer.');
        }
    } else {
        die('Erreur de fichier : ' . $file_error);
    }
} else {
    die('Méthode de requête non valide.');
}
?>
