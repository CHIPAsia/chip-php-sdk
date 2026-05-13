<?php

use Chip\Model\Billing\BillingTemplate;
use Chip\Model\Billing\BillingTemplateClient;

require_once 'vendor/autoload.php';
$env = parse_ini_file('.env');

$purchase_id = "14483a7f-2bde-4e9d-a3d2-ffa6e09e72d7";
$billing_template_id = "e0aff507-a282-410b-8c82-a74d1cc44fd1";

$chip = new \Chip\ChipApi($env["BRAND_ID"], $env["API_KEY"], 'https://gate.chip-in.asia/api/v1/');
// $client = new \Chip\Model\ClientDetails();
// $client->email = 'test@example.com';
// $purchase = new \Chip\Model\Purchase();
// $purchase->client = $client;
// $details = new \Chip\Model\PurchaseDetails();
// $product = new \Chip\Model\Product();
// $product->name = 'test product ' . date('Y-m-d H:i:s e');
// $product->price = 100;
// $details->products = [$product];
// $purchase->purchase = $details;
// $purchase->brand_id = $env["BRAND_ID"];
// $purchase->success_redirect = 'https://yourdomain.com/redirect.php?success=1';
// $purchase->success_callback = 'https://yourdomain.com/callback.php?success=0';
// $purchase->failure_redirect = 'https://yourdomain.com/redirect.php?success=0';

/** Billing */
$billing = new BillingTemplate();

// $client = new BillingTemplateClient();
// $client->client_id = "6c761052-b64e-48f4-a68b-b0b7ef1935a2";
// $billing->clients = [$client];

$purchaseDetails = new \Chip\Model\PurchaseDetails();
$product = new \Chip\Model\Product();
$product->name = 'test product ' . date('Y-m-d H:i:s e');
$product->price = 100;
$purchaseDetails->products = [$product];
$billing->purchase = $purchaseDetails;
// $billing->is_subscription = true;
$billing->brand_id = $env["BRAND_ID"];
// $billing->invoice_due = time() + 100;
// $billing->title = 'test createBillingTemplate ' . date('Y-m-d H:i:s e');
// $result = $chip->getClients();
// $result = $chip->createBilling($billing);

// $billing->subscription_period = 1;
// $billing->subscription_period_units = "months";
$billing->subscription_due_period = 7;
$billing->subscription_due_period_units = "days";
// $billing->subscription_charge_period_end = true;
// $billing->subscription_trial_periods = 1;
$billing->subscription_active = true;
// $result = $chip->createBillingTemplate($billing);

$result = $chip->getBillingTemplates();

// $result = $chip->getBillingTemplate("bab94920-5078-4b08-9cbc-13e78549652f");

// $billing->title = 'test updateBillingTemplate ' . date('Y-m-d H:i:s e');
// $result = $chip->updateBillingTemplate("bab94920-5078-4b08-9cbc-13e78549652f", $billing);

// $result = $chip->deleteBillingTemplate("d2ee0722-c3ac-40da-a3f3-b9cd8084c596");

// $billing_client = new BillingTemplateClient();
// $billing_client->client_id = "6c761052-b64e-48f4-a68b-b0b7ef1935a2";
// $result = $chip->sendBillingTemplateInvoice("d2ee0722-c3ac-40da-a3f3-b9cd8084c596", $billing_client);

// $billing_client = new BillingTemplateClient();
// $billing_client->client_id = "6c761052-b64e-48f4-a68b-b0b7ef1935a2";
// $result = $chip->addBillingTemplateSubscriber("d2ee0722-c3ac-40da-a3f3-b9cd8084c596", $billing_client);

// $result = $chip->getBillingTemplateClients("d2ee0722-c3ac-40da-a3f3-b9cd8084c596");

// $result = $chip->getBillingTemplateClient("d2ee0722-c3ac-40da-a3f3-b9cd8084c596", "3efa3608-67cf-40fc-bed4-b55da4c87eb8");

$billing_client = new BillingTemplateClient();
$billing_client->status = 'active';
$result = $chip->updateBillingTemplateClient("d2ee0722-c3ac-40da-a3f3-b9cd8084c596", "3efa3608-67cf-40fc-bed4-b55da4c87eb8", $billing_client);

var_dump($result);
