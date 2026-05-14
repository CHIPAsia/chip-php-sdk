<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# Use PurchaseBuilder for a simpler API
$purchase = \Chip\Builder\PurchaseBuilder::new()
    ->withBrandId($config['brand_id'])
    ->withProduct('Test Product', 100, 1)
    ->withClient('test@example.com', 'John Doe')
    ->withSuccessRedirect($config['basedUrl'] . '/api/redirect.php?success=1')
    ->withFailureRedirect($config['basedUrl'] . '/api/redirect.php?success=0')
    ->withSuccessCallback($config['basedUrl'] . '/api/callback.php')
    ->build();

$result = $chip->createPurchase($purchase);

echo "<h2>Purchase Created via Builder</h2>";
echo "<pre><code>" . json_encode($result, JSON_PRETTY_PRINT) . "</code></pre>";

if ($result && $result->checkout_url) {
    echo "<p><a href='" . $result->checkout_url . "' target='_blank'>Go to Checkout</a></p>";
}
