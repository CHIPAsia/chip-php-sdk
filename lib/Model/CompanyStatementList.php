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
        $results = $data['results'] ?? [];
        $list->results = is_array($results)
            ? array_values(array_map(
                fn (array $s) => CompanyStatement::fromArray($s),
                array_filter($results, 'is_array')
            ))
            : [];
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
