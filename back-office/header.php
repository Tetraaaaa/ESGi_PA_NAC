<?php include '../include/session.php'?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                Paris Caretaker Services
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user.php">Utilisateur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="service.php">Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reservation.php">Réservation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logement.php">Logement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Deconnection</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</header>
</body>
</html>