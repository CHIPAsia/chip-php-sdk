# CHIP PHP SDK

[![CI](https://github.com/CHIPAsia/chip-php-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/CHIPAsia/chip-php-sdk/actions)
[![Latest Stable Version](https://poser.pugx.org/chip/chip-sdk-php/v/stable)](https://packagist.org/packages/chip/chip-sdk-php)
[![License](https://poser.pugx.org/chip/chip-sdk-php/license)](https://packagist.org/packages/chip/chip-sdk-php)

Official PHP SDK for [CHIP](https://chip-in.asia) payment platform.

## Requirements

- PHP ^8.0
- Extensions: `curl`, `json`, `openssl`

## Installation

```bash
composer require chip/chip-sdk-php
```

## Quick Start

```php
use Chip\ChipApi;
use Chip\Builder\PurchaseBuilder;

$chip = new ChipApi('YOUR_BRAND_ID', 'YOUR_API_KEY');

$purchase = PurchaseBuilder::create()
    ->brandId('YOUR_BRAND_ID')
    ->currency('MYR')
    ->language('en')
    ->clientEmail('customer@example.com')
    ->clientFullName('John Doe')
    ->addProduct('Widget', 5000, 2)
    ->successRedirect('https://yourdomain.com/success')
    ->failureRedirect('https://yourdomain.com/failure')
    ->successCallback('https://yourdomain.com/webhook')
    ->build();

$result = $chip->createPurchase($purchase);

if ($result->checkout_url) {
    header('Location: ' . $result->checkout_url);
    exit;
}
```

## Authentication

You need a `Brand ID` and `API Key` from the [CHIP Merchant Portal](https://gate.chip-in.asia/login).

```php
$chip = new ChipApi('YOUR_BRAND_ID', 'YOUR_API_KEY');
```

Optional parameters:

```php
$chip = new ChipApi(
    brandId: 'YOUR_BRAND_ID',
    apiKey: 'YOUR_API_KEY',
    base: 'https://gate.chip-in.asia/api/v1/', // optional, default shown
    config: ['timeout' => 30],                  // optional Guzzle config
    logger: $psr3Logger                         // optional PSR-3 logger
);
```

## API Methods

### Purchases

```php
// Create a purchase
$purchase = $chip->createPurchase($purchaseModel);

// Get purchase details
$purchase = $chip->getPurchase('purchase_id');

// Cancel a purchase
$purchase = $chip->cancelPurchase('purchase_id');

// Release a purchase
$purchase = $chip->releasePurchase('purchase_id');

// Capture payment (full or partial)
$purchase = $chip->capturePurchase('purchase_id');
$purchase = $chip->capturePurchase('purchase_id', 5000); // partial

// Refund (full or partial)
$purchase = $chip->refundPurchase('purchase_id');
$purchase = $chip->refundPurchase('purchase_id', 2500); // partial

// Charge with recurring token
$purchase = $chip->chargePurchase('purchase_id', 'recurring_token');

// Delete recurring token
$purchase = $chip->deleteRecurringToken('purchase_id');
```

### Payment Methods

```php
$methods = $chip->getPaymentMethods('MYR');
```

### Clients

```php
$client = new \Chip\Model\ClientDetails();
$client->email = 'customer@example.com';
$result = $chip->createClient($client);
```

### Webhooks

```php
// Create a webhook
$webhook = new \Chip\Model\Webhook();
$webhook->title = 'My Webhook';
$webhook->callback = 'https://yourdomain.com/webhook';
$webhook->events = ['purchase.paid', 'purchase.created'];
$result = $chip->createWebhook($webhook);

// Get webhook details
$webhook = $chip->getWebhook('webhook_id');
```

## Error Handling

The SDK throws domain-specific exceptions:

```php
use Chip\Exception\AuthenticationException;
use Chip\Exception\NotFoundException;
use Chip\Exception\ValidationException;
use Chip\Exception\ServerException;
use Chip\Exception\ClientException;

try {
    $purchase = $chip->getPurchase('nonexistent_id');
} catch (NotFoundException $e) {
    // 404 - Purchase not found
    echo $e->getMessage();
} catch (AuthenticationException $e) {
    // 401 - Invalid API key
} catch (ValidationException $e) {
    // 422 - Validation failed
    print_r($e->getErrors());
} catch (ServerException $e) {
    // 5xx - Server error
} catch (ClientException $e) {
    // Other 4xx errors
}
```

All exceptions extend `ChipApiException` and expose the response body:

```php
try {
    $chip->createPurchase($purchase);
} catch (ChipApiException $e) {
    $statusCode = $e->getCode();
    $responseBody = $e->getResponseBody();
}
```

## Webhook Verification

Verify webhook signatures using your public key:

```php
$isValid = ChipApi::verify($jsonPayload, $signatureHeader, $publicKey);
```

## Purchase Builder

The `PurchaseBuilder` provides a fluent API for constructing purchases:

```php
use Chip\Builder\PurchaseBuilder;

$purchase = PurchaseBuilder::create()
    ->brandId('YOUR_BRAND_ID')
    ->currency('MYR')
    ->language('en')
    ->clientEmail('customer@example.com')
    ->clientFullName('John Doe')
    ->clientPhone('+60123456789')
    ->addProduct('Widget', 5000, 2)
    ->addProduct('Gadget', 3000)
    ->successRedirect('https://yourdomain.com/success')
    ->failureRedirect('https://yourdomain.com/failure')
    ->successCallback('https://yourdomain.com/webhook')
    ->cancelRedirect('https://yourdomain.com/cancel')
    ->build();
```

## Logging

Pass a PSR-3 compatible logger to enable request/response logging:

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('chip');
$logger->pushHandler(new StreamHandler('chip.log'));

$chip = new ChipApi('BRAND_ID', 'API_KEY', logger: $logger);
```

## Development

```bash
# Install dependencies
composer install

# Run tests
composer test

# Run static analysis
composer phpstan

# Check code style
composer cs-check

# Fix code style
composer cs-fix
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## License

MIT License. See [LICENSE](LICENSE) for details.
