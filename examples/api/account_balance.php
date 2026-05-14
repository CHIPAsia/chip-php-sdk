<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

$balance = $chip->account->balance();

echo "<pre><code>" . json_encode($balance, JSON_PRETTY_PRINT) . "</code></pre>";
