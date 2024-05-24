<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ma page d'accueil</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-blue">

<?php require_once 'include/connection_db.php'; ?>

<main>
    <form method="POST" class="my-login-validation" action="connexion_verif.php">
        <div class="form-group">
            <label for="email">Adresse E-Mail</label>
            <input id="email" type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <div>
            <input type="checkbox" class="form-check-input" id="stayLoggedIn" name="stayLoggedIn">
            <label class="form-check-label" for="stayLoggedIn">Rester connecté</label>
        </div>
        <button type="submit" class="btn btn-primary">Connexion</button>
        <div>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='inscription.php';">Inscription</button>
        </div>
    </form>
</main>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>

