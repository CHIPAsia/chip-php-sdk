<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# Use PurchaseBuilder for a simpler API
$purchase = \Chip\Builder\PurchaseBuilder::create()
    ->brandId($config['brand_id'])
    ->currency('MYR')
    ->language('en')
    ->clientEmail('test@example.com')
    ->clientFullName('John Doe')
    ->addProduct('Test Product', 100)
    ->paymentMethodWhitelist(['visa', 'mastercard'])
    ->successRedirect($config['basedUrl'] . '/api/redirect.php?success=1')
    ->failureRedirect($config['basedUrl'] . '/api/redirect.php?success=0')
    ->successCallback($config['basedUrl'] . '/api/callback.php')
    ->build();

$result = $chip->purchases->create($purchase);

echo "<h2>Purchase Created via Builder</h2>";
echo "<pre><code>" . json_encode($result, JSON_PRETTY_PRINT) . "</code></pre>";

if ($result && $result->checkout_url) {
    echo "<p><a href='" . $result->checkout_url . "' target='_blank'>Go to Checkout</a></p>";
}
