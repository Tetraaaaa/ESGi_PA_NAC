<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendValidationEmail($email, $code) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur
        $mail->isSMTP();
        $mail->Host = 'ssl0.ovh.net'; // Host SMTP pour OVH
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@nac.ovh'; // Votre adresse email complète
        $mail->Password = 'Azerty11!'; // Votre mot de passe
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Activer le cryptage SSL
        $mail->Port = 465; // Port SMTP pour SSL

        // Destinataire
        $mail->setFrom('noreply@nac.ovh', 'PCS'); // Votre adresse et nom
        $mail->addAddress($email);

        // Contenu de l'email
        $mail->isHTML(true); // Format de l'email en HTML
        $mail->Subject = 'Votre code de validation';

        // Contenu HTML de l'email
        $mail->Body = '
        <html>
        <head>
            <style>
                @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@400&display=swap");

                body {
                    font-family: "Quicksand", sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                    text-align: center;
                }

                .container {
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    margin: auto;
                    max-width: 500px;
                }

                .header {
                    background-color: #6e8efb;
                    color: #ffffff;
                    padding: 10px;
                    border-radius: 10px 10px 0 0;
                }

                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }

                .content {
                    padding: 20px;
                    text-align: left;
                }

                .content p {
                    font-size: 16px;
                }

                .code-box {
                    background-color: #6e8efb;
                    color: #ffffff;
                    padding: 10px;
                    font-size: 20px;
                    font-weight: bold;
                    margin: 20px 0;
                    border-radius: 5px;
                }

                .footer {
                    margin-top: 20px;
                    font-size: 12px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Validation de votre compte</h1>
                </div>
                <div class="content">
                    <p>Bonjour,</p>
                    <p>Merci de vous être inscrit sur notre site. Pour finaliser votre inscription, veuillez entrer le code de validation ci-dessous sur la page de vérification :</p>
                    <div class="code-box">' . $code . '</div>
                    <p>Ce code est valable pendant 5 minutes.</p>
                    <p>Si vous n\'avez pas demandé cette inscription, veuillez ignorer cet email.</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date("Y") . ' PCS. Tous droits réservés.</p>
                </div>
            </div>
        </body>
        </html>';

        // Contenu texte alternatif pour les clients email sans support HTML
        $mail->AltBody = "Votre code de validation est : $code";

        // Activer le mode débogage , 1 client, 2 client - serveur
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        $mail->send();
        echo 'Le message a été envoyé';
    } catch (Exception $e) {
        echo "Le message n'a pas pu être envoyé. Erreur Mailer: {$mail->ErrorInfo}";
    }
}
?>
