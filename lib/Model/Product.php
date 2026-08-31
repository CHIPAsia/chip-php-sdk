<?php

namespace Chip\Model;

use Chip\Exception\InvalidMoneyValueException;
use Chip\Support\Money;

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
        $product->price = isset($data['price']) ? Money::coerce($data['price']) : null;
        $product->discount = isset($data['discount']) ? Money::coerce($data['discount']) : null;
        $product->tax_percent = $data['tax_percent'] ?? null;
        $product->category = $data['category'] ?? null;
        $product->total_price_override = isset($data['total_price_override']) ? Money::coerce($data['total_price_override']) : null;

        return $product;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        // Coerce at serialization time too: public properties mean callers may
        // assign raw values (e.g. 0.29 * 100 float noise) directly without the
        // builder. Money fields must reach the API as integers.
        $data = (array) $this;

        foreach (['price', 'discount', 'total_price_override'] as $moneyField) {
            if ($data[$moneyField] !== null) {
                try {
                    $data[$moneyField] = Money::coerce($data[$moneyField]);
                } catch (InvalidMoneyValueException $e) {
                    throw new InvalidMoneyValueException("Product->{$moneyField}: " . $e->getMessage(), 0, $e);
                }
            }
        }

        return array_filter($data, [$this, 'allow_non_null']);
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
