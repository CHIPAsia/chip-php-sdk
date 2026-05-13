<?php

namespace Chip\Model\Billing;

class BillingTemplateList implements \JsonSerializable
{

  /**
   *
   * @var BillingTemplate[]
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
