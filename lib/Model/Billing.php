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
	 * @var ClientDetails
	 */
	public $client;

	/**
	 *
	 * @var PurchaseDetails
	 */
	public $purchase;

	/**
	 *
	 * @var int
	 */
	public $number_of_billing_cycles;

	/**
	 *
	 * @var string
	 */
	public $brand_id;

	/**
	 *
	 * @var string
	 */
	public $title;

	/**
	 *
	 * @var boolean
	 */
	public $is_subscription;

	/**
	 *
	 * @var string
	 */
	public $invoice_issued;

	/**
	 *
	 * @var int
	 */
	public $invoice_due;

	/**
	 *
	 * @var boolean
	 */
	public $invoice_skip_capture;

	/**
	 *
	 * @var boolean
	 */
	public $invoice_send_receipt;

	/**
	 *
	 * @var int
	 */
	public $subscription_period;

	/**
	 *
	 * @var string
	 */
	public $subscription_period_units;

	/**
	 *
	 * @var int
	 */
	public $subscription_due_period;

	/**
	 *
	 * @var string
	 */
	public $subscription_due_period_units;

	/**
	 *
	 * @var boolean
	 */
	public $subscription_charge_period_end;

	/**
	 *
	 * @var int
	 */
	public $subscription_trial_periods;

	/**
	 *
	 * @var boolean
	 */
	public $subscription_active;

	/**
	 *
	 * @var boolean
	 */
	public $force_recurring;

	/**
	 *
	 * @var string[]
	 */
	public $upsell_campaigns;

	/**
	 *
	 * @var string
	 */
	public $referral_campaign_id;

  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}

}
