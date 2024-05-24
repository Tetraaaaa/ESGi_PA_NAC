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
<body class="container text-center">
    <?php
    include 'verif.php';
    include 'header.php';
    include ("include/db.php");
    if (isset($_GET['message']) && !empty($_GET['message'])){
        echo '<div class="alert alert-danger" role="alert">
        '.$_GET['message'].'
            </div>
            ';
    }
?>
    <main>
        <form method="POST" action="add_caractéristique.php" class="my-registration-validation">
        <div class="form-group">
            <label for="Nom de la caractéristique à ajouter" class="form-label">Nom de la caractéristique à ajouter</label>
            <input type="text" class="form-control" id="nom_caractéristique" name="nom_caractéristique" required>
        </div>
        <div class="form-group">
                <label for="icon">icon de la caractéristique</label>
                <input type="file" class="form-control" id="icon" name="icon" accept="image/*" multiple>
              </div>
        </form>
    </main>

</body>
<script src="js/script.js">
</script>
</html>
