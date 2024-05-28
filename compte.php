<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit;
}

include 'include/connection_db.php';

$sql = "SELECT emplacement FROM PHOTO_PROFIL WHERE id_USER = :id";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$photo = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['photo_profil'] = $photo['emplacement'] ?? 'photo_de_profil/fond_noir.jpg'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo_profil'])) {
    $target_dir = "photo_de_profil/";
    $target_file = $target_dir . basename($_FILES["photo_profil"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["photo_profil"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo "Désolé, le fichier existe déjà.";
        $uploadOk = 0;
    }

    if ($_FILES["photo_profil"]["size"] > 500000) {
        echo "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.";
    } else {
        if (isset($photo['emplacement']) && $photo['emplacement'] != 'photo_de_profil/fond_noir.jpg') {
            unlink($photo['emplacement']);
        }

        if (move_uploaded_file($_FILES["photo_profil"]["tmp_name"], $target_file)) {
            echo "Le fichier " . htmlspecialchars(basename($_FILES["photo_profil"]["name"])) . " a été téléchargé.";
            $sql = "SELECT id FROM PHOTO_PROFIL WHERE id_USER = :id";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
            $stmt->execute();
            $existingPhoto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingPhoto) {
                $sql = "UPDATE PHOTO_PROFIL SET emplacement = :emplacement WHERE id_USER = :id";
            } else {
                $sql = "INSERT INTO PHOTO_PROFIL (id_USER, emplacement) VALUES (:id, :emplacement)";
            }
            
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':emplacement', $target_file);
            $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                $_SESSION['photo_profil'] = $target_file;
                echo "Photo de profil mise à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de la base de données : " . $stmt->errorInfo()[2];
            }
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte</title>
    <link rel="stylesheet" href="css/compte.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="account-box">
            <h1>Compte <?php echo ($_SESSION['status'] == '0' ? 'Administrateur' : ($_SESSION['status'] == '1' ? 'Modérateur' : ($_SESSION['status'] == '2' ? 'Utilisateur' : ($_SESSION['status'] == '3' ? 'Entreprise non-validé' : 'Entreprise')))); ?></h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($_SESSION['nom']); ?></p>
            <p><strong>Prénom:</strong> <?php echo htmlspecialchars($_SESSION['prenom']); ?></p>
            <p><strong>Date de naissance:</strong> <?php echo htmlspecialchars($_SESSION['age']); ?></p>
            <p><strong>Statut:</strong> <?php echo ($_SESSION['status'] == '0' ? 'Administrateur' : ($_SESSION['status'] == '1' ? 'Modérateur' : ($_SESSION['status'] == '2' ? 'Utilisateur' : ($_SESSION['status'] == '3' ? 'Entreprise non-validé' : 'Entreprise')))); ?></p>
            
            <form action="compte.php" method="post" enctype="multipart/form-data">
                <label for="photo_profil">
                    <img src="<?php echo htmlspecialchars($_SESSION['photo_profil']); ?>" alt="Photo de Profil" class="profile-pic" id="profilePic">
                </label>
                <input type="file" name="photo_profil" id="photo_profil" style="display:none;" onchange="previewImage(event)">
                <button type="submit" class="btn btn-primary mt-3">Mettre à jour la photo de profil</button>
            </form>

            <?php if ($_SESSION['status'] == '4'): ?>
                <a href="mes_services.php" class="btn btn-secondary">Mes services</a>
                <a href="mettre_en_vente_un_service.php" class="btn btn-secondary">Mettre en vente un service</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>
    <br>
    <?php include 'footer.php'; ?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profilePic');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
