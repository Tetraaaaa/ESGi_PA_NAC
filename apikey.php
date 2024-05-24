<?php

// Inclure le fichier d'autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Configuration de vos clés d'API Stripe
\Stripe\Stripe::setApiKey("sk_test_51PFa0VDDadRJj5EqSM5Y9JhPtnVfEKuNbtLbcQhbYg7gMLuuBv4TPej0ZhXwb9ItJC2KNOY7Aurnh79Hb0f7PS7D00vxo59W5m");

// Laisser une ligne vide avant le retour du chargeur
// pour des raisons de bonnes pratiques
return ComposerAutoloaderInit5d5b2c2947c4010e58a2e883d2905ef7::getLoader();
