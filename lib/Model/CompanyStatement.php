<?php

namespace Chip\Model;

class CompanyStatement implements \JsonSerializable
{
    /** @var string */
    public $id;

    /** @var string */
    public $format;

    /** @var string */
    public $status;

    /** @var int|null */
    public $created_on;

    /** @var int|null */
    public $updated_on;

    /** @var string|null */
    public $url;

    /** @var string|null */
    public $company_id;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
