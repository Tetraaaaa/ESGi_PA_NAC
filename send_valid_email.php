<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendValidationEmail($email, $code) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.yahoo.com'; // change host
        $mail->SMTPAuth = true;
        $mail->Username = 'pcs.pa2a1@yahoo.com'; // Remplacez par votre adresse email 
        $mail->Password = 'mdp'; // Remplacez par votre mot de passe 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Destinataire
        $mail->setFrom('pcs.pa2a1@yahoo.com', 'PCS'); // Remplacez par votre adresse et nom
        $mail->addAddress($email);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Votre code de validation';
        $mail->Body    = "Votre code de validation est : $code";
        $mail->AltBody = "Votre code de validation est : $code";

        // Activer le mode débogage , 1 client, 2 client - serveur
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
