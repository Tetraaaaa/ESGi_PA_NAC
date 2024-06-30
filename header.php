<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'init.php';
// Inclure la connexion à la base de données
include 'include/connection_db.php';

// Récupérer les informations de l'utilisateur depuis la base de données si elles ne sont pas déjà dans la session
if (isset($_SESSION['id']) && !isset($_SESSION['photo_profil'])) {
    $sql = "SELECT P.emplacement AS photo_profil 
            FROM USER U
            LEFT JOIN PHOTO_PROFIL P ON U.id = P.id_USER
            WHERE U.id = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['photo_profil'] = $user['photo_profil'] ?? 'photo_de_profil/fond_noir.jpg';
}
?>

<header class="container-fluid p-3 border-bottom">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <img src="image/PCS.png" alt="Logo">
        </div>
        
        <div class="nav-links">
            <a href="index.php" class="btn text-decoration-none"><?php echo $translations['trouver_une_location'] ?? 'Trouver une location'; ?></a>
            <a href="vos_locations.php" class="btn text-decoration-none"><?php echo $translations['Vos locations'] ?? 'Vos locations'; ?></a>
        </div>

        <div class="language-switcher">
            <a href="?lang=fr"><img src="lang/france.png" alt="Français" style="height: 40px;"></a>
            <a href="?lang=en"><img src="lang/royaume-uni.png" alt="English" style="height: 40px;"></a>
        </div>

        <?php if (isset($_SESSION['id'])): ?>
            <div class="profile-dropdown">
                <button onclick="toggleDropdown()" class="btn profile-button">
                    <img src="<?php echo htmlspecialchars($_SESSION['photo_profil']); ?>" alt="Profile Picture" class="profile-pic">
                    <div class="menu-icon">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="compte.php"><?php echo $translations['Mon compte'] ?? 'Mon compte'; ?></a>
                    <a href="grade.php"><?php echo $translations['Grade'] ?? 'Grade'; ?></a>
                    <a href="voyages.php"><?php echo $translations['Mes voyages'] ?? 'Mes voyages'; ?></a>
                    <?php if ($_SESSION['status'] == "4"): ?>
                        <a href="mes_services.php"><?php echo $translations['Mes services'] ?? 'Mes services'; ?></a>
                        <a href="mes_demandes.php"><?php echo $translations['Mes demandes'] ?? 'Mes demandes'; ?></a>
                        <a href="mettre_en_vente_un_service.php"><?php echo $translations['Mettre en vente un service'] ?? 'Mettre en vente un service'; ?></a>
                    <?php endif; ?>
                    <?php if ($_SESSION['status'] == "0"): ?>
                        <a href="back-office/"><?php echo $translations['Admin'] ?? 'Admin'; ?></a>
                    <?php endif; ?>
                    <a href="logout.php"><?php echo $translations['Déconnexion'] ?? 'Déconnexion'; ?></a>
                </div>
            </div>
        <?php else: ?>
            <div>
                <button type="button" class="btn" onclick="window.location.href='connexion.php';"><?php echo $translations['Connexion'] ?? 'Connexion'; ?></button>
                <button type="button" class="btn" onclick="window.location.href='inscription.php';"><?php echo $translations['Inscription'] ?? 'Inscription'; ?></button>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        if (dropdown.classList.contains("show")) {
            dropdown.classList.remove("show");
        } else {
            dropdown.classList.add("show");
        }
    }

    window.onclick = function(event) {
        if (!event.target.matches('.profile-button') && !event.target.closest('.profile-button')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
