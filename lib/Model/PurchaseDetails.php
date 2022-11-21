<?php
namespace Chip\Model;

class PurchaseDetails implements \JsonSerializable {
	/**
	 *
	 * @var string
	 */
	public $currency;
	
	/**
	 *
	 * @var Product[]
	 */
	public $products;
	
	/**
	 *
	 * @var int
	 */
	public $total;
	
	/**
	 *
	 * @var string
	 */
	public $language;
	
	/**
	 *
	 * @var string
	 */
	public $notes;
	
	/**
	 *
	 * @var int
	 */
	public $debt;
	
	/**
	 *
	 * @var int
	 */
	public $subtotal_override;
	
	/**
	 *
	 * @var int
	 */
	public $total_tax_override;
	
	
	/**
	 *
	 * @var int
	 */
	public $total_discount_override;
	
	/**
	 *
	 * @var int
	 */
	public $total_override;
	
	/**
	 *
	 * @var string[]
	 */
	public $request_client_details;
	
	/**
	 *
	 * @var string
	 */
	public $timezone;
	
	/**
	 *
	 * @var bool
	 */
	public $due_strict;
	
	/**
	 *
	 * @var string
	 */
	public $email_message;

  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}
}

