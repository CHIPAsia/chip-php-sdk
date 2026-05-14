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
     * @var array<string, mixed>|null
     */
    public $logos;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
