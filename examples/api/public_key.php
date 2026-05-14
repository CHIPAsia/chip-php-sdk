<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# Get public key via SDK
$publicKey = $chip->getPublicKey();

header('Content-Type: application/json');
echo json_encode(['public_key' => $publicKey]);
