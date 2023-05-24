# Chip PHP library

## Requirements

PHP 8.0 and later.

The following PHP extensions are required:

* curl
* json
* openssl

## Prerequisite
Before you start, make sure you already have created `Brand ID` and `API Key` from your developer dashboard by logging-in into [merchant portal](https://gate.chip-in.asia/login).


## Installation
## Composer

Add the following on your `composer.json`
```php
"repositories": [
  ...
  {
      "type": "vcs",
      "url": "git@github.com:CHIPAsia/chip-php-sdk.git"
  }
],
"require": {
    "chip/chip-sdk-php": "^1.0"
}
```

And run command
```bash
composer install
# OR
composer update
```

## Functions

**getPaymentMethods**
```
Get list of payment methods that available for your account.
```

**createPurchase**
```
Create checkout & direct post URL.
```

**getPurchase**
```
Get purchase detail by its ID
```

**verify**
```
Verify callback or webhook response from our server. For webhook public key, it is generated when you register your webhook URL. Refer to our API (https://developer.chip-in.asia/api) for more information.
```

And more which you can refer to [`/lib/ChipApi.php`](./lib/ChipApi.php) for more information.

## Getting Started

Simple usage looks like:


```php
<?php
  require_once 'vendor/autoload.php';
  $chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);
  $client = new \Chip\Model\ClientDetails();
  $client->email = 'test@example.com';
  $purchase = new \Chip\Model\Purchase();
  $purchase->client = $client;
  $details = new \Chip\Model\PurchaseDetails();
  $product = new \Chip\Model\Product();
  $product->name = 'Test';
  $product->price = 100;
  $details->products = [$product];
  $purchase->purchase = $details;
  $purchase->brand_id = $config['brand_id'];
  $purchase->success_redirect = 'https://yourdomain.com/redirect.php?success=1';
  $purchase->success_callback = 'https://yourdomain.com/callback.php?success=0';
  $purchase->failure_redirect = 'https://yourdomain.com/redirect.php?success=0';

  $result = $chip->createPurchase($purchase);

  if ($result && $result->checkout_url) {
    // Redirect user to checkout
    header("Location: " . $result->checkout_url);
    exit;
  }
```

## Testing

```bash
./vendor/bin/phpunit tests 
```

## Example
Check our examples [here](./examples).
