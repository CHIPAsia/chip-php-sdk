<?php

  require_once '../vendor/autoload.php';

  $config = include('config.php');

  $chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

  # Option 1: Use success_callback parameter of the Purchase object
  $post = json_decode(file_get_contents('php://input')); # lib/Model/Purchase.php
  $headers = getallheaders();
  $xSignature = $headers["X-Signature"];

  $verify = \Chip\ChipApi::verify(json_encode($post, JSON_PRETTY_PRINT), $xSignature, $publicKey);

  # Option 2: Use GET /purchases/<purchase_id>/ request
  $purchase = $chip->getPurchase($post->id);

  openlog("phperrors", LOG_PID | LOG_PERROR, 0);
  syslog(LOG_ERR, json_encode($verify));
  syslog(LOG_PID, json_encode($post));
  syslog(LOG_PID, $xSignature);
  syslog(LOG_PID, $publicKey);
  closelog();