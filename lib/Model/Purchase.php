<?php

namespace Chip\Model;

class Purchase implements \JsonSerializable
{
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
     * @var PaymentDetails
     */
    public $payment;

    /**
     *
     * @var IssuerDetails
     */
    public $issuer_details;

    /**
     *
     * @var object
     */
    public $transaction_data;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var array
     * @phpstan-var array<int, \stdClass>
     */
    public $status_history;

    /**
     *
     * @var int
     */
    public $viewed_on;

    /**
     *
     * @var string
     */
    public $company_id;

    /**
     *
     * @var bool
     */
    public $is_test;

    /**
     *
     * @var string
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $brand_id;

    /**
     *
     * @var string
     */
    public $billing_template_id;

    /**
     *
     * @var string
     */
    public $client_id;

    /**
     *
     * @var bool
     */
    public $send_receipt;

    /**
     *
     * @var bool
     */
    public $is_recurring_token;

    /**
     *
     * @var string
     */
    public $recurring_token;

    /**
     *
     * @var bool
     */
    public $force_recurring;

    /**
     *
     * @var bool
     */
    public $skip_capture;

    /**
     *
     * @var string
     */
    public $reference_generated;

    /**
     *
     * @var string
     */
    public $reference;

    /**
     *
     * @var string|null
     */
    public $issued;

    /**
     *
     * @var int
     */
    public $due;

    /**
     *
     * @var string
     */
    public $refund_availability;

    /**
     *
     * @var int
     */
    public $refundable_amount;

    /**
     *
     * @var object
     */
    public $currency_conversion;

    /**
     *
     * @var string[]
     */
    public $payment_method_whitelist;

    /**
     *
     * @var string
     */
    public $success_redirect;

    /**
     *
     * @var string
     */
    public $failure_redirect;

    /**
     *
     * @var string
     */
    public $cancel_redirect;

    /**
     *
     * @var string
     */
    public $success_callback;

    /**
     *
     * @var string
     */
    public $creator_agent;

    /**
     *
     * @var string
     */
    public $platform;

    /**
     *
     * @var string
     */
    public $product;

    /**
     *
     * @var string
     */
    public $created_from_ip;

    /**
     *
     * @var string
     */
    public $invoice_url;

    /**
     *
     * @var string
     */
    public $checkout_url;

    /**
     *
     * @var string
     */
    public $direct_post_url;

    /**
     *
     * @var string|null
     */
    public $notes;

    /**
     *
     * @var bool
     */
    public $marked_as_paid;

    /**
     *
     * @var string|null
     */
    public $order_id;

    /**
     *
     * @var array
     * @phpstan-var array<int, mixed>
     */
    public $upsell_campaigns;

    /**
     *
     * @var string|null
     */
    public $referral_campaign_id;

    /**
     *
     * @var string|null
     */
    public $referral_code;

    /**
     *
     * @var object|null
     */
    public $referral_code_details;

    /**
     *
     * @var string|null
     */
    public $referral_code_generated;

    /**
     *
     * @var object|null
     */
    public $retain_level_details;

    /**
     *
     * @var bool
     */
    public $can_retrieve;

    /**
     *
     * @var bool
     */
    public $can_chargeback;

    /**
     *
     * @var bool
     */
    public $can_reverse_chargeback;

    /**
     *
     * @var string[]
     */
    public $tags;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
