<?php
session_start();
require_once 'include/connection_db.php';

$code = $_POST['code'];

if ($code != $_SESSION['validationCode'] || time() > $_SESSION['validationCodeExpires']) {
    header('Location: code_validation_email.php?erreur=codeInvalide');
    exit;
}

$email = $_SESSION['email'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$password = $_SESSION['password'];
$date_naissance = $_SESSION['date_naissance'];
$statusChoice = $_SESSION['statusChoice'];
$presentation = $_SESSION['presentation'];

try {
    $status = ($statusChoice === 'Entreprise') ? 3 : 2;
    $hashedPassword = hash('sha512', $password);


    do {
        $id = mt_rand(1, 2147483647);
        $query = $bdd->prepare("SELECT COUNT(*) FROM USER WHERE id = :id");
        $query->execute([':id' => $id]);
        $count = $query->fetchColumn();
    } while ($count > 0);

    $insertQuery = "INSERT INTO USER (id, nom, prenom, age, email, mot_de_passe, status, presentation) VALUES (:id, :nom, :prenom, :age, :email, :mot_de_passe, :status, :presentation)";
    $insertStmt = $bdd->prepare($insertQuery);
    $insertStmt->execute([
        ':id' => $id,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':age' => $date_naissance,
        ':email' => $email,
        ':mot_de_passe' => $hashedPassword,
        ':status' => $status,
        ':presentation' => $presentation
    ]);


    $_SESSION['email'] = $email;
    $_SESSION['id'] = $id;
    $_SESSION['nom'] = $nom;
    $_SESSION['age'] = $date_naissance;
    $_SESSION['status'] = $status;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['password'] = $hashedPassword;

    unset($_SESSION['validationCode']);
    unset($_SESSION['validationCodeExpires']);
    unset($_SESSION['statusChoice']);
    unset($_SESSION['presentation']);

    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    header('Location: code_validation_email.php?erreur=erreurInscription&info=' . urlencode($e->getMessage()));
    exit;
}
?>
