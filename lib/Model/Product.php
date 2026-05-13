<?php

namespace Chip\Model;

class Product implements \JsonSerializable
{
    /**
     * @var string|null
     */
    public $name;

    /**
     * @var float|null
     */
    public $quantity;

    /**
     * @var int|null
     */
    public $price;

    /**
     * @var int|null
     */
    public $discount;

    /**
     * @var float|null
     */
    public $tax_percent;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this, [$this, 'allow_non_null']);
    }

    /**
     * @param mixed $var
     * @return bool
     */
    private function allow_non_null($var)
    {
        if (is_null($var)) {
            return false;
        }

        return true;
    }
}
