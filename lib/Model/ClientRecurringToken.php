<?php

namespace Chip\Model;

class ClientRecurringToken implements \JsonSerializable
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
    public $payment_method;

    /** @var string|null */
    public $description;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $token = new self();
        $token->type = $data['type'] ?? null;
        $token->id = $data['id'] ?? null;
        $token->created_on = $data['created_on'] ?? null;
        $token->updated_on = $data['updated_on'] ?? null;
        $token->payment_method = $data['payment_method'] ?? null;
        $token->description = $data['description'] ?? null;

        return $token;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
