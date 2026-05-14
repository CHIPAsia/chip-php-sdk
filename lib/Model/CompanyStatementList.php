<?php

namespace Chip\Model;

class CompanyStatementList implements \JsonSerializable
{
    /** @var CompanyStatement[] */
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
