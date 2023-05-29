<?php

namespace Chip\Model\Billing;

class BillingTemplateClientList implements \JsonSerializable
{

  /**
   *
   * @var BillingTemplateClient[]
   */
  public $results;

  public $next;
  public $previous;

  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return array_filter((array) $this);
  }
}
