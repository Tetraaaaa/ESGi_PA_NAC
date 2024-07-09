<?php
include 'include/connection_db.php';

session_start(); 


$email = $_POST['email'];
$password = $_POST['password'];
$stayLoggedIn = isset($_POST['stayLoggedIn']); 


if (empty($email) || empty($password)) {
    
    header("Location: connexion.php?erreur=emptyfields");
    exit;
}


$hashedPassword = hash('sha512', $password);


$q = 'SELECT * FROM USER WHERE email = :email AND mot_de_passe = :hashedPassword';
$req = $bdd->prepare($q);
$req->execute(['email' => $email, 'hashedPassword' => $hashedPassword]);

$results = $req->fetch(PDO::FETCH_ASSOC); 

if (!$results) {
    header('location:connexion.php?erreur=CaExistePas');
    exit;
}
if ($results['status'] === '10') {
    header('location:connexion.php?erreur=Tban');
    exit;
}


$_SESSION['email'] = $email;
$_SESSION['id'] = $results['id'];
$_SESSION['nom'] = $results['nom'];
$_SESSION['age'] = $results['age'];
$_SESSION['status'] = $results['status'];
$_SESSION['prenom'] = $results['prenom'];
$_SESSION['password'] = $hashedPassword;


if ($stayLoggedIn) {
    
    $cookieLifetime = 30 * 24 * 60 * 60; 
    setcookie(session_name(), session_id(), time() + $cookieLifetime);
}

header('location:index.php');
exit;
?>
