<div align="center">
    <a href="https://php.net">
        <img
            alt="PHP"
            src="https://www.php.net/images/logos/new-php-logo.svg"
            width="150">
    </a>
</div>

---

## Requirements
* It is required `PHP` version >= 7.2
* `composer` to install dependencies

## Prerequisite
You will need to replace the value on file [config.php](./config.php) with the configuration on your Developer section by logging-in to Merchant Portal with your account.

```php
// config.php
<?php

return [
	'brand_id' => '<<BRAND_ID>>',
	'api_key' => '<<API_KEY>>',
	'endpoint' => 'https://gate.chip-in.asia/api/v1/',
  	'basedUrl' => '<<DOMAIN_URL>>',
  	'webhook_public_key' => "<<WEBHOOK_PUBLIC_KEY" // SHOULD BE WRAPPED IN DOUBLE QUOTES (")
];
```

**BRAND_ID**

Obtain your BRAND_ID from Developer section.

---
**API_KEY**

Obtain your API_KEY from Developer section.

---

**WEBHOOK_PUBLIC_KEY**

Obtain your `WEBHOOK_PUBLIC_KEY` from Developer section. You can register the URL from [API](https://developer.chip-in.asia/api) or from Merchant Portal on Developer section.

---

**DOMAIN_URL**

It is your domain URL

## Run Example
1. Install dependencies:
```bash
composer install
```

2. Run application locally:
```bash
php -S localhost:7001 
```

and visit [localhost:7001](http://localhost:7001) on your browser.
To test `/api/callback.php` & `/api/webhook.php` to be called from our server, make sure it is connected to internet (exposed to outside). 

We recommend to use [ngrok](https://ngrok.com/) if you want to run locally for debugging. Then You can replace `DOMAIN_URL` with generated URL by `ngrok`. 

`NB: Use it at your own risk. Make sure do not expose your critical port.`
