<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte</title>
    <link rel="stylesheet" href="css/compte.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="account-box">
            <h1>Compte <?php echo ($_SESSION['status'] == '0' ? 'Administrateur' : ($_SESSION['status'] == '1' ? 'Modérateur' : ($_SESSION['status'] == '2' ? 'Utilisateur' : ($_SESSION['status'] == '3' ? 'Entreprise non-validé' : 'Entreprise')))); ?></h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($_SESSION['nom']); ?></p>
            <p><strong>Prénom:</strong> <?php echo htmlspecialchars($_SESSION['prenom']); ?></p>
            <p><strong>Date de naissance:</strong> <?php echo htmlspecialchars($_SESSION['age']); ?></p>
            <p><strong>Statut:</strong> <?php echo ($_SESSION['status'] == '0' ? 'Administrateur' : ($_SESSION['status'] == '1' ? 'Modérateur' : ($_SESSION['status'] == '2' ? 'Utilisateur' : ($_SESSION['status'] == '3' ? 'Entreprise non-validé' : 'Entreprise')))); ?></p>
            <?php if ($_SESSION['status'] == '2'): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['photo_profil']); ?>" alt="Photo de Profil">
            <?php endif; ?>
            <?php if ($_SESSION['status'] == '4'): ?>
                <a href="mes_services.php" class="btn btn-secondary">Mes services</a>
                <a href="mettre_en_vente_un_service.php" class="btn btn-secondary">Mettre en vente un service</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("myDropdown");
            if (dropdown.style.display === "none") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }
        window.onclick = function(event) {
            if (!event.target.matches('.btn-secondary')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
