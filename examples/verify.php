<?php
require_once '../vendor/autoload.php';

$config = include('config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

$content = '<<PURCHASE_RESPONSE>>';
$signature = '<<X-SIGNATURE_HEADER>>';

$url = $config['endpoint'] . "public_key/";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "Authorization: Bearer " . $config['api_key'],
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$publicKey = curl_exec($curl);
curl_close($curl);

var_dump(\Chip\ChipApi::verify($content, $signature, $publicKey));
