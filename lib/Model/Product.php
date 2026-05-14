<?php

namespace Chip\Model;

class Product implements \JsonSerializable
{
    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null
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
     * @var string|null
     */
    public $tax_percent;

    /**
     * @var string|null
     */
    public $category;

    /**
     * @var int|null
     */
    public $total_price_override;

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
