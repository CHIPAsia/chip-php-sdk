<?php

namespace Chip\Model;

class Webhook implements \JsonSerializable
{
    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var int
     */
    public $created_on;

    /**
     *
     * @var int
     */
    public $updated_on;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var bool
     */
    public $all_events;

    /**
     *
     * @var string
     */
    public $public_key;

    /**
     *
     * @var string[]
     */
    public $events;

    /**
     *
     * @var string
     */
    public $callback;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $webhook = new self();
        $webhook->type = $data['type'] ?? '';
        $webhook->id = $data['id'] ?? '';
        $webhook->created_on = $data['created_on'] ?? 0;
        $webhook->updated_on = $data['updated_on'] ?? 0;
        $webhook->title = $data['title'] ?? '';
        $webhook->all_events = $data['all_events'] ?? false;
        $webhook->public_key = $data['public_key'] ?? '';
        $webhook->events = $data['events'] ?? [];
        $webhook->callback = $data['callback'] ?? '';

        return $webhook;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
