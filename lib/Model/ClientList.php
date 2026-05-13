<?php

namespace Chip\Model;

class ClientList implements \JsonSerializable
{

  /**
   *
   * @var ClientDetails[]
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
