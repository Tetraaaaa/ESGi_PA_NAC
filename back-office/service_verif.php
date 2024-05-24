<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Utilisateur</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
session_start();

// Vérifier si l'utilisateur est connecté et si son statut est celui d'un administrateur
if (!isset($_SESSION['status']) || $_SESSION['status'] != 0) {
    // Rediriger l'utilisateur vers la page d'accueil ou de connexion si ce n'est pas un administrateur
    header("Location: index.php");
    exit; // Important pour arrêter l'exécution du script après la redirection
}
include 'header.php';
include 'verif.php';
?>
<body class="container text-center">
<?php
include ("include/db.php");
if (isset($_GET['message']) && !empty($_GET['message'])){
    echo '<div class="alert alert-danger" role="alert">
                '.$_GET['message'].'
            </div>
            ';
}
echo "<h1>User</h1>";

?>



<div class="form-group">
    <input type="text" class="form-control" onkeyup="search()" id="search" placeholder="Rechercher par email">
</div>



<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">id</th>
        <th scope="col">Nom</th>
        <th scope="col">Prénom</th>
        <th scope="col">email</th>
        <th scope="col">Presentation</th>
    
        <th scope="col">Delete</th>
        <!--<th scope="col">Voir</th>-->
    </tr>
    </thead>
    <tbody id="recherche">
    <?php
    include 'include/db.php';

    // Vérifier si un critère de recherche a été fourni
    if (isset($_GET["search"]) && !empty($_GET["search"])) {
        // Préparer et exécuter la requête avec un critère de recherche
        $q = "SELECT * FROM USER WHERE email LIKE :email AND status = 3";
        $stmt = $bdd->prepare($q);
        $stmt->execute([':email' => '%' . $_GET["search"] . '%']);
        $list_user = $stmt->fetchAll();
    } else {
        // Exécuter une requête sans critère de recherche spécifique
        $q = "SELECT * FROM USER WHERE status = 3";
        $stmt = $bdd->query($q);
        $list_user = $stmt->fetchAll();
    }
    
    // Afficher les données dans un tableau HTML
    echo '<table class="table">';
    foreach ($list_user as $user) {
        echo '<tr>
                <td>#' . htmlspecialchars($user['id']) . '</td>
                <td>' . htmlspecialchars($user['nom']) . '</td>
                <td>' . htmlspecialchars($user['prenom']) . '</td>
                <td>' . htmlspecialchars($user['email']) . '</td>
                <td>' . htmlspecialchars($user['presentation']) . '</td>
                <td>NULL</td>
                <td><button type="button" class="btn btn-danger" onclick="del_user(' . htmlspecialchars($user['id']) . ')">Delete</button></td>
                <td><button type="button" class="btn btn-success" onclick="updateUserStatus(\'' . htmlspecialchars($user['id']) . '\')">Accept</button></td>
              </tr>';
    }
    echo '</table>';
?>

<script>
function del_user(userId) {
    var confirmDelete = confirm('Are you sure you want to delete this user?');
    if (confirmDelete) {
        window.location.href = 'delete_user.php?id=' + userId;
    }
}

function updateUserStatus(userId) {
    var confirmAction = confirm('Are you sure you want to accept this user and change the status to 4?');
    if (confirmAction) {
        window.location.href = 'update_status.php?id=' + userId;
    }
}
</script>


<script src="js/script.js">
</script>

</body>
</html>
