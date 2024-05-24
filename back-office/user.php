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
        <th scope="col">Status</th>
        <th scope="col">Edit</th>
        <th scope="col">Delete</th>
    </tr>
    </thead>
    <tbody id="recherche">
    <?php
        include ("search_user.php");
    ?>
    </tbody>
</table>

<script src="js/script.js">
</script>

</body>
</html>
