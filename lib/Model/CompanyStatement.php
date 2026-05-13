<?php

namespace Chip\Model;

class CompanyStatement implements \JsonSerializable
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
    public $format;

    /** @var string|null */
    public $timezone;

    /** @var bool|null */
    public $is_test;

    /** @var string|null */
    public $company_uid;

    /** @var string|null */
    public $query_string;

    /** @var string|null */
    public $status;

    /** @var string|null */
    public $download_url;

    /** @var int|null */
    public $began_on;

    /** @var int|null */
    public $finished_on;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
