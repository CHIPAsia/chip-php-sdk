# CHIP PHP SDK

[![CI](https://github.com/CHIPAsia/chip-php-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/CHIPAsia/chip-php-sdk/actions)
<!-- Packagist badges - uncomment once published:
[![Latest Stable Version](https://poser.pugx.org/chip/chip-sdk-php/v/stable)](https://packagist.org/packages/chip/chip-sdk-php)
[![License](https://poser.pugx.org/chip/chip-sdk-php/license)](https://packagist.org/packages/chip/chip-sdk-php)
-->

Official PHP SDK for [CHIP](https://chip-in.asia) payment platform.

## Requirements

- PHP ^8.1
- Extensions: `curl`, `json`, `openssl`

## Upgrading from 1.x

See [MIGRATION.md](MIGRATION.md) for a detailed guide on breaking changes when upgrading from 1.x to 2.0.0.

## Prerequisite

Before you start, make sure you already have created `Brand ID` and `API Key` from your developer dashboard by logging-in into [merchant portal](https://gate.chip-in.asia/login).

## Installation

The package is not yet published on Packagist. Install via VCS repository:

```bash
composer config repositories.chip-sdk vcs https://github.com/CHIPAsia/chip-php-sdk.git
composer require chip/chip-sdk-php:^2.0
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

$result = $chip->purchases->create($purchase);

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

The SDK is organized into resource objects accessed via properties on `ChipApi`:

- `$chip->purchases`
- `$chip->clients`
- `$chip->webhooks`
- `$chip->paymentMethods`
- `$chip->account`
- `$chip->statements`
- `$chip->publicKey`
- `$chip->billing`

### Purchases

```php
// Create a purchase
$purchase = $chip->purchases->create($purchaseModel);

// Get purchase details
$purchase = $chip->purchases->get('purchase_id');

// Cancel a purchase
$purchase = $chip->purchases->cancel('purchase_id');

// Release a purchase
$purchase = $chip->purchases->release('purchase_id');

// Capture payment (full or partial)
$purchase = $chip->purchases->capture('purchase_id');
$purchase = $chip->purchases->capture('purchase_id', 5000); // partial

// Refund (full or partial)
$purchase = $chip->purchases->refund('purchase_id');
$purchase = $chip->purchases->refund('purchase_id', 2500); // partial

// Charge with recurring token
$purchase = $chip->purchases->charge('purchase_id', 'recurring_token');

// Delete recurring token
$purchase = $chip->purchases->deleteRecurringToken('purchase_id');

// Resend invoice
$purchase = $chip->purchases->resendInvoice('purchase_id');
```

### Payment Methods

```php
$methods = $chip->paymentMethods->list('MYR');

// Optional filters
$methods = $chip->paymentMethods->list('MYR', [
    'country' => 'MY',
    'recurring' => true,
    'amount' => 500,
]);
```

### Clients

```php
// Create a client
$client = new \Chip\Model\ClientDetails();
$client->email = 'customer@example.com';
$client->full_name = 'John Doe';
$created = $chip->clients->create($client);

// List all clients
$clients = $chip->clients->list();

// Iterate all clients (auto-paginates)
foreach ($chip->clients->iterate() as $client) {
    echo $client->email;
}

// Retrieve a client
$client = $chip->clients->get($clientId);

// Update a client
$updated = $chip->clients->update($clientId, $client);

// Partially update a client
$updated = $chip->clients->partialUpdate($clientId, $client);

// Delete a client
$chip->clients->delete($clientId);

// List recurring tokens for a client
$tokens = $chip->clients->listRecurringTokens($clientId);

// Get a specific recurring token
$token = $chip->clients->getRecurringToken($clientId, $purchaseId);

// Delete a recurring token
$chip->clients->deleteRecurringToken($clientId, $purchaseId);
```

### Webhooks

```php
// List all webhooks
$webhooks = $chip->webhooks->list();

// Iterate all webhooks (auto-paginates)
foreach ($chip->webhooks->iterate() as $webhook) {
    echo $webhook->title;
}

// Create a webhook
$webhook = new \Chip\Model\Webhook();
$webhook->title = 'My Webhook';
$webhook->callback = 'https://yourdomain.com/webhook';
$created = $chip->webhooks->create($webhook);

// Get webhook details
$webhook = $chip->webhooks->get($webhookId);

// Update a webhook
$updated = $chip->webhooks->update($webhookId, $webhook);

// Partially update a webhook
$updated = $chip->webhooks->partialUpdate($webhookId, $webhook);

// Delete a webhook
$chip->webhooks->delete($webhookId);
```

### Account

```php
// Get account balance (with optional filters)
$balance = $chip->account->balance();
$balance = $chip->account->balance(['currency' => 'MYR']);

// Get account turnover
$turnover = $chip->account->turnover(['from' => 1609459200, 'to' => 1640995200]);
```

### Statements

```php
// Schedule a company statement
$statement = new \Chip\Model\CompanyStatement();
$statement->format = 'csv';
$scheduled = $chip->statements->schedule($statement);

// List statements
$statements = $chip->statements->list();

// Iterate statements (auto-paginates)
foreach ($chip->statements->iterate() as $statement) {
    echo $statement->format;
}

// Get a statement
$statement = $chip->statements->get($statementId);

// Cancel a statement
$statement = $chip->statements->cancel($statementId);
```

### Public Key

```php
$publicKey = $chip->publicKey->get();
```

### Billing

```php
// Create a billing template
$template = new \Chip\Model\Billing\BillingTemplate();
$template->title = 'Monthly Subscription';
$created = $chip->billing->createTemplate($template);

// List billing templates
$templates = $chip->billing->listTemplates();

// Iterate billing templates (auto-paginates)
foreach ($chip->billing->iterateTemplates() as $template) {
    echo $template->title;
}

// Get a billing template
$template = $chip->billing->getTemplate($templateId);

// Update a billing template
$updated = $chip->billing->updateTemplate($templateId, $template);

// Delete a billing template
$chip->billing->deleteTemplate($templateId);

// Send an invoice from a billing template
$client = new \Chip\Model\Billing\BillingTemplateClient();
$client->client_id = $clientId;
$purchase = $chip->billing->sendInvoice($templateId, $client);

// Add a subscriber
$result = $chip->billing->addSubscriber($templateId, $client);

// List billing template clients
$clients = $chip->billing->listClients($templateId);

// Iterate billing template clients (auto-paginates)
foreach ($chip->billing->iterateClients($templateId) as $client) {
    echo $client->client_id;
}

// Get a billing template client
$client = $chip->billing->getClient($templateId, $clientId);

// Update a billing template client
$updated = $chip->billing->updateClient($templateId, $clientId, $client);
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
    $purchase = $chip->purchases->get('nonexistent_id');
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
    $chip->purchases->create($purchase);
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
