<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ajouter une Location</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
</head>

<body class="container text-center">
    <h1>Ajouter une Location</h1>
    <form action="traitement_ajout_location.php" method="POST">
        <div class="mb-3">
            <label for="id_USER" class="form-label">ID de l'Utilisateur</label>
            <input type="text" class="form-control" id="id_USER" name="id_USER" required>
        </div>
        <div class="mb-3">
            <label for="prix" class="form-label">Prix</label>
            <input type="text" class="form-control" id="prix" name="prix" required>
        </div>
        <div class="mb-3">
            <label for="validation" class="form-label">Validation</label>
            <input type="text" class="form-control" id="validation" name="validation" required>
        </div>
        <div class="mb-3">
            <label for="type_conciergerie" class="form-label">Type de Conciergerie</label>
            <input type="text" class="form-control" id="type_conciergerie" name="type_conciergerie" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>
        <div class="mb-3">
            <label for="code_postal" class="form-label">Code Postal</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal" required>
        </div>
        <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <input type="text" class="form-control" id="pays" name="pays" required>
        </div>
        <div class="mb-3">
            <label for="type_bien" class="form-label">Type de Bien</label>
            <input type="text" class="form-control" id="type_bien" name="type_bien" required>
        </div>
        <div class="mb-3">
            <label for="type_location" class="form-label">Type de Location</label>
            <input type="text" class="form-control" id="type_location" name="type_location" required>
        </div>
        <div class="mb-3">
            <label for="capacite_location" class="form-label">Capacité de la Location</label>
            <input type="text" class="form-control" id="capacite_location" name="capacite_location" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="heure_de_contacte" class="form-label">Heure de Contact</label>
            <input type="time" class="form-control" id="heure_de_contacte" name="heure_de_contacte" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Location</button>
    </form>
</body>

</html>
