<?php
namespace Chip\Model;

class Billing implements \JsonSerializable {

	/**
	 *
	 * @var string
	 */
	public $id;
	
	/**
	 *
	 * @var string
	 */
	public $client_id;

	/**
	 *
	 * @var string
	 */
	public $invoice_reference;

	/**
	 *
	 * @var string
	 */
	public $status;

	/**
	 *
	 * @var string[]
	 */
	public $payment_method_whitelist;

	/**
	 *
	 * @var boolean
	 */
	public $send_invoice_on_charge_failure;

	/**
	 *
	 * @var boolean
	 */
	public $send_invoice_on_add_subscriber;

	/**
	 *
	 * @var boolean
	 */
	public $send_receipt;

  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}

}
