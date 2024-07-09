<?php
session_start();
if (!isset($_SESSION['validationCode'])) {
    header('Location: inscription.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation du code</title>
    <link rel="stylesheet" href="css/connexion.css">
</head>
<body>
<div class="container">
  <div class="login-box">
    <div class="avatar">
      <img src="image/PCS.png" alt="Avatar">
    </div>
    <h2>Validation du code</h2>
    <form action="validation_code_email.php" method="post">
      <div class="inputBox">
        <input type="text" id="code" name="code" placeholder="Code de validation" required>
      </div>
      <div class="inputBox">
        <input type="submit" value="Valider">
      </div>
    </form>
  </div>
</div>

</body>
</html>
