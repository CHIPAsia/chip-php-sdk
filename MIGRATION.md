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
    $purchase = $chip->getPurchase('nonexistent_id');
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
- `Account` endpoints: `getBalance()`, `getTurnover()`
- `PublicKey` endpoint: `getPublicKey()`
- `Statements` endpoints: `scheduleStatement()`, `listStatements()`, `getStatement()`, `cancelStatement()`
- Expanded `Client` endpoints: `getClient()`, `updateClient()`, `partialUpdateClient()`, `deleteClient()`, `listRecurringTokens()`, `getRecurringToken()`, `deleteRecurringTokenByClient()`
- Expanded `Webhook` endpoints: `listWebhooks()`, `updateWebhook()`, `partialUpdateWebhook()`
- `Purchase::resendInvoice()`

These are purely additive — no existing code needs to change unless you want to use them.
