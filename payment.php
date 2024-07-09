<?php
session_start();
require_once 'include/connection_db.php';
require 'vendor/autoload.php';
include_once 'init.php'; 

use Stripe\Stripe;
use Stripe\PaymentIntent;

function truncate_text($text, $chars = 150) {
    if (strlen($text) > $chars) {
        $text = substr($text, 0, $chars) . "…";
    }
    return $text;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['selectedDates'], $_POST['logementId'], $_POST['totalCost'])) {
        header("HTTP/1.1 400 Bad Request");
        header("Location: reservation_error.php?error=invalidData");
        exit;
    }

    
    $selectedDates = explode(',', $_POST['selectedDates']);
    $logementId = $_POST['logementId'];
    $totalCost = (float) $_POST['totalCost'];

    
    if (empty($selectedDates) || empty($logementId) || empty($totalCost)) {
        header("HTTP/1.1 400 Bad Request");
        header("Location: reservation_error.php?error=invalidData");
        exit;
    }

    
    Stripe::setApiKey("sk_test_51PFa0VDDadRJj5EqSM5Y9JhPtnVfEKuNbtLbcQhbYg7gMLuuBv4TPej0ZhXwb9ItJC2KNOY7Aurnh79Hb0f7PS7D00vxo59W5m");

    try {
        
        $paymentIntent = PaymentIntent::create([
            'amount' => $totalCost * 100, 
            'currency' => 'eur',
        ]);

        $clientSecret = $paymentIntent->client_secret;

        
        $_SESSION['selectedDates'] = $selectedDates;
        $_SESSION['logementId'] = $logementId;
        $_SESSION['totalCost'] = $totalCost;
        $_SESSION['paymentIntentId'] = $paymentIntent->id;
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        header("Location: reservation_error.php?error=payment");
        exit;
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    header("Location: reservation_error.php?error=invalidRequest");
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations['Paiement']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/payment.css"> 
</head>
<body>
    <?php require_once 'header.php'; ?>
    <main class="container">
        <h1 class="text-center"><?php echo $translations['Page de paiement']; ?></h1>
        <form id="payment-form" class="payment-form">
            <div class="mb-3">
                <label for="cardholder-name" class="form-label"><?php echo $translations['Nom du titulaire de la carte']; ?></label>
                <input type="text" id="cardholder-name" class="form-control" required>
            </div>
            <div id="card-element" class="mb-3">
                
            </div>
            <button id="submit" class="btn btn-primary mt-4"><?php echo $translations['Payer']; ?></button>
        </form>
        <div id="error-message" class="mt-3 text-danger"></div>
    </main>
    <?php require_once 'footer.php'; ?>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('pk_test_51PFa0VDDadRJj5EqsPvLN0v2RU3Rxz6Y95qgsK4rWEZTAfQgfo86k4PXYU2RyHV0QRgK4fqCVKUkBIqcUGU5uWrj00niWyaDhd');
        const elements = stripe.elements();

        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        const card = elements.create('card', {style: style});
        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const errorMessage = document.getElementById('error-message');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const cardholderName = document.getElementById('cardholder-name').value;

            const {error, paymentIntent} = await stripe.confirmCardPayment('<?= $clientSecret ?>', {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: cardholderName,
                    },
                }
            });

            if (error) {
                errorMessage.textContent = error.message;
            } else if (paymentIntent.status === 'succeeded') {
                window.location.href = 'reservation_verif.php';
            } else {
                window.location.href = 'reservation_error.php?error=payment';
            }
        });
    </script>
</body>
</html>
