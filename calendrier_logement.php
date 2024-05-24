<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="css/header.css">
</head>
<body>
  <?php
    session_start();
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $nom = $_SESSION['nom'];
    $age = $_SESSION['age'];
    $status = $_SESSION['status'];
    $prenom = $_SESSION['prenom'];
    $password = $_SESSION['password'];
    require 'include/db.php';
    if (!isset($_POST['id_logement']) || empty($_POST['id_logement'])) {
        echo "ID de logement non fourni.";
        exit;
    }
    
    $logement_id = $_POST['id_logement'];
    $stmt = $bdd->prepare("SELECT * FROM DATE_DISPO WHERE id_LOGEMENT = ?");
    $stmt->execute([$logement_id]);
    $dates = $stmt->fetchAll();
    
    $moisActuel = date('m');
    $anneeActuelle = date('Y');
    $datesJson = json_encode(array_map(function ($row) {
        return $row['date'];
    }, $dates));
    echo "<script>var datesDispo = $datesJson;</script>";
      ?>

<?php require_once 'header.php'; ?>
<style>
        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f2f2f2;
        }
        .bg-success {
    background-color: #28a745 !important; 
}
    </style>
</head>
<body>
<main class="container">
    
    <h1>Calendrier du Mois</h1>
    <div class="text-center mb-3">
        <button id="prevMonth" class="btn btn-primary">Précédent</button>
        <span id="moisAnnee" class="mx-4"></span>
        <button id="nextMonth" class="btn btn-primary">Suivant</button>
    </div>
    <div id="calendrier"></div>
</main>

<script>
let moisActuel;
let anneeActuelle;

function initCalendar() {
    const aujourdHui = new Date();
    moisActuel = aujourdHui.getMonth() + 1; // JavaScript compte les mois de 0 à 11
    anneeActuelle = aujourdHui.getFullYear();
    updateCalendar();
}

function changerMois(direction) {
    moisActuel += direction;
    if (moisActuel > 12) {
        moisActuel = 1;
        anneeActuelle++;
    } else if (moisActuel < 1) {
        moisActuel = 12;
        anneeActuelle--;
    }
    updateCalendar();
}



function nomMois(mois) {
    const moisNoms = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                      "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    return moisNoms[mois - 1];
}

function updateCalendar() {
    document.getElementById('moisAnnee').textContent = nomMois(moisActuel) + ' ' + anneeActuelle;
    construireCalendrier(moisActuel, anneeActuelle);
}

function construireCalendrier(mois, annee) {
    const premierJour = new Date(annee, mois - 1, 1);
    const nombreDeJours = new Date(annee, mois, 0).getDate();
    let premierJourSemaine = premierJour.getDay();
    premierJourSemaine = premierJourSemaine === 0 ? 7 : premierJourSemaine - 1;

    let tableau = '<table class="table table-bordered"><thead><tr><th>Lundi</th><th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th><th>Dimanche</th></tr></thead><tbody>';
    let jourCourant = 1;

    for (let i = 0; i < 6; i++) {
        tableau += '<tr>';
        for (let j = 0; j < 7; j++) {
            if (i === 0 && j < premierJourSemaine || jourCourant > nombreDeJours) {
                tableau += '<td>&nbsp;</td>';
            } else if (jourCourant <= nombreDeJours) {
                const dateActuelle = `${annee}-${('0' + mois).slice(-2)}-${('0' + jourCourant).slice(-2)}`;
                if (datesDispo.includes(dateActuelle)) {
                    tableau += `<td class="bg-success">${jourCourant} <button onclick="supprimerDate('${dateActuelle}')">Supprimer</button></td>`;
                } else {
                    tableau += `<td>${jourCourant} <button onclick="ajouterDate('${dateActuelle}')">Ajouter</button></td>`;
                }
                jourCourant++;
            }
        }
        tableau += '</tr>';
        if (jourCourant > nombreDeJours) break;
    }

    tableau += '</tbody></table>';
    document.getElementById('calendrier').innerHTML = tableau;
}


function supprimerDate(date) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'supprimer_date.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.status === 'success') {
                datesDispo = datesDispo.filter(d => d !== date);
                updateCalendar(); // Rafraîchir le calendrier après suppression
            }
        }
    };
    xhr.send('date=' + encodeURIComponent(date) + '&id_logement=' + <?= json_encode($logement_id) ?>);
}


document.addEventListener('DOMContentLoaded', function() {
    fetchDates();
});

function fetchDates() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_dates.php?id_logement=' + <?= json_encode($logement_id) ?>, true);
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.status === 'success') {
                datesDispo = response.dates;
                updateCalendar();
            } else {
                alert('Erreur lors du chargement des dates: ' + response.message);
            }
        }
    };
    xhr.send();
}
function ajouterDate(date) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajouter_date.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.status === 'success') {
                datesDispo.push(date); // Ajouter la nouvelle date au tableau
                updateCalendar(); // Mettre à jour le calendrier pour refléter l'ajout
            }
        }
    };
    xhr.send('date=' + encodeURIComponent(date) + '&id_logement=' + <?= json_encode($logement_id) ?>);
}

document.addEventListener('DOMContentLoaded', initCalendar);
document.getElementById('prevMonth').addEventListener('click', () => changerMois(-1));
document.getElementById('nextMonth').addEventListener('click', () => changerMois(1));
</script>
</body>
</html>