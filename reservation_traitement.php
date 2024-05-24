<?php
session_start();
include 'include/db.php';
include "apikey.php"; // Fournit la clé d'API Stripe

$errors = []; // Tableau pour stocker les erreurs

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logement_id = $_POST['logement_id'];
    $date = $_POST['date'];
    $prix = $_POST['prix_total'];
    $token = $_POST['stripeToken'];

    try {
        $charge = \Stripe\Charge::create([
            'amount' => $prix * 100, // Montant en centimes
            'currency' => 'eur',
            'source' => $token,
            'description' => 'Paiement pour réservation de logement ' . $logement_id // Correction de la concaténation
        ]);

        // Rediriger vers une page de confirmation si le paiement est réussi
        header("Location: confirmation.php");
        exit();
    } catch (\Stripe\Exception\CardException $e) {
        $errors[] = $e->getError()->message;
    } catch (\Stripe\Exception\RateLimitException $e) {
        $errors[] = "Une erreur est survenue. Veuillez réessayer plus tard.";
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        $errors[] = "Une erreur est survenue. Veuillez réessayer plus tard.";
    } catch (\Stripe\Exception\AuthenticationException $e) {
        $errors[] = "Une erreur est survenue. Veuillez réessayer plus tard.";
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        $errors[] = "Une erreur est survenue. Veuillez réessayer plus tard.";
    }
}

// Afficher les erreurs
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error) . '</div>';
    }
}
?>
