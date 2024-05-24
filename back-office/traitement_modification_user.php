<?php
include("include/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $status = $_POST["status"];

    $q = "UPDATE USER SET nom = :nom, prenom = :prenom, email = :email, status = :status WHERE id = :user_id";
    $req = $bdd->prepare($q);

    $result = $req->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':status' => $status,
        ':user_id' => $user_id
    ]);


    if ($result) {
        header('Location: user.php?message=Utilisateur modifié avec succès');
        exit();
    } else {
        header('Location: user.php?message=Erreur lors de la mise à jour de l\'utilisateur');
        exit();
    }
} else {

    echo "Mauvaise méthode de requête.";
}
?>
