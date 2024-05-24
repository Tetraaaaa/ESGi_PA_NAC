<header class="container-fluid p-3 border-bottom">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <img src="image/PCS.png" alt="Logo">
        </div>
        
        <div>
            <a href="index.php" class="btn btn-link text-decoration-none">Trouver une location</a>
        </div>
        <div>
            <a href="vos_locations.php" class="btn btn-link text-decoration-none">Vos locations</a>
        </div>

        <?php if (isset($_SESSION['id'])): ?>
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="btn btn-secondary">Mon compte</button>
                <div id="myDropdown" class="dropdown-content" style="display:none;">
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
                <button type="button" class="btn btn-primary" onclick="window.location.href='connexion.php';">Connexion</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='inscription.php';">Inscription</button>
            </div>
        <?php endif; ?>
    </div>
</header>
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .dropdown-content a:hover {background-color: #f1f1f1}
</style>
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
