<?php
namespace Chip\Model;

class IssuerDetails implements \JsonSerializable {
	/**
	 *
	 * @var string
	 */
	public $website;
	
	/**
	 *
	 * @var string
	 */
	public $legal_street_address;
	
	/**
	 *
	 * @var string
	 */
	public $legal_country;
	
	/**
	 *
	 * @var string
	 */
	public $legal_city;
	
	/**
	 *
	 * @var string
	 */
	public $legal_zip_code;
	
	/**
	 *
	 * @var BankAccount[]
	 */
	public $bank_accounts;
	
	/**
	 *
	 * @var string
	 */
	public $legal_name;
	
	/**
	 *
	 * @var string
	 */
	public $brand_name;
	
	/**
	 *
	 * @var string
	 */
	public $registration_number;
	
	/**
	 *
	 * @var string
	 */
	public $tax_number;
	
  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}
}
