<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

$methods = $chip->getPaymentMethods();

echo "<pre><code>" . json_encode($methods, JSON_PRETTY_PRINT) . "</code></pre>";