<?php
session_start();

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit;
}

// Affichage différent en fonction du statut
switch ($_SESSION['status']) {
    case '0': // Administrateur
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
                <p><strong>Âge:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                <p><strong>Statut:</strong> Administrateur</p>
                <a href="mettre_en_vente_un_service.php" class="btn btn-secondary"><?php echo "Valider la photo de profil"; ?></a>

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
                <p><strong>Âge:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
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
                <p><strong>Âge:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                <p><strong>Statut:</strong> Utilisateur</p>
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
                    <h1>Compte Utilisateur</h1>
                    <p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['email']) . '</p>
                    <p><strong>Nom:</strong> ' . htmlspecialchars($_SESSION['nom']) . '</p>
                    <p><strong>Prénom:</strong> ' . htmlspecialchars($_SESSION['prenom']) . '</p>
                    <p><strong>Âge:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
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
                        <h1>Compte Utilisateur</h1>
                        <p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['email']) . '</p>
                        <p><strong>Nom:</strong> ' . htmlspecialchars($_SESSION['nom']) . '</p>
                        <p><strong>Prénom:</strong> ' . htmlspecialchars($_SESSION['prenom']) . '</p>
                        <p><strong>Âge:</strong> ' . htmlspecialchars($_SESSION['age']) . '</p>
                        <p><strong>Statut:</strong> Entreprise</p>
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
