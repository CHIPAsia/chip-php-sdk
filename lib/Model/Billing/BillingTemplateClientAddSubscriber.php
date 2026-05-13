<?php

namespace Chip\Model\Billing;

class BillingTemplateClientAddSubscriber implements \JsonSerializable
{

  /**
  *
  * @var \Chip\Model\Billing\BillingTemplateClient
  */
  public $billing_template_client;

  /**
  *
  * @var \Chip\Model\Purchase
  */
  public $purchase;

  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return array_filter((array) $this);
  }
}
