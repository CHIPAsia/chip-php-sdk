<?php

namespace Chip\Model;

class PaymentMethods implements \JsonSerializable
{
    /**
     *
     * @var string[]
     */
    public $available_payment_methods;

    /**
     *
     * @var array
     * @phpstan-var array<string, string[]>
     */
    public $by_country;

    /**
     *
     * @var array
     * @phpstan-var array<string, string>
     */
    public $country_names;

    /**
     *
     * @var array
     * @phpstan-var array<string, string>
     */
    public $names;

    /**
     *
     * @var string[]
     */
    public $card_methods;

    /**
     *
     * @var array
     * @phpstan-var array<string, string|string[]>
     */
    public $logos;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $methods = new self();
        $methods->available_payment_methods = $data['available_payment_methods'] ?? [];
        $methods->by_country = $data['by_country'] ?? [];
        $methods->country_names = $data['country_names'] ?? [];
        $methods->names = $data['names'] ?? [];
        $methods->card_methods = $data['card_methods'] ?? [];
        $methods->logos = $data['logos'] ?? [];

        return $methods;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
