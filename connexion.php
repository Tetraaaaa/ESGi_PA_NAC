<?php
session_start();
include_once 'init.php';
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $translations['Connexion']; ?></title>
  <link rel="stylesheet" href="css/connexion.css">
</head>
<body>

<div class="container">
  <div class="login-box">
    <div class="avatar">
      <img src="image/PCS.png" alt="Avatar">
    </div>
    <h2><?php echo $translations['Connexion']; ?></h2>
    <form method="POST" action="connexion_verif.php">
      <div class="inputBox">
        <input type="text" id="email" name="email" placeholder="<?php echo $translations['Adresse E-Mail']; ?>" required>
      </div>
      <div class="inputBox">
        <input type="password" id="password" name="password" placeholder="<?php echo $translations['Mot de passe']; ?>" required>
      </div>
      <div class="inputBox">
        <input type="submit" value="<?php echo $translations['Se connecter']; ?>">
      </div>
    </form>
  </div>
</div>

</body>
</html>
