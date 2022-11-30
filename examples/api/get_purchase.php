<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

$purchase_id = ''; # ID of the purchase: $purchase->id;

$purchase = $chip->getPurchase($purchase_id);

print json_encode($purchase);