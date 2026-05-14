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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $product = new self();
        $product->name = $data['name'] ?? null;
        $product->quantity = $data['quantity'] ?? null;
        $product->price = $data['price'] ?? null;
        $product->discount = $data['discount'] ?? null;
        $product->tax_percent = $data['tax_percent'] ?? null;
        $product->category = $data['category'] ?? null;
        $product->total_price_override = $data['total_price_override'] ?? null;

        return $product;
    }

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
