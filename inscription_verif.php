<?php
session_start();
ob_start();
require_once 'include/connection_db.php';
require_once 'send_valid_email.php';

$email = $_POST['email'];
$nom = $_POST['Nom'];
$prenom = $_POST['Prenom'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$date_naissance = $_POST['Age'];
$statusChoice = $_POST['status'];
$presentation = $_POST['presentation'];

$dateNaissanceObj = new DateTime($date_naissance);
if ($dateNaissanceObj === false) {
    echo "Invalid date format";
    header('Location: inscription.php?erreur=formatDateInvalide');
    exit;
}

if ($password != $password_confirm) {
    echo "Passwords do not match";
    header('Location: inscription.php?erreur=passwordNonIdentiques');
    exit;
}

$pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
if (!preg_match($pattern, $password)) {
    echo "Password is too weak";
    header('Location: inscription.php?erreur=passwordFaible');
    exit;
}

$query = $bdd->prepare('SELECT * FROM USER WHERE email = :email');
$query->execute(['email' => $email]);
if ($query->fetch()) {
    echo "Email already exists";
    header('Location: inscription.php?erreur=emailExistant');
    exit;
}

if ($_FILES['photo_profil']['size'] > 0) {
    $targetDir = "uploads/"; 
    $fileName = basename($_FILES['photo_profil']['name']);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'jpeg', 'png');
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $targetFilePath)) {
            $photo_profil = $targetFilePath;
        } else {
            echo "Photo upload error";
            header('Location: inscription.php?erreur=erreurTelechargementPhoto');
            exit;
        }
    } else {
        echo "File type not supported";
        header('Location: inscription.php?erreur=typeFichierNonSupporte');
        exit;
    }
} else {
    $photo_profil = NULL;
}

$validationCode = rand(100000, 999999);
$_SESSION['validationCode'] = $validationCode;
$_SESSION['email'] = $email;
$_SESSION['nom'] = $nom;
$_SESSION['prenom'] = $prenom;
$_SESSION['password'] = $password;
$_SESSION['date_naissance'] = $date_naissance;
$_SESSION['statusChoice'] = $statusChoice;
$_SESSION['presentation'] = $presentation;
$_SESSION['photo_profil'] = $photo_profil;
$_SESSION['validationCodeExpires'] = time() + 300;

sendValidationEmail($email, $validationCode);


echo "Validation email sent, redirecting to code_validation_email.php..."; 


header('Location: /code_validation_email.php');
exit;

echo "If you see this, redirection did not work"; 
ob_end_flush();
?>
