<?php

  require_once '../vendor/autoload.php';

  $config = include('../config.php');

  $chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

  # Option 1: Use success_callback parameter of the Purchase object
  $post = file_get_contents('php://input'); # lib/Model/Purchase.php
  $headers = getallheaders();
  $xSignature = $headers["X-Signature"];

  $data = json_decode($post);

  # GET PUBLIC KEY
  $publicKey = $config['webhook_public_key'];

  $verify = \Chip\ChipApi::verify($post, $xSignature, $publicKey);
  error_log("/webhook EVENT: " . $data->event_type);
  error_log("/webhook VERIFIED: " . ($verify ? "true" : "false"));
