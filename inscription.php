<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-blue">

<div class="container center-form">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="bg-grey p-4">
        <div class="card">
          <h3 class="card-title text-center mt-3">Formulaire d'Inscription</h3>
          <div class="card-body">
            <form method="POST" action="inscription_verif.php" class="my-registration-validation">
              <div class="form-group">

                  <div class="form-group">
                  <label for="photo_profil">Photo de Profil</label>
                  <input type="file" class="form-control" id="photo_profil" name="photo_profil">
                  </div>

                <label for="email" class="form-label">Adresse E-Mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="form-group">
                <label for="Nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="Nom" name="Nom" required>
              </div>
              <div class="form-group">
                <label for="Prenom" class="form-label">Prenom</label>
                <input type="text" class="form-control" id="Prenom" name="Prenom" required>
              </div>
              <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="form-group">
                <label for="password_confirm" class="form-label">Confirmez le mot de passe</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
              </div>
              <div class="form-group">
                <label for="Age" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" id="Age" name="Age" required>
              </div>
              <div class="form-group">
                <label for="statusChoice">Êtes-vous une entreprise?</label>
                <select id="statusChoice" name="statusChoice" class="form-control" onchange="showPresentationField()">
                    <option value="">Veuillez sélectionner</option>
                    <option value="Entreprise">Oui</option>
                    <option value="Particulier">Non</option>
                </select>
              </div>
              <div class="form-group" id="presentationField" style="display: none;">
                  <label for="presentation">Présentation de l'entreprise</label>
                  <textarea id="presentation" class="form-control" name="presentation"></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-submit">S'inscrire</button>
            </form>
          </div>
        </div>
      </div>
    </div>
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
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
