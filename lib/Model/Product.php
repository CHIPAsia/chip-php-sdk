<?php
namespace Chip\Model;

class Product implements \JsonSerializable {
	
	/**
	 * @var string
	 */
	public $name;
	
	/**
	 * @var float
	 */
	public $quantity;
	
	/**
	 * @var int
	 */
	public $price;
	
	/**
	 * @var int
	 */
	public $discount;
	
	/**
	 * @var float
	 */
	public $tax_percent;
	
  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this, array($this, 'allow_non_null'));
	}

	private function allow_non_null($var) {
		if (is_null($var)){
			return false;
		}
		return true;
	}
}