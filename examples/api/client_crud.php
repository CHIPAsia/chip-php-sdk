<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# Create a client
$client = new \Chip\Model\ClientDetails();
$client->email = 'client@example.com';
$client->full_name = 'John Doe';

$created = $chip->clients->create($client);
echo "<h2>Created Client</h2>";
echo "<pre><code>" . json_encode($created, JSON_PRETTY_PRINT) . "</code></pre>";

# Get all clients
$clients = $chip->clients->list();
echo "<h2>All Clients</h2>";
echo "<pre><code>" . json_encode($clients, JSON_PRETTY_PRINT) . "</code></pre>";

# Update client
$clientId = $created->id;
$client->full_name = 'Jane Doe';
$updated = $chip->clients->update($clientId, $client);
echo "<h2>Updated Client</h2>";
echo "<pre><code>" . json_encode($updated, JSON_PRETTY_PRINT) . "</code></pre>";
