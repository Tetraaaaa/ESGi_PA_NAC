<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="css/inscription.css">
</head>
<body>

<div class="container">
  <div class="login-box">
    <div class="avatar">
      <img src="image/PCS.png" alt="Avatar">
    </div>
    <h2>Inscription</h2>
    <form method="POST" action="inscription_verif.php" enctype="multipart/form-data">
      <div class="inputBox">
        <label for="photo_profil" class="avatar-btn">Avatar</label>
        <input type="file" id="photo_profil" name="photo_profil" style="display: none;">
      </div>
      <div class="inputBox">
        <input type="email" id="email" name="email" placeholder="Adresse E-Mail" required>
      </div>
      <div class="inputBox">
        <input type="text" id="Nom" name="Nom" placeholder="Nom" required>
      </div>
      <div class="inputBox">
        <input type="text" id="Prenom" name="Prenom" placeholder="Prenom" required>
      </div>
      <div class="inputBox">
        <input type="password" id="password" name="password" placeholder="Mot de passe" required>
      </div>
      <div class="inputBox">
        <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirmez le mot de passe" required>
      </div>
      <div class="inputBox">
        <input type="date" id="Age" name="Age" placeholder="Date de naissance" required>
      </div>
      <div class="inputBox">
        <select id="statusChoice" name="statusChoice" onchange="showPresentationField()">
          <option value="">Veuillez sélectionner</option>
          <option value="Entreprise">Oui</option>
          <option value="Particulier">Non</option>
        </select>
      </div>
      <div class="inputBox" id="presentationField" style="display: none;">
        <textarea id="presentation" name="presentation" placeholder="Présentation de l'entreprise"></textarea>
      </div>
      <div class="inputBox">
        <input type="submit" value="S'inscrire">
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
