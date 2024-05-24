<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Service</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet_details.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../Js/script.js" async></script>
    <style>
        /* Ajoutez ici votre propre CSS pour personnaliser la page */
    </style>
</head>
<body class="container text-center">
<?php
include 'header.php';
include 'verif.php';
include 'include/db.php';

$q = 'SELECT * FROM CARACTERISTIQUE';
$req = $bdd->query($q);
$services = $req->fetchAll();

if (count($services) > 0) { ?>
    <div class="container mt-5">
        <h2>Liste des Caractéristiques :</h2>
        <table class="table">
            <thead>
            <tr>
                <th>ID de la caractéristique</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($services as $service) { ?>
                <tr>
                    <td><?php echo $service['id']; ?></td>
                    <td><?php echo $service['nom']; ?></td>
                    <td>
                        <form action="delete_caracteristique.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette caractéristique ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="container mt-5">
        <p>Aucune caractéristique trouvée.</p>
    </div>
<?php } ?>

<div class="container mt-5">
    <h2>Ajouter une Caractéristique :</h2>
    <form action="caracteristique_verif.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la Caractéristique:</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="icone">Icône :</label>
            <input type="file" class="form-control" id="icone" name="icone" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
</body>
</html>
