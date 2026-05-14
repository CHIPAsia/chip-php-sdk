<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# List all statements
$statements = $chip->statements->list();
echo "<h2>Statements</h2>";
echo "<pre><code>" . json_encode($statements, JSON_PRETTY_PRINT) . "</code></pre>";

# Schedule a new statement
$statement = new \Chip\Model\CompanyStatement();
$statement->format = 'json';

$scheduled = $chip->statements->schedule($statement);
echo "<h2>Scheduled Statement</h2>";
echo "<pre><code>" . json_encode($scheduled, JSON_PRETTY_PRINT) . "</code></pre>";
