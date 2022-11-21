<?php
namespace Chip\Model;

class PaymentMethods implements \JsonSerializable {
	/**
	 *
	 * @var string[]
	 */
	public $available_payment_methods;
	
	/**
	 *
	 * @var string[][]
	 */
	public $by_country;
	
	/**
	 *
	 * @var string[]
	 */
	public $country_names;
	
	/**
	 *
	 * @var string[]
	 */
	public $names;
	
	/**
	 *
	 * @var string[]
	 */
	public $card_methods;
	
  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}
}

