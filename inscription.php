<?php
session_start();
include_once 'init.php';
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $translations['Inscription']; ?></title>
  <link rel="stylesheet" href="css/inscription.css">
</head>
<body>

<div class="container">
  <div class="login-box">
    <div class="avatar">
      <img src="image/PCS.png" alt="Avatar">
    </div>
    <h2><?php echo $translations['Inscription']; ?></h2>
    <form method="POST" action="inscription_verif.php" enctype="multipart/form-data">
      <div class="inputBox">
        <input type="email" id="email" name="email" placeholder="<?php echo $translations['Adresse E-Mail']; ?>" required>
      </div>
      <div class="inputBox">
        <input type="text" id="Nom" name="Nom" placeholder="<?php echo $translations['Nom']; ?>" required>
      </div>
      <div class="inputBox">
        <input type="text" id="Prenom" name="Prenom" placeholder="<?php echo $translations['Prenom']; ?>" required>
      </div>
      <div class="inputBox">
        <input type="password" id="password" name="password" placeholder="<?php echo $translations['Mot de passe']; ?>" required>
      </div>
      <div class="inputBox">
        <input type="password" id="password_confirm" name="password_confirm" placeholder="<?php echo $translations['Confirmez le mot de passe']; ?>" required>
      </div>
      <div class="inputBox">
        <input type="date" id="Age" name="Age" placeholder="<?php echo $translations['Date de naissance']; ?>" required>
      </div>
      <div class="inputBox">
        <select id="statusChoice" name="statusChoice" onchange="showPresentationField()">
          <option value=""><?php echo $translations['Veuillez sélectionner']; ?></option>
          <option value="Entreprise"><?php echo $translations['Entreprise']; ?></option>
          <option value="Particulier"><?php echo $translations['Particulier']; ?></option>
        </select>
      </div>
      <div class="inputBox" id="presentationField" style="display: none;">
        <textarea id="presentation" name="presentation" placeholder="<?php echo $translations['Présentation de l\'entreprise']; ?>"></textarea>
      </div>
      <div class="inputBox">
        <input type="submit" value="<?php echo $translations['S\'inscrire']; ?>">
      </div>
    </form>
  </div>
</div>

<script>
function showPresentationField() {
  var selection = document.getElementById('statusChoice').value;
  var presentationField = document.getElementById('presentationField');
  if (selection === 'Entreprise') {
    presentationField.style.display = 'block';
  } else {
    presentationField.style.display = 'none';
  }
}

document.querySelector('.avatar-btn').addEventListener('click', function() {
  document.getElementById('photo_profil').click();
});
</script>

</body>
</html>
