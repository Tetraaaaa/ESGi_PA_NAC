<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link rel="stylesheet" href="css/connexion.css">
</head>
<body>

<div class="container">
  <div class="login-box">
    <div class="avatar">
      <img src="image/PCS.png" alt="Avatar">
    </div>
    <h2>Connexion</h2>
    <form method="POST" action="connexion_verif.php">
      <div class="inputBox">
        <input type="text" id="email" name="email" placeholder="Email" required>
      </div>
      <div class="inputBox">
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <div class="inputBox">
        <input type="submit" value="Se connecter">
      </div>
    </form>
  </div>
</div>

</body>
</html>
