<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

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
$purchase->success_redirect = $config['basedUrl'] . '/api/redirect.php?success=1';
$purchase->failure_redirect = $config['basedUrl'] . '/api/redirect.php?success=0';
$purchase->success_callback = $config['basedUrl'] . '/api/callback.php';

$result = $chip->createPurchase($purchase);

if ($result && $result->checkout_url) {
	// Redirect user to checkout
	header("Location: " . $result->checkout_url);
	exit;
}