<?php

namespace Chip\Model\Billing;

class BillingTemplateClientList implements \JsonSerializable
{
    /** @var BillingTemplateClient[]|null */
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
        $results = $data['results'] ?? null;
        $list->results = is_array($results)
            ? array_values(array_map(
                fn (array $r) => BillingTemplateClient::fromArray($r),
                array_filter($results, 'is_array')
            ))
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
