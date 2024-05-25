<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Logement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css"> <!-- Assurez-vous que le fichier CSS du footer est inclus -->
    <style>
        .carousel-control-prev, .carousel-control-next {
            background: none;
            border: none;
            color: black;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-image: none;
        }
        .carousel-control-prev-icon:after,
        .carousel-control-next-icon:after {
            content: '❮';
            font-size: 24px;
            color: black;
        }
        .carousel-control-next-icon:after {
            content: '❯';
        }
        .carousel-img {
            height: 300px;
            width: auto;
            margin: auto;
            display: block;
        }
        .modal-img {
            width: 100%;
            height: auto;
        }
        .main-content {
            min-height: calc(100vh - 200px); /* Ajustez cette valeur en fonction de la hauteur de votre header et footer */
        }
    </style>
</head>
<body>
    <?php
    session_start();
    require_once 'include/connection_db.php';

    $logementId = isset($_GET['id']) ? $_GET['id'] : 0;
    $datesDisponibles = [];
    if ($logementId) {
        $query = $bdd->prepare("SELECT * FROM DATE_DISPO WHERE id_LOGEMENT = :id_LOGEMENT");
        $query->execute(['id_LOGEMENT' => $logementId]);
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $datesDisponibles[] = $row['date'];
        }
    }
    $datesReservees = [];
    if ($logementId) {
        $reservedDatesQuery = $bdd->prepare("SELECT date FROM DATE_RESERVE WHERE id_LOGEMENT = :id_LOGEMENT");
        $reservedDatesQuery->execute(['id_LOGEMENT' => $logementId]);
        while ($row = $reservedDatesQuery->fetch(PDO::FETCH_ASSOC)) {
            $datesReservees[] = $row['date'];
        }
        $datesReserveesJson = json_encode($datesReservees);
        echo "<script>var datesReservees = $datesReserveesJson;</script>";
    }
    $datesJson = json_encode($datesDisponibles);
    echo "<script>var datesDisponibles = $datesJson;</script>";
    if ($logementId) {
        $query = $bdd->prepare("SELECT * FROM LOGEMENT WHERE id = :id");
        $query->execute(['id' => $logementId]);
        $logement = $query->fetch(PDO::FETCH_ASSOC);

        $imgQuery = $bdd->prepare("SELECT * FROM PHOTO_LOGEMENT WHERE id_LOGEMENT = :id_LOGEMENT");
        $imgQuery->execute(['id_LOGEMENT' => $logementId]);
        $images = $imgQuery->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>

    <?php require_once 'header.php'; ?>

    <main class="container main-content mt-4">
        <h1><?= htmlspecialchars($logement['nom']) ?></h1>
        <div id="logementCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
                <?php foreach ($images as $index => $image): ?>
                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                    <img src="<?= htmlspecialchars($image['emplacement']) ?>" alt="Photo du logement" class="carousel-img" onclick="enlargeImage('<?= htmlspecialchars($image['emplacement']) ?>')">
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#logementCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#logementCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"></span>
            </button>
        </div>

        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img id="modalImage" class="modal-img" src="" alt="Enlarged image">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <p><strong>Description:</strong> <?= htmlspecialchars($logement['description']) ?></p>
                <p><strong>Adresse:</strong> <?= htmlspecialchars($logement['adresse']) ?></p>
                <p><strong>Ville:</strong> <?= htmlspecialchars($logement['ville']) ?></p>
                <p><strong>Code Postal:</strong> <?= htmlspecialchars($logement['code_postal']) ?></p>
                <p><strong>Pays:</strong> <?= htmlspecialchars($logement['pays']) ?></p>
                <p><strong>Prix par nuit:</strong> <?= htmlspecialchars($logement['prix']) ?>€</p>
            </div>
        </div>
        
        <div class="card mt-4">
            <h3 class="card-header">Calendrier de Disponibilité</h3>
            <div class="card-body">
                <div class="text-center mb-3">
                    <button id="prevMonth" class="btn btn-primary">Précédent</button>
                    <span id="moisAnnee" class="mx-4"></span>
                    <button id="nextMonth" class="btn btn-primary">Suivant</button>
                </div>
                <div id="calendrier"></div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5 id="totalCost">Total: 0€</h5>
                <form id="reservationForm" action="reservation_verif.php" method="post">
                    <input type="hidden" id="selectedDatesField" name="selectedDates">
                    <input type="hidden" id="logementIdField" name="logementId" value="<?= htmlspecialchars($logementId) ?>">
                    <button type="submit" class="btn btn-primary">Réserver</button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function enlargeImage(src) {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }
    </script>
    <script>
        let moisActuel;
        let anneeActuelle;

        document.addEventListener('DOMContentLoaded', function() {
            initCalendar();
            document.getElementById('prevMonth').addEventListener('click', function() {
                changerMois(-1);
            });
            document.getElementById('nextMonth').addEventListener('click', function() {
                changerMois(1);
            });
        });

        function initCalendar() {
            const aujourdHui = new Date();
            moisActuel = aujourdHui.getMonth() + 1;
            anneeActuelle = aujourdHui.getFullYear();
            updateCalendar();
        }

        function changerMois(delta) {
            moisActuel += delta;
            if (moisActuel > 12) {
                moisActuel = 1;
                anneeActuelle++;
            } else if (moisActuel < 1) {
                moisActuel = 12;
                anneeActuelle--;
            }
            updateCalendar();
        }

        function updateCalendar() {
            const moisAnneeEl = document.getElementById('moisAnnee');
            if (moisAnneeEl) {
                moisAnneeEl.textContent = nomMois(moisActuel) + ' ' + anneeActuelle;
                construireCalendrier(moisActuel, anneeActuelle);
            } else {
                console.error("L'élément 'moisAnnee' n'est pas trouvé dans le DOM.");
            }
        }

        function nomMois(mois) {
            const moisNoms = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            return moisNoms[mois - 1];
        }

        let selectedDates = []; // Stockage des dates sélectionnées

        function construireCalendrier(mois, annee) {
            const premierJour = new Date(annee, mois - 1, 1);
            const nombreDeJours = new Date(annee, mois, 0).getDate();
            let premierJourSemaine = premierJour.getDay();
            premierJourSemaine = premierJourSemaine === 0 ? 7 : premierJourSemaine - 1;

            let tableau = '<table class="table table-bordered"><thead><tr><th>Lundi</th><th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th><th>Dimanche</th></tr></thead><tbody>';
            let jourCourant = 1;

            for (let i = 0; i < 6; i++) {
                tableau += '<tr>';
                for (let j = 1; j <= 7; j++) {
                    if (i === 0 && j < premierJourSemaine || jourCourant > nombreDeJours) {
                        tableau += '<td>&nbsp;</td>';
                    } else if (jourCourant <= nombreDeJours) {
                        let dateFull = `${annee}-${('0' + mois).slice(-2)}-${('0' + jourCourant).slice(-2)}`;
                        let isReserved = datesReservees.includes(dateFull);
                        let isAvailable = datesDisponibles.includes(dateFull);
                        let isSelected = selectedDates.includes(dateFull);
                        let backgroundColor = isReserved ? '#696969' : isSelected ? '#FFA500' : isAvailable ? '#28a745' : '#FFFFFF';
                        let clickAction = isAvailable && !isReserved ? `onclick="toggleDateSelection('${dateFull}')"` : '';
                        tableau += `<td style="background-color: ${backgroundColor}; cursor: pointer;" ${clickAction}>${jourCourant}</td>`;
                        jourCourant++;
                    }
                }
                tableau += '</tr>';
                if (jourCourant > nombreDeJours) break;
            }

            tableau += '</tbody></table>';
            document.getElementById('calendrier').innerHTML = tableau;
        }

        let prixParNuit = <?= json_encode($logement['prix']) ?>;

        function updateTotalCost() {
            let totalCost = selectedDates.length * prixParNuit;
            document.getElementById('totalCost').textContent = `Total: ${totalCost}€`;
        }

        let selectedStartDate = null;
        let selectedEndDate = null;

        function toggleDateSelection(date) {
            if (!datesDisponibles.includes(date) || datesReservees.includes(date)) return;

            let index = selectedDates.indexOf(date);
            if (index > -1) {
                selectedDates.splice(index, 1);
                if (date === selectedStartDate) selectedStartDate = null;
                if (date === selectedEndDate) selectedEndDate = null;
            } else {
                if (!selectedStartDate || (selectedEndDate && selectedDates.length)) {
                    selectedStartDate = date;
                    selectedEndDate = null;
                    selectedDates = [date];
                } else if (selectedStartDate && !selectedEndDate) {
                    selectedEndDate = date;
                    if (selectedStartDate > selectedEndDate) [selectedStartDate, selectedEndDate] = [selectedEndDate, selectedStartDate];
                    selectDateRange();
                }
            }
            updateCalendar();
            updateTotalCost();
        }

        function selectDateRange() {
            if (!selectedStartDate || !selectedEndDate) return;

            const start = new Date(selectedStartDate);
            const end = new Date(selectedEndDate);
            selectedDates = [];

            for (let dt = new Date(start); dt <= end; dt.setDate(dt.getDate() + 1)) {
                let dateStr = dt.toISOString().split('T')[0];
                if (datesDisponibles.includes(dateStr) && !datesReservees.includes(dateStr)) {
                    selectedDates.push(dateStr);
                } else {
                    alert('La plage sélectionnée contient des jours non disponibles ou déjà réservés.');
                    selectedDates = [];
                    selectedStartDate = null;
                    selectedEndDate = null;
                    break;
                }
            }
            updateCalendar();
            updateTotalCost();
        }

        document.addEventListener('DOMContentLoaded', function() {
            initCalendar();
            updateTotalCost();
        });

        document.getElementById('reservationForm').addEventListener('submit', function(event) {
            event.preventDefault();
            if (selectedDates.some(date => datesReservees.includes(date))) {
                alert('Votre sélection contient des dates déjà réservées. Veuillez ajuster votre sélection.');
            } else {
                updateSelectedDatesField();
                this.submit();
            }
        });

        function updateSelectedDatesField() {
            const selectedDatesStr = selectedDates.join(',');
            document.getElementById('selectedDatesField').value = selectedDatesStr;
        }
    </script>

    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
