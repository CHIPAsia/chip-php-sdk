<?php

namespace Chip\Model;

class WebhookList implements \JsonSerializable
{
    /** @var Webhook[]|null */
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
                fn (array $w) => Webhook::fromArray($w),
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
