<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste des Logements</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/grade.css">
    </head>
    <body>
        <?php require_once 'include/connection_db.php'; 
        session_start();
        require_once 'header.php'; 
        ?>
        <main>
            <div class="d-flex justify-content-center align-items-flex-start w-100">
                <div class="v-flex justify-content-between align-items-center">
                    <div>
                        <img src="image/free.png" alt="Free">
                    </div>
                    <div>
                        <a>Présence du publicité</a>
                    </div>
                    <div>
                        <a>Commenter, publier des avis</a>
                    </div>
                </div>
                <div class="v-flex justify-content-between align-items-center">
                    <div>
                        <img src="image/back-packer.png" alt="back-packer">
                    </div>
                    <div>
                        <a>-Pas de publicité</a>
                    </div>
                    <div>
                        <a>-Commenter, publier des avis</a>
                    </div>
                    <div>
                        <a>-1 prestation offerte par an dans la limitte de 80€</a>
                    </div>
                    <div>
                        <h3>9.90€ / mois</h3>
                    </div>
                    <div>
                        <h3>113€ / ans</h3>
                    </div>
                    <div>
                        <a href="buy_grade.php" class="btn btn-primary">Acheter</a>
                    </div>
                </div>
                <div class="v-flex justify-content-between align-items-center">
                    <div>
                        <img src="image/explorator.png" alt="explorator">
                    </div>
                    <div>
                        <a>-Pas de publicité</a>
                    </div>
                    <div>
                        <a>-Commenter, publier des avis</a>
                    </div>
                    <div>
                        <a>-1 prestation offerte par semestre sans limitte de prix</a>
                    </div>
                    <div>
                        <a>-Accès aux réservation VIP</a>
                    </div>
                    <div>
                        <h3>19€ / mois</h3>
                    </div>
                    <div>
                        <h3>220€ / ans</h3>
                    </div>
                    <div>
                        <a href="buy_grade.php" class="btn btn-primary">Acheter</a>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <?php require_once 'footer.php'; ?>
        </footer>
    </body>
</html>
