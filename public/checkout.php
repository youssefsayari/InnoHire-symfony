<?php

require_once '../vendor/autoload.php';
$stripeSecretKey = 'sk_test_51P9BbQKgsQeJx6lcOwpRhmr3TeJCakO6YOYbhK67ToJzdHkcAmIrjxx6R4ZCR14M2SG0FgniTnARnQYcgOx0ZTTg00s8zc9NTW';
\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost:8000';

// Récupérer la quantité depuis le formulaire
$montant = $_POST['montant'] * 100 * 0.32;


$wallet_id = $_POST['wallet_id'];

// Créez d'abord un produit
$product = \Stripe\Product::create([
    'name' => 'Recharger My Wallet', // Nom de votre produit
]);

// Créez ensuite un prix associé à ce produit
$price = \Stripe\Price::create([
    'unit_amount' => $montant, // Montant en cents (20 USD)
    'currency' => 'usd',
    'product' => $product->id, // ID du produit que vous avez créé
]);

// Créez la session de paiement
$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price' => $price->id, // Utilisez l'ID du prix que vous avez créé
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/wallet/rechargerSuccess/' . $wallet_id . '?montant=' . $montant / 100 / 0.32,
    'cancel_url' => $YOUR_DOMAIN . '/wallet/rechargerCancel',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
