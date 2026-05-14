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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $list = new self();
        $list->results = isset($data['results']) && is_array($data['results'])
            ? array_map(fn (array $r) => BillingTemplate::fromArray($r), $data['results'])
            : null;
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
