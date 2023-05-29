<?php

namespace Chip\Model\Billing;

class BillingTemplateClientAddSubscriber implements \JsonSerializable
{

  /**
   *
   * @var BillingTemplateClient
   */
  public $billing_template_client;

  /**
   *
   * @var Purchase
   */
  public $purchase;

  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return array_filter((array) $this);
  }
}
