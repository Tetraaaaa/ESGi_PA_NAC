<head>
    <link rel="stylesheet" href="css/header.css">
</head>
<header class="container-fluid p-3 border-bottom">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <img src="image/PCS.png" alt="Logo">
        </div>
        
        <div class="nav-links">
            <a href="index.php" class="btn text-decoration-none">Trouver une location</a>
            <a href="vos_locations.php" class="btn text-decoration-none">Vos locations</a>
        </div>

        <?php if (isset($_SESSION['id'])): ?>
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="btn">Mon compte</button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="compte.php">Mon compte</a>
                    <a href="voyages.php">Mes voyages</a>
                    <?php if ($_SESSION['status'] == "4"): ?>
                        <a href="mes_services.php">Mes services</a>
                        <a href="mettre_en_vente_un_service.php">Mettre en vente un service</a>
                    <?php endif; ?>
                    <?php if ($_SESSION['status'] == "0"): ?>
                        <a href="back-office/">Admin</a>
                    <?php endif; ?>
                    <a href="logout.php">Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <div>
                <button type="button" class="btn" onclick="window.location.href='connexion.php';">Connexion</button>
                <button type="button" class="btn" onclick="window.location.href='inscription.php';">Inscription</button>
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
        if (!event.target.matches('.btn')) {
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
