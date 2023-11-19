<?php

namespace Chip\Model\Billing;

class BillingTemplateClientAddSubscriber implements \JsonSerializable
{

  #BillingTemplateClient
  public $billing_template_client;

  #Purchase
  public $purchase;

  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return array_filter((array) $this);
  }
}
