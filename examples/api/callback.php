<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# Use SDK instead of raw curl
$publicKey = $chip->getPublicKey();

$post = file_get_contents('php://input');
$headers = getallheaders();
$xSignature = $headers['X-Signature'];

$verify = \Chip\ChipApi::verify($post, $xSignature, $publicKey);

$data = json_decode($post);
error_log('/callback EVENT: ' . $data->event_type);
error_log('/callback VERIFIED: ' . ($verify ? 'true' : 'false'));
