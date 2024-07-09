<?php
function remove_accents($string) {
    return strtolower(preg_replace('/[^\w\s]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT', $string)));
}

function clean_input($string) {
    $string = trim($string);
    $string = preg_replace('/\s+/', ' ', $string); 
    $string = rtrim($string, "?"); 
    return $string;
}

function insert_spaces($string) {
    $string = preg_replace('/([a-z])([A-Z])/', '$1 $2', $string);
    $string = preg_replace('/([a-zA-Z])(\d)/', '$1 $2', $string);
    $string = preg_replace('/(\d)([a-zA-Z])/', '$1 $2', $string);
    return $string;
}

$faq = [
    "fr" => [
        remove_accents("comment faire une réservation") => "Pour faire une réservation, cliquez sur le bouton 'Réserver' sur la page de la propriété.",
        remove_accents("quels sont les moyens de paiement acceptés") => "Nous acceptons les cartes de crédit et Stripe.",
        remove_accents("quelles sont les offres disponibles") => "Nous avons trois offres : Free, Bag Packer, et Explorator. Chaque offre a des avantages différents.",
        remove_accents("comment ajouter une location") => "Pour ajouter une location, allez dans la section 'Vos locations' et suivez les instructions."
    ],
    "en" => [
        remove_accents("how to make a reservation") => "To make a reservation, click on the 'Reserve' button on the property's page.",
        remove_accents("what payment methods are accepted") => "We accept credit cards and Stripe.",
        remove_accents("what are the available offers") => "We have three offers: Free, Bag Packer, and Explorator. Each offer has different benefits.",
        remove_accents("how to add a rental") => "To add a rental, go to the 'Your rentals' section and follow the instructions."
    ]
];

$userMessage = clean_input(remove_accents(strtolower(trim($_POST['message']))));
$userMessage = insert_spaces($userMessage);
$language = strtolower(trim($_POST['language']));

if (!in_array($language, ['fr', 'en'])) {
    $language = 'fr';
}

$response = ($language == 'fr') ? "Je ne comprends pas votre question." : "I don't understand your question.";
if (array_key_exists($userMessage, $faq[$language])) {
    $response = $faq[$language][$userMessage];
}

echo json_encode(["response" => $response]);
?>
