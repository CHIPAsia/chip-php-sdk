<?php
namespace Chip\Model;

class PaymentDetails implements \JsonSerializable {
	/**
	 *
	 * @var bool
	 */
	public $is_outgoing;
	
	/**
	 *
	 * @var string
	 */
	public $payment_type;
	
	/**
	 *
	 * @var int
	 */
	public $amount;
	
	/**
	 *
	 * @var string
	 */
	public $currency;
	
	/**
	 *
	 * @var int
	 */
	public $net_amount;
	
	/**
	 *
	 * @var int
	 */
	public $fee_amount;
	
	/**
	 *
	 * @var int
	 */
	public $pending_amount;
	
	/**
	 *
	 * @var int
	 */
	public $pending_unfreeze_on;
	
	/**
	 *
	 * @var string
	 */
	public $description;
	
	/**
	 *
	 * @var int
	 */
	public $paid_on;
	
	/**
	 *
	 * @var int
	 */
	public $remote_paid_on;
	
  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}
}
