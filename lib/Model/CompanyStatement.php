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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $statement = new self();
        $statement->type = $data['type'] ?? null;
        $statement->id = $data['id'] ?? null;
        $statement->created_on = $data['created_on'] ?? null;
        $statement->updated_on = $data['updated_on'] ?? null;
        $statement->format = $data['format'] ?? null;
        $statement->timezone = $data['timezone'] ?? null;
        $statement->is_test = $data['is_test'] ?? null;
        $statement->company_uid = $data['company_uid'] ?? null;
        $statement->query_string = $data['query_string'] ?? null;
        $statement->status = $data['status'] ?? null;
        $statement->download_url = $data['download_url'] ?? null;
        $statement->began_on = $data['began_on'] ?? null;
        $statement->finished_on = $data['finished_on'] ?? null;

        return $statement;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
