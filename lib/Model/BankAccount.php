<?php
namespace Chip\Model;

class BankAccount implements \JsonSerializable {
	/**
	 *
	 * @var string
	 */
	public $bank_account;
	
	/**
	 *
	 * @var string
	 */
	public $bank_code;
	
  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}
}
