<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

$purchaseId = ''; # ID of the purchase: $purchase->id;

$purchase = $chip->getPurchase($purchaseId);

header('Content-Type: application/json');
echo json_encode($purchase, JSON_PRETTY_PRINT);
