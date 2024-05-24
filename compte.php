<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit;
}


switch ($_SESSION['status']) {
    case '0': 
        echo '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Compte Administrateur</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-4">
                <h1>Compte Administrateur</h1>
                <p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['email']) . '</p>
                <p><strong>Nom:</strong> ' . htmlspecialchars($_SESSION['nom']) . '</p>
                <p><strong>Prénom:</strong> ' . htmlspecialchars($_SESSION['prenom']) . '</p>
                <p><strong>Date de naissance:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                <p><strong>Statut:</strong> Administrateur</p>
                <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            </div>
        </body>
        </html>
        ';
        break;
    case '1': // Modérateur
        echo '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Compte Modérateur</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-4">
                <h1>Compte Modérateur</h1>
                <p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['email']) . '</p>
                <p><strong>Nom:</strong> ' . htmlspecialchars($_SESSION['nom']) . '</p>
                <p><strong>Prénom:</strong> ' . htmlspecialchars($_SESSION['prenom']) . '</p>
                <p><strong>Date de naissance:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                <p><strong>Statut:</strong> Modérateur</p>
                <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            </div>
        </body>
        </html>
        ';
        break;
    case '2': // Utilisateur
        echo '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Compte Utilisateur</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-4">
                <h1>Compte Utilisateur</h1>
                <p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['email']) . '</p>
                <p><strong>Nom:</strong> ' . htmlspecialchars($_SESSION['nom']) . '</p>
                <p><strong>Prénom:</strong> ' . htmlspecialchars($_SESSION['prenom']) . '</p>
                <p><strong>Date de naissance:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                <p><strong>Statut:</strong> Utilisateur</p>
                <img src="' . htmlspecialchars($_SESSION['photo_profil']) . '" alt="Photo de Profil">
                <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            </div>
        </body>
        </html>
        ';
        break;
        case '3': 
            echo '
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Compte Entreprise non-validé</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
            </head>
            <body>
                <div class="container mt-4">
                    <h1>Compte Compte Entreprise Non validé</h1>
                    <p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['email']) . '</p>
                    <p><strong>Nom:</strong> ' . htmlspecialchars($_SESSION['nom']) . '</p>
                    <p><strong>Prénom:</strong> ' . htmlspecialchars($_SESSION['prenom']) . '</p>
                    <p><strong>Date de naissance:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                    <p><strong>Statut:</strong> Utilisateur</p>
                    <a href="logout.php" class="btn btn-danger">Déconnexion</a>
                </div>
            </body>
            </html>
            ';
            break;
            case '4': // Utilisateur
                echo '
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Compte Entreprise</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
                </head>
                <body>
                    <div class="container mt-4">
                        <h1>Compte Entreprise</h1>
                        <p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['email']) . '</p>
                        <p><strong>Nom:</strong> ' . htmlspecialchars($_SESSION['nom']) . '</p>
                        <p><strong>Prénom:</strong> ' . htmlspecialchars($_SESSION['prenom']) . '</p>
                        <p><strong>Date de naissance:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                        <p><strong>Statut:</strong> Entreprise</p>
                        <a href="mes_services.php" class="btn btn-danger">Mes services</a>
                        <a href="mettre_en_vente_un_service.php" class="btn btn-danger">Mettre en vente un service</a>
                        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
                    </div>
                </body>
                </html>
                ';
                break;
    default:
        echo '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Accès non reconnu</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-4">
                <h1>Statut Inconnu</h1>
                <p>Vous n\'avez pas les droits d\'accès nécessaires pour cette page.</p>
                <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            </div>
        </body>
        </html>
        ';
        break;
}

?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
