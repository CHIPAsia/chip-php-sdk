<?php

namespace Chip\Model\Billing;

class BillingTemplateList implements \JsonSerializable
{
    /** @var BillingTemplate[]|null */
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
