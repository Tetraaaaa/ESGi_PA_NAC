<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Logement</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet_details.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../Js/script.js" async></script>
</head>

<body class="container text-center">
    <?php
    session_start();

    // Vérifier si l'utilisateur est connecté et si son statut est celui d'un administrateur
    if (!isset($_SESSION['status']) || $_SESSION['status'] != 0) {
        // Rediriger l'utilisateur vers la page d'accueil ou de connexion si ce n'est pas un administrateur
        header("Location: index.php");
        exit; // Important pour arrêter l'exécution du script après la redirection
    }
    include 'header.php';
    include 'verif.php';
    include 'include/db.php';

    // Récupération de tous les logements
    $query = "SELECT * FROM LOGEMENT";
    $stmt = $bdd->query($query);
    $logements = $stmt->fetchAll();

    // Vérification si des logements sont trouvés
    if (count($logements) > 0) {
        ?>
        <div class="container mt-5">
            <h2>Liste des Logements :</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID Utilisateur</th>
                        <th>Prix</th>
                        <th>Validation</th>
                        <th>Type Conciergerie</th>
                        <th>Adresse</th>
                        <th>Ville</th>
                        <th>Code Postal</th>
                        <th>Pays</th>
                        <th>Type de Bien</th>
                        <th>Type de Location</th>
                        <th>Capacité de Location</th>
                        <th>Description</th>
                        <th>Heure de Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logements as $logement) { ?>
                        <tr>
                            <td><?php echo $logement['id']; ?></td>
                            <td><?php echo $logement['id_USER']; ?></td>
                            <td><?php echo $logement['prix']; ?></td>
                            <td><?php echo $logement['validation']; ?></td>
                            <td><?php echo $logement['type_conciergerie']; ?></td>
                            <td><?php echo $logement['adresse']; ?></td>
                            <td><?php echo $logement['ville']; ?></td>
                            <td><?php echo $logement['code_postal']; ?></td>
                            <td><?php echo $logement['pays']; ?></td>
                            <td><?php echo $logement['type_bien']; ?></td>
                            <td><?php echo $logement['type_location']; ?></td>
                            <td><?php echo $logement['capacite_location']; ?></td>
                            <td><?php echo $logement['description']; ?></td>
                            <td><?php echo $logement['heure_de_contacte']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="container mt-5">
            <p>Aucun logement trouvé.</p>
        </div>
    <?php } ?>
</body>

</html>
