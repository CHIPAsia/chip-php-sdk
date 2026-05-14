# Migration Guide

## Upgrading from 1.x to 2.0.0

### PHP Version Requirement

The minimum PHP version has been raised from `>=7.2.0` to `^8.1`.

If your project runs on PHP 7.2–8.0, you must upgrade your runtime before installing version 2.0.0.

### Exception Handling

In 1.x, Guzzle HTTP exceptions bubbled up directly. In 2.0.0, `ChipApi` catches all HTTP errors and throws domain-specific exceptions.

**Before (1.x):**

```php
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

try {
    $purchase = $chip->getPurchase('nonexistent_id');
} catch (ClientException $e) {
    $statusCode = $e->getResponse()->getStatusCode();
    $body = json_decode((string) $e->getResponse()->getBody(), true);
}
```

**After (2.0.0):**

```php
use Chip\Exception\AuthenticationException;
use Chip\Exception\NotFoundException;
use Chip\Exception\ValidationException;
use Chip\Exception\ServerException;
use Chip\Exception\ClientException;

try {
    $purchase = $chip->purchases->get('nonexistent_id');
} catch (NotFoundException $e) {
    $statusCode = $e->getCode(); // 404
    $body = $e->getResponseBody(); // decoded array
} catch (ValidationException $e) {
    $errors = $e->getErrors(); // 422 validation errors
} catch (AuthenticationException $e) {
    // 401 - invalid API key
} catch (ServerException $e) {
    // 5xx - server error
} catch (ClientException $e) {
    // other 4xx errors
}
```

All exceptions extend `Chip\Exception\ChipApiException`, which exposes the decoded response body via `getResponseBody()`.

### Model Property Type Changes

Several model properties changed types to match the CHIP API specification.

#### `Product`

| Property | Before | After |
|----------|--------|-------|
| `$quantity` | `float` | `string\|null` |
| `$tax_percent` | `float` | `string\|null` |

**Before (1.x):**

```php
$product = new \Chip\Model\Product();
$product->name = 'Widget';
$product->price = 5000;
$product->quantity = 2.0;
$product->tax_percent = 0.06;
$total = $product->price * $product->quantity; // works
```

**After (2.0.0):**

```php
$product = new \Chip\Model\Product();
$product->name = 'Widget';
$product->price = 5000;
$product->quantity = '2.0';  // now a string
$product->tax_percent = '0.06';  // now a string
$total = $product->price * (float) $product->quantity; // cast needed
```

> The API returns `quantity` and `tax_percent` as strings (e.g. `"1.0000"`, `"0.00"`), so the model now reflects the actual response format.

#### `Purchase`

| Property | Before | After |
|----------|--------|-------|
| `$issued` | `int` | `string\|null` |
| `$status_history` | `object` | `array` |

**Before (1.x):**

```php
$issuedTimestamp = $purchase->issued; // int
$status = $purchase->status_history->status; // object access
```

**After (2.0.0):**

```php
$issuedString = $purchase->issued; // string or null
$status = $purchase->status_history[0]->status; // array access
```

#### `PaymentMethods`

| Property | Before | After |
|----------|--------|-------|
| `$by_country` | `string[][]` | `array` (key-value map) |
| `$country_names` | `string[]` | `array` (key-value map) |
| `$names` | `string[]` | `array` (key-value map) |

These are associative arrays, not sequential. Access remains the same (`$methods->names['fpx']`), but type checks may differ.

### Resource-Based Client API

The biggest architectural change in 2.0.0 is the move from monolithic trait-based methods on `ChipApi` to dedicated resource objects:

**Before (1.x):**

```php
$purchase = $chip->createPurchase($purchase);
$client = $chip->createClient($clientDetails);
$webhook = $chip->createWebhook($webhook);
$methods = $chip->getPaymentMethods('MYR');
$balance = $chip->getBalance();
```

**After (2.0.0):**

```php
$purchase = $chip->purchases->create($purchase);
$client = $chip->clients->create($clientDetails);
$webhook = $chip->webhooks->create($webhook);
$methods = $chip->paymentMethods->list('MYR');
$balance = $chip->account->balance();
```

Available resources:

