<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# List all webhooks
$webhooks = $chip->webhooks->list();
echo "<h2>Webhooks</h2>";
echo "<pre><code>" . json_encode($webhooks, JSON_PRETTY_PRINT) . "</code></pre>";

# Create a webhook
$webhook = new \Chip\Model\Webhook();
$webhook->title = 'My Webhook';
$webhook->callback = $config['basedUrl'] . '/api/webhook.php';

$created = $chip->webhooks->create($webhook);
echo "<h2>Created Webhook</h2>";
echo "<pre><code>" . json_encode($created, JSON_PRETTY_PRINT) . "</code></pre>";

# Update webhook
$webhookId = $created->id;
$webhook->callback = $config['basedUrl'] . '/api/callback.php';
$updated = $chip->webhooks->update($webhookId, $webhook);
echo "<h2>Updated Webhook</h2>";
echo "<pre><code>" . json_encode($updated, JSON_PRETTY_PRINT) . "</code></pre>";
