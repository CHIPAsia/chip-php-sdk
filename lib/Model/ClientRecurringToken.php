<?php

namespace Chip\Model;

class ClientRecurringToken implements \JsonSerializable
{
    /** @var string|null */
    public $type;

    /** @var string|null */
    public $id;

    /** @var int|null */
    public $created_on;

    /** @var int|null */
    public $updated_on;

    /** @var string|null */
    public $payment_method;

    /** @var string|null */
    public $description;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
