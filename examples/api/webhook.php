<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

# Option 1: Use success_callback parameter of the Purchase object
$post = file_get_contents('php://input');
$headers = getallheaders();
$xSignature = $headers['X-Signature'];

# Get public key via SDK
$publicKey = $chip->publicKey->get();

$verify = \Chip\ChipApi::verify($post, $xSignature, $publicKey);

$data = json_decode($post);
error_log('/webhook EVENT: ' . $data->event_type);
error_log('/webhook VERIFIED: ' . ($verify ? 'true' : 'false'));
