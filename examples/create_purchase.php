<?php

require_once '../vendor/autoload.php';

$config = include('config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

$client = new \Chip\Model\ClientDetails();
$client->email = 'test@example.com';
$purchase = new \Chip\Model\Purchase();
$purchase->client = $client;
$details = new \Chip\Model\PurchaseDetails();
$product = new \Chip\Model\Product();
$product->name = 'Test';
$product->price = 100;
$details->products = [$product];
$purchase->purchase = $details;
$purchase->brand_id = $config['brand_id'];
$purchase->success_redirect = 'http://localhost:7001/redirect.php?success=1';
$purchase->failure_redirect = 'http://localhost:7001/redirect.php?success=0';
$purchase->success_callback = 'https://f19e-2001-f40-90f-ed2-7cc9-7c6b-95d7-d061.ap.ngrok.io/callback.php?success=1';

$result = $chip->createPurchase($purchase);

if ($result && $result->checkout_url) {
	// Redirect user to checkout
	header("Location: " . $result->checkout_url);
	exit;
}