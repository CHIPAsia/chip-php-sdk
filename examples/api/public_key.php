<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);


# GET PUBLIC KEY
$url = $config['endpoint'] . "public_key/";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
  "Authorization: Bearer " . $config['api_key'],
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$publicKey = json_decode(curl_exec($curl));
curl_close($curl);

header("Content-Type: application/json");
echo $publicKey;
