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

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
