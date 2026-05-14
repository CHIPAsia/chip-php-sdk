<?php

namespace Chip\Model;

class CompanyStatementList implements \JsonSerializable
{
    /** @var CompanyStatement[]|null */
    public $results;

    /** @var string|null */
    public $next;

    /** @var string|null */
    public $previous;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $list = new self();
        $list->results = array_map(
            fn (array $s) => CompanyStatement::fromArray($s),
            $data['results'] ?? []
        );
        $list->next = $data['next'] ?? null;
        $list->previous = $data['previous'] ?? null;

        return $list;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
