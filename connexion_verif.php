<?php
include 'include/connection_db.php';

session_start(); // Assurez-vous que session_start() est appelé au début

// Initialisation des variables
$email = $_POST['email'];
$password = $_POST['password'];
$stayLoggedIn = isset($_POST['stayLoggedIn']); // Vérifie si l'utilisateur a coché "Rester connecté"

// Vérifiez si les champs email et mot de passe ont été remplis
if (empty($email) || empty($password)) {
    // Redirection vers la page de connexion avec un message d'erreur
    header("Location: connexion.php?erreur=emptyfields");
    exit;
}

// Hasher le mot de passe pour la comparaison, supposer que vous stockez des mots de passe hashés
$hashedPassword = hash('sha512', $password);

// Si email et mdp existe en bdd : redirection
$q = 'SELECT * FROM USER WHERE email = :email AND mot_de_passe = :hashedPassword';
$req = $bdd->prepare($q);
$req->execute(['email' => $email, 'hashedPassword' => $hashedPassword]);

$results = $req->fetch(PDO::FETCH_ASSOC); // résultats sont mis dans un tableau

if (!$results) {
    header('location:connexion.php?erreur=CaExistePas');
    exit;
}
if ($results['status'] === '10') {
    header('location:connexion.php?erreur=Tban');
    exit;
}

// Stockage des informations dans la session
$_SESSION['email'] = $email;
$_SESSION['id'] = $results['id'];
$_SESSION['nom'] = $results['nom'];
$_SESSION['age'] = $results['age'];
$_SESSION['status'] = $results['status'];
$_SESSION['prenom'] = $results['prenom'];
$_SESSION['password'] = $hashedPassword;

// Gestion de rester connecté
if ($stayLoggedIn) {
    // Prolonger la durée du cookie de session pour rester connecté plus longtemps
    $cookieLifetime = 30 * 24 * 60 * 60; // 30 jours, par exemple
    setcookie(session_name(), session_id(), time() + $cookieLifetime);
}

header('location:index.php');
exit;
?>
