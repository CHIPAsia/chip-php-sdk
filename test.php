<?php
  require_once 'vendor/autoload.php';
  $id="14483a7f-2bde-4e9d-a3d2-ffa6e09e72d7";
  $env = parse_ini_file('.env');

  $chip = new \Chip\ChipApi($env["BRAND_ID"], $env["API_KEY"], 'https://gate.chip-in.asia/api/v1/');
  // $client = new \Chip\Model\ClientDetails();
  // $client->email = 'test@example.com';
  // $purchase = new \Chip\Model\Purchase();
  // $purchase->client = $client;
  // $details = new \Chip\Model\PurchaseDetails();
  // $product = new \Chip\Model\Product();
  // $product->name = 'Test';
  // $product->price = 100;
  // $details->products = [$product];
  // $purchase->purchase = $details;
  // $purchase->brand_id = $env["BRAND_ID"];
  // $purchase->success_redirect = 'https://yourdomain.com/redirect.php?success=1';
  // $purchase->success_callback = 'https://yourdomain.com/callback.php?success=0';
  // $purchase->failure_redirect = 'https://yourdomain.com/redirect.php?success=0';

  $result = $chip->markAsPaid($id);

  var_dump($result);