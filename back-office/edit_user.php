<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Modifier l'Utilisateur</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/stylesheet_details.css">
    <link href="../picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../Js/script.js" async></script>
    <style>
        /* Ajoutez ici votre propre CSS pour personnaliser la page */
    </style>
</head>

<?php
include 'header.php';
include 'verif.php';
include 'include/db.php';

$id_user_a_modifier = $_GET['user_id'];

$query = "SELECT * FROM USER WHERE id=:id";
$stmt = $bdd->prepare($query);
$stmt->execute(['id' => $id_user_a_modifier]);
$user_a_modifier = $stmt->fetch();

if (!$user_a_modifier) {
    echo "Utilisateur non trouvé.";
    exit;
}

$query_status = "SELECT * FROM STATUS";
$stmt_status = $bdd->query($query_status);
$status_list = $stmt_status->fetchAll();

echo '<body class="container text-center">
    <h1>Modifier l\'Utilisateur</h1>
    <form action="traitement_modification_user.php" method="POST">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de l\'utilisateur</label>
            <input type="text" class="form-control" id="nom" name="nom" value="' . htmlspecialchars($user_a_modifier['nom']) . '" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom de l\'utilisateur</label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="' . htmlspecialchars($user_a_modifier['prenom']) . '" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email de l\'utilisateur</label>
            <input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($user_a_modifier['email']) . '" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Statut de l\'utilisateur</label>
            <select class="form-select" id="status" name="status" required>';
            foreach ($status_list as $status) {
                echo '<option value="' . $status['id'] . '"';
                if ($status['id'] == $user_a_modifier['status']) {
                    echo ' selected';
                }
                echo '>' . htmlspecialchars($status['name']) . '</option>';
            }
echo '</select>
        </div>
        <input type="hidden" name="user_id" value="' . htmlspecialchars($user_a_modifier['id']) . '">
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>';

echo '</body>';

?>
</html>
