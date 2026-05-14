<?php

namespace Chip\Model;

class ClientRecurringToken implements \JsonSerializable
{
    /** @var string */
    public $type;

    /** @var string */
    public $id;

    /** @var int|null */
    public $created_on;

    /** @var int|null */
    public $updated_on;

    /** @var string|null */
    public $client_id;

    /** @var string|null */
    public $purchase_id;

    /** @var string|null */
    public $token;

    /** @var bool|null */
    public $is_deleted;

    /** @var bool|null */
    public $is_test;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