| Resource | Old Method | New Method |
|----------|-----------|------------|
| `purchases` | `createPurchase()` | `create()` |
| `purchases` | `getPurchase()` | `get()` |
| `purchases` | `cancelPurchase()` | `cancel()` |
| `purchases` | `releasePurchase()` | `release()` |
| `purchases` | `capturePurchase()` | `capture()` |
| `purchases` | `chargePurchase()` | `charge()` |
| `purchases` | `refundPurchase()` | `refund()` |
| `purchases` | `deleteRecurringToken()` | `deleteRecurringToken()` |
| `purchases` | `markAsPaid()` | `markAsPaid()` |
| `purchases` | `resendInvoice()` | `resendInvoice()` |
| `clients` | `createClient()` | `create()` |
| `clients` | `getClient()` | `get()` |
| `clients` | `getClients()` | `list()` |
| `clients` | `updateClient()` | `update()` |
| `clients` | `partialUpdateClient()` | `partialUpdate()` |
| `clients` | `deleteClient()` | `delete()` |
| `clients` | `listRecurringTokens()` | `listRecurringTokens()` |
| `clients` | `getRecurringToken()` | `getRecurringToken()` |
| `clients` | `deleteRecurringTokenByClient()` | `deleteRecurringToken()` |
| `webhooks` | `createWebhook()` | `create()` |
| `webhooks` | `getWebhook()` | `get()` |
| `webhooks` | `listWebhooks()` | `list()` |
| `webhooks` | `updateWebhook()` | `update()` |
| `webhooks` | `partialUpdateWebhook()` | `partialUpdate()` |
| `webhooks` | `deleteWebhook()` | `delete()` |
| `paymentMethods` | `getPaymentMethods()` | `list()` |
| `publicKey` | `getPublicKey()` | `get()` |
| `account` | `getBalance()` | `balance()` |
| `account` | `getTurnover()` | `turnover()` |
| `statements` | `scheduleStatement()` | `schedule()` |
| `statements` | `listStatements()` | `list()` |
| `statements` | `getStatement()` | `get()` |
| `statements` | `cancelStatement()` | `cancel()` |
| `billing` | `createBilling()` | `create()` |
| `billing` | `createBillingTemplate()` | `createTemplate()` |
| `billing` | `getBillingTemplates()` | `listTemplates()` |
| `billing` | `getBillingTemplate()` | `getTemplate()` |
| `billing` | `updateBillingTemplate()` | `updateTemplate()` |
| `billing` | `deleteBillingTemplate()` | `deleteTemplate()` |
| `billing` | `sendBillingTemplateInvoice()` | `sendInvoice()` |
| `billing` | `addBillingTemplateSubscriber()` | `addSubscriber()` |
| `billing` | `getBillingTemplateClients()` | `listClients()` |
| `billing` | `getBillingTemplateClient()` | `getClient()` |
| `billing` | `updateBillingTemplateClient()` | `updateClient()` |

### Pagination Iterators

List endpoints now support automatic pagination iterators:

```php
foreach ($chip->clients->iterate() as $client) {
    echo $client->email;
}

foreach ($chip->webhooks->iterate() as $webhook) {
    echo $webhook->title;
}

foreach ($chip->billing->iterateTemplates() as $template) {
    echo $template->title;
}
```

### New Optional Constructor Parameter

`ChipApi` now accepts an optional PSR-3 logger as the 5th parameter:

```php
$chip = new ChipApi(
    brandId: 'YOUR_BRAND_ID',
    apiKey: 'YOUR_API_KEY',
    base: 'https://gate.chip-in.asia/api/v1/',
    config: ['timeout' => 30],
    logger: $psr3Logger // optional, new in 2.0.0
);
```

Existing 3-argument constructor calls remain backward-compatible.

### New Features Available

Version 2.0.0 adds several new endpoints and helpers that were not available in 1.x:

- `PurchaseBuilder` fluent API
- `Account` endpoints: `$chip->account->balance()`, `$chip->account->turnover()`
- `PublicKey` endpoint: `$chip->publicKey->get()`
- `Statements` endpoints: `$chip->statements->schedule()`, `$chip->statements->list()`, `$chip->statements->get()`, `$chip->statements->cancel()`
- Expanded `Client` endpoints: `$chip->clients->get()`, `$chip->clients->update()`, `$chip->clients->partialUpdate()`, `$chip->clients->delete()`, `$chip->clients->listRecurringTokens()`, `$chip->clients->getRecurringToken()`, `$chip->clients->deleteRecurringToken()`
- Expanded `Webhook` endpoints: `$chip->webhooks->list()`, `$chip->webhooks->update()`, `$chip->webhooks->partialUpdate()`
- `$chip->purchases->resendInvoice()`
- Automatic retry with exponential backoff for 429 and 5xx responses

These are purely additive — no existing code needs to change unless you want to use them.
