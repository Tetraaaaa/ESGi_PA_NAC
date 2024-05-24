<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="css/index.css">

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
      /* Custom styles for characteristics with checkboxes and icons */
      .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-check input[type="checkbox"] {
            position: absolute;
            opacity: 0;
        }

        .form-check i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .form-check label {
            margin-left: 5px;
            position: relative;
            padding-left: 30px;
            cursor: pointer;
        }

        .form-check label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
            background-color: #fff;
        }

        .form-check input[type="checkbox"]:checked + label:before {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-check input[type="checkbox"]:checked + label:after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 4px;
            top: 0;
            font-size: 14px;
            color: #fff;
        }

    </style>
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
  ?>

<?php
include 'include/db.php';

// Récupérer les caractéristiques de la base de données
include 'include/db.php';

// Récupérer les caractéristiques et leurs icônes de la base de données
$q = '
    SELECT c.id, c.nom, i.emplacement 
    FROM CARACTERISTIQUE c
    LEFT JOIN ICONE i ON c.id = i.id_CARACTERISTIQUE
';
$req = $bdd->query($q);
$caracteristiques = $req->fetchAll();


?>
<?php require_once 'header.php'; ?>

<main class="container">
  <form method="POST" action="mettre_en_location_un_logement_verif.php" class="my-registration-validation" enctype="multipart/form-data">

              <div class="form-group">
                <label for="Nom de la location" class="form-label">Nom de la location</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
              </div>
              <div class="form-group">
                <label for="Description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description" required>
              </div>
              <div class="form-group">
                <label for="Nombre de locataire" class="form-label">Nombre de locataire </label>
                <input type="number" class="form-control" id="capacite_location" name="capacite_location" required>

              </div>
              <div class="form-group">
                <label for="Adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" required>
              </div>
              <div class="form-group">
                <label for="Ville" class="form-label">Ville</label>
                <input type="text" class="form-control" id="ville" name="ville" required>
              </div>
              <div class="form-group">
                <label for="prix" class="form-label">Prix par nuit</label>
                <input type="number" class="form-control" id="prix" name="prix" required>
              </div>
              <div class="form-group">
                <label for="Code postal" class="form-label">Code postal</label>
                <input type="number" class="form-control" id="code" name="code" required>
              </div>
              <div class="form-group">
                <label for="pays" class="form-label">Pays</label>
                <select id="pays" name="pays" class="form-control" required>
                    <option value="">Sélectionnez un pays</option>
                    <option value="AF">Afghanistan</option>
                    <option value="AX">Îles Åland</option>
                    <option value="AL">Albanie</option>
                    <option value="DZ">Algérie</option>
                    <option value="AS">Samoa américaines</option>
                    <option value="AD">Andorre</option>
                    <option value="AO">Angola</option>
                    <option value="AI">Anguilla</option>
                    <option value="AQ">Antarctique</option>
                    <option value="AG">Antigua-et-Barbuda</option>
                    <option value="AR">Argentine</option>
                    <option value="AM">Arménie</option>
                    <option value="AW">Aruba</option>
                    <option value="AU">Australie</option>
                    <option value="AT">Autriche</option>
                    <option value="AZ">Azerbaïdjan</option>
                    <option value="BS">Bahamas</option>
                    <option value="BH">Bahreïn</option>
                    <option value="BD">Bangladesh</option>
                    <option value="BB">Barbade</option>
                    <option value="BY">Biélorussie</option>
                    <option value="BE">Belgique</option>
                    <option value="BZ">Belize</option>
                    <option value="BJ">Bénin</option>
                    <option value="BM">Bermudes</option>
                    <option value="BT">Bhoutan</option>
                    <option value="BO">Bolivie</option>
                    <option value="BQ">Bonaire, Saint-Eustache et Saba</option>
                    <option value="BA">Bosnie-Herzégovine</option>
                    <option value="BW">Botswana</option>
                    <option value="BV">Île Bouvet</option>
                    <option value="BR">Brésil</option>
                    <option value="IO">Territoire britannique de l'océan Indien</option>
                    <option value="BN">Brunei</option>
                    <option value="BG">Bulgarie</option>
                    <option value="BF">Burkina Faso</option>
                    <option value="BI">Burundi</option>
                    <option value="CV">Cap-Vert</option>
                    <option value="KH">Cambodge</option>
                    <option value="CM">Cameroun</option>
                    <option value="CA">Canada</option>
                    <option value="KY">Îles Caïmans</option>
                    <option value="CF">République centrafricaine</option>
                    <option value="TD">Tchad</option>
                    <option value="CL">Chili</option>
                    <option value="CN">Chine</option>
                    <option value="CX">Île Christmas</option>
                    <option value="CC">Îles Cocos</option>
                    <option value="CO">Colombie</option>
                    <option value="KM">Comores</option>
                    <option value="CG">Congo-Brazzaville</option>
                    <option value="CD">Congo-Kinshasa</option>
                    <option value="CK">Îles Cook</option>
                    <option value="CR">Costa Rica</option>
                    <option value="HR">Croatie</option>
                    <option value="CU">Cuba</option>
                    <option value="CW">Curaçao</option>
                    <option value="CY">Chypre</option>
                    <option value="CZ">République tchèque</option>
                    <option value="DK">Danemark</option>
                    <option value="DJ">Djibouti</option>
                    <option value="DM">Dominique</option>
                    <option value="DO">République dominicaine</option>
                    <option value="EC">Équateur</option>
                    <option value="EG">Égypte</option>
                    <option value="SV">Salvador</option>
                    <option value="GQ">Guinée équatoriale</option>
                    <option value="ER">Érythrée</option>
                    <option value="EE">Estonie</option>
                    <option value="SZ">Eswatini</option>
                    <option value="ET">Éthiopie</option>
                    <option value="FK">Îles Malouines</option>
                    <option value="FO">Îles Féroé</option>
                    <option value="FJ">Fidji</option>
                    <option value="FI">Finlande</option>
                    <option value="FR">France</option>
                    <option value="GF">Guyane française</option>
                    <option value="PF">Polynésie française</option>
                    <option value="TF">Terres australes françaises</option>
                    <option value="GA">Gabon</option>
                    <option value="GM">Gambie</option>
                    <option value="GE">Géorgie</option>
                    <option value="DE">Allemagne</option>
                    <option value="GH">Ghana</option>
                    <option value="GI">Gibraltar</option>
                    <option value="GR">Grèce</option>
                    <option value="GL">Groenland</option>
                    <option value="GD">Grenade</option>
                    <option value="GP">Guadeloupe</option>
                    <option value="GU">Guam</option>
                    <option value="GT">Guatemala</option>
                    <option value="GG">Guernesey</option>
                    <option value="GN">Guinée</option>
                    <option value="GW">Guinée-Bissau</option>
                    <option value="GY">Guyana</option>
                    <option value="HT">Haïti</option>
                    <option value="HM">Îles Heard-et-MacDonald</option>
                    <option value="VA">Saint-Siège</option>
                    <option value="HN">Honduras</option>
                    <option value="HK">Hong Kong</option>
                    <option value="HU">Hongrie</option>
                    <option value="IS">Islande</option>
                    <option value="IN">Inde</option>
                    <option value="ID">Indonésie</option>
                    <option value="IR">Iran</option>
                    <option value="IQ">Irak</option>
                    <option value="IE">Irlande</option>
                    <option value="IM">Île de Man</option>
                    <option value="IL">Israël</option>
                    <option value="IT">Italie</option>
                    <option value="JM">Jamaïque</option>
                    <option value="JP">Japon</option>
                    <option value="JE">Jersey</option>
                    <option value="JO">Jordanie</option>
                    <option value="KZ">Kazakhstan</option>
                    <option value="KE">Kenya</option>
                    <option value="KI">Kiribati</option>
                    <option value="KP">Corée du Nord</option>
                    <option value="KR">Corée du Sud</option>
                    <option value="KW">Koweït</option>
                    <option value="KG">Kirghizistan</option>
                    <option value="LA">Laos</option>
                    <option value="LV">Lettonie</option>
                    <option value="LB">Liban</option>
                    <option value="LS">Lesotho</option>
                    <option value="LR">Libéria</option>
                    <option value="LY">Libye</option>
                    <option value="LI">Liechtenstein</option>
                    <option value="LT">Lituanie</option>
                    <option value="LU">Luxembourg</option>
                    <option value="MO">Macao</option>
                    <option value="MG">Madagascar</option>
                    <option value="MW">Malawi</option>
                    <option value="MY">Malaisie</option>
                    <option value="MV">Maldives</option>
                    <option value="ML">Mali</option>
                    <option value="MT">Malte</option>
                    <option value="MH">Îles Marshall</option>
                    <option value="MQ">Martinique</option>
                    <option value="MR">Mauritanie</option>
                    <option value="MU">Maurice</option>
                    <option value="YT">Mayotte</option>
                    <option value="MX">Mexique</option>
                    <option value="FM">Micronésie</option>
                    <option value="MD">Moldavie</option>
                    <option value="MC">Monaco</option>
                    <option value="MN">Mongolie</option>
                    <option value="ME">Monténégro</option>
                    <option value="MS">Montserrat</option>
                    <option value="MA">Maroc</option>
                    <option value="MZ">Mozambique</option>
                    <option value="MM">Myanmar</option>
                    <option value="NA">Namibie</option>
                    <option value="NR">Nauru</option>
                    <option value="NP">Népal</option>
                    <option value="NL">Pays-Bas</option>
                    <option value="NC">Nouvelle-Calédonie</option>
                    <option value="NZ">Nouvelle-Zélande</option>
                    <option value="NI">Nicaragua</option>
                    <option value="NE">Niger</option>
                    <option value="NG">Nigeria</option>
                    <option value="NU">Niue</option>
                    <option value="NF">Île Norfolk</option>
                    <option value="MP">Îles Mariannes du Nord</option>
                    <option value="NO">Norvège</option>
                    <option value="OM">Oman</option>
                    <option value="PK">Pakistan</option>
                    <option value="PW">Palaos</option>
                    <option value="PS">Palestine</option>
                    <option value="PA">Panama</option>
                    <option value="PG">Papouasie-Nouvelle-Guinée</option>
                    <option value="PY">Paraguay</option>
                    <option value="PE">Pérou</option>
                    <option value="PH">Philippines</option>
                    <option value="PN">Îles Pitcairn</option>
                    <option value="PL">Pologne</option>
                    <option value="PT">Portugal</option>
                    <option value="PR">Porto Rico</option>
                    <option value="QA">Qatar</option>
                    <option value="RE">La Réunion</option>
                    <option value="RO">Roumanie</option>
                    <option value="RU">Russie</option>
                    <option value="RW">Rwanda</option>
                    <option value="BL">Saint-Barthélemy</option>
                    <option value="SH">Sainte-Hélène</option>
                    <option value="KN">Saint-Kitts-et-Nevis</option>
                    <option value="LC">Sainte-Lucie</option>
                    <option value="MF">Saint-Martin (partie française)</option>
                    <option value="PM">Saint-Pierre-et-Miquelon</option>
                    <option value="VC">Saint-Vincent-et-les-Grenadines</option>
                    <option value="WS">Samoa</option>
                    <option value="SM">Saint-Marin</option>
                    <option value="ST">Sao Tomé-et-Principe</option>
                    <option value="SA">Arabie saoudite</option>
                    <option value="SN">Sénégal</option>
                    <option value="RS">Serbie</option>
                    <option value="SC">Seychelles</option>
                    <option value="SL">Sierra Leone</option>
                    <option value="SG">Singapour</option>
                    <option value="SX">Saint-Martin (partie néerlandaise)</option>
                    <option value="SK">Slovaquie</option>
                    <option value="SI">Slovénie</option>
                    <option value="SB">Îles Salomon</option>
                    <option value="SO">Somalie</option>
                    <option value="ZA">Afrique du Sud</option>
                    <option value="GS">Géorgie du Sud et îles Sandwich du Sud</option>
                    <option value="SS">Soudan du Sud</option>
                    <option value="ES">Espagne</option>
                    <option value="LK">Sri Lanka</option>
                    <option value="SD">Soudan</option>
                    <option value="SR">Suriname</option>
                    <option value="SJ">Svalbard et île Jan Mayen</option>
                    <option value="SE">Suède</option>
                    <option value="CH">Suisse</option>
                    <option value="SY">Syrie</option>
                    <option value="TW">Taïwan</option>
                    <option value="TJ">Tadjikistan</option>
                    <option value="TZ">Tanzanie</option>
                    <option value="TH">Thaïlande</option>
                    <option value="TL">Timor-Leste</option>
                    <option value="TG">Togo</option>
                    <option value="TK">Tokelau</option>
                    <option value="TO">Tonga</option>
                    <option value="TT">Trinité-et-Tobago</option>
                    <option value="TN">Tunisie</option>
                    <option value="TR">Turquie</option>
                    <option value="TM">Turkménistan</option>
                    <option value="TC">Îles Turques-et-Caïques</option>
                    <option value="TV">Tuvalu</option>
                    <option value="UG">Ouganda</option>
                    <option value="UA">Ukraine</option>
                    <option value="AE">Émirats arabes unis</option>
                    <option value="GB">Royaume-Uni</option>
                    <option value="US">États-Unis</option>
                    <option value="UM">Îles mineures éloignées des États-Unis</option>
                    <option value="UY">Uruguay</option>
                    <option value="UZ">Ouzbékistan</option>
                    <option value="VU">Vanuatu</option>
                    <option value="VE">Venezuela</option>
                    <option value="VN">Viêt Nam</option>
                    <option value="VG">Îles Vierges britanniques</option>
                    <option value="VI">Îles Vierges des États-Unis</option>
                    <option value="WF">Wallis-et-Futuna</option>
                    <option value="EH">Sahara occidental</option>
                    <option value="YE">Yémen</option>
                    <option value="ZM">Zambie</option>
                    <option value="ZW">Zimbabwe</option>
                </select>
            </div>
              <div class="form-group">
                <label for="Heure de contacte" class="form-label">Type de logement</label>
                    <select id="horaire" name="type_logement" class="form-control">
                        <option value="appartement">Appartement</option>
                        <option value="maison">Maison</option>
                        <option value="chalet">Chalet</option>
                        <option value="villa">Villa</option>
                        <option value="maison_partagee">Maison partagée</option>
                    </select>
              </div>


              <div class="form-group">
                <label for="caracteristique" class="form-label">Caractéristiques du logement</label>
                <?php foreach ($caracteristiques as $caracteristique) { ?>
                    <div class="form-check">
                        <input type="checkbox" id="caracteristique_<?php echo $caracteristique['id']; ?>" name="caracteristiques[]" value="<?php echo $caracteristique['id']; ?>">
                        <label class="form-check-label" for="caracteristique_<?php echo $caracteristique['id']; ?>">
                            <?php if (!empty($caracteristique['emplacement'])) { ?>
                                <img src="icone/<?php echo $caracteristique['emplacement']; ?>" alt="<?php echo htmlspecialchars($caracteristique['nom']); ?>" style="width:20px; height:20px;" onerror="this.onerror=null; this.src='path/to/default/image.png'">
                            <?php } ?>
                            <?php echo htmlspecialchars($caracteristique['nom']); ?>
                        </label>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group">
            <label for="horaires" class="form-label">Heure de contact</label>
            <div>
                <button type="button" class="btn btn-primary btn-submit" onclick="selectAllHoraires()">Tout sélectionner</button>
                <button type="button" class="btn btn-primary btn-submit" onclick="deselectAllHoraires()">Tout désélectionner</button><br><br>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_1" name="horaires[]" value="08:00-09:00">
                <label class="form-check-label" for="horaire_1">8h-9h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_2" name="horaires[]" value="09:00-10:00">
                <label class="form-check-label" for="horaire_2">9h-10h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_3" name="horaires[]" value="10:00-11:00">
                <label class="form-check-label" for="horaire_3">10h-11h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_4" name="horaires[]" value="12:00-13:00">
                <label class="form-check-label" for="horaire_4">12h-13h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_5" name="horaires[]" value="13:00-14:00">
                <label class="form-check-label" for="horaire_5">13h-14h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_6" name="horaires[]" value="14:00-15:00">
                <label class="form-check-label" for="horaire_6">14h-15h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_7" name="horaires[]" value="15:00-16:00">
                <label class="form-check-label" for="horaire_7">15h-16h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_8" name="horaires[]" value="16:00-17:00">
                <label class="form-check-label" for="horaire_8">16h-17h</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="horaire_9" name="horaires[]" value="17:00-18:00">
                <label class="form-check-label" for="horaire_9">17h-18h</label>
            </div>
        </div>
</div>
              <div class="form-group">
                <label for="photos">Photos du logement</label>
                  <input type="file" class="form-control" id="photos" name="photos[]" accept="image/*" multiple>
              </div>
              <button type="submit" class="btn btn-primary btn-submit">S'inscrire</button>
  </form> 
</main>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script>
// Fonction pour tout sélectionner
// Fonction pour tout sélectionner
function selectAll() {
    const checkboxes = document.querySelectorAll('input[name="caracteristiques[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = true;
    });
}

// Fonction pour tout désélectionner
function deselectAll() {
    const checkboxes = document.querySelectorAll('input[name="caracteristiques[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });
}

// Fonction pour tout sélectionner pour les horaires
function selectAllHoraires() {
    const checkboxes = document.querySelectorAll('input[name="horaires[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = true;
    });
}

// Fonction pour tout désélectionner pour les horaires
function deselectAllHoraires() {
    const checkboxes = document.querySelectorAll('input[name="horaires[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });
}

    $(document).ready(function() {
            function formatState (state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span><i class="' + $(state.element).data('icon') + '"></i> ' + state.text + '</span>'
                );
                return $state;
            };

            $('#caracteristique').select2({
                templateResult: formatState,
                templateSelection: formatState
            });
        });

    $(document).ready(function() {
            $('#pays').select2({
                placeholder: "Sélectionnez un pays",
                allowClear: true,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) === 0) {
                        return data;
                    }
                    return null;
                }
            });
        });
</script>

<?php require_once 'footer.php'; ?>

</body>
</html>
