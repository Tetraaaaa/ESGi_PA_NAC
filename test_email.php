<?php
require 'send_valid_email.php';

$email = 'admeslin04@gmail.com'; // Remplacez par une adresse email de test
$code = rand(100000, 999999);

sendValidationEmail($email, $code);
?>
