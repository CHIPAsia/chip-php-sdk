<?php

namespace Chip\Model\Billing;

class BillingTemplateClientList implements \JsonSerializable
{
    /** @var BillingTemplateClient[]|null */
    public $results;

    /** @var string|null */
    public $next;

    /** @var string|null */
    public $previous;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
