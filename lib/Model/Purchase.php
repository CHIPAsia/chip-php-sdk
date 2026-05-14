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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $purchase = new self();
        $purchase->id = $data['id'] ?? '';

        if (isset($data['client']) && is_array($data['client'])) {
            $purchase->client = ClientDetails::fromArray($data['client']);
        }

        if (isset($data['purchase']) && is_array($data['purchase'])) {
            $purchase->purchase = PurchaseDetails::fromArray($data['purchase']);
        }

        if (isset($data['payment']) && is_array($data['payment'])) {
            $purchase->payment = PaymentDetails::fromArray($data['payment']);
        }

        if (isset($data['issuer_details']) && is_array($data['issuer_details'])) {
            $purchase->issuer_details = IssuerDetails::fromArray($data['issuer_details']);
        }

        $purchase->transaction_data = isset($data['transaction_data']) ? (object) $data['transaction_data'] : new \stdClass();
        $purchase->status = $data['status'] ?? '';
        $purchase->status_history = array_map(
            fn (array $h) => (object) $h,
            $data['status_history'] ?? []
        );
        $purchase->viewed_on = $data['viewed_on'] ?? 0;
        $purchase->company_id = $data['company_id'] ?? '';
        $purchase->is_test = $data['is_test'] ?? false;
        $purchase->user_id = $data['user_id'] ?? '';
        $purchase->brand_id = $data['brand_id'] ?? '';
        $purchase->billing_template_id = $data['billing_template_id'] ?? '';
        $purchase->client_id = $data['client_id'] ?? '';
        $purchase->send_receipt = $data['send_receipt'] ?? false;
        $purchase->is_recurring_token = $data['is_recurring_token'] ?? false;
        $purchase->recurring_token = $data['recurring_token'] ?? '';
        $purchase->force_recurring = $data['force_recurring'] ?? false;
        $purchase->skip_capture = $data['skip_capture'] ?? false;
        $purchase->reference_generated = $data['reference_generated'] ?? '';
        $purchase->reference = $data['reference'] ?? '';
        $purchase->issued = $data['issued'] ?? null;
        $purchase->due = $data['due'] ?? 0;
        $purchase->refund_availability = $data['refund_availability'] ?? '';
        $purchase->refundable_amount = $data['refundable_amount'] ?? 0;
        $purchase->currency_conversion = isset($data['currency_conversion']) ? (object) $data['currency_conversion'] : new \stdClass();
        $purchase->payment_method_whitelist = $data['payment_method_whitelist'] ?? [];
        $purchase->success_redirect = $data['success_redirect'] ?? '';
        $purchase->failure_redirect = $data['failure_redirect'] ?? '';
        $purchase->cancel_redirect = $data['cancel_redirect'] ?? '';
        $purchase->success_callback = $data['success_callback'] ?? '';
        $purchase->creator_agent = $data['creator_agent'] ?? '';
        $purchase->platform = $data['platform'] ?? '';
        $purchase->product = $data['product'] ?? '';
        $purchase->created_from_ip = $data['created_from_ip'] ?? '';
        $purchase->invoice_url = $data['invoice_url'] ?? '';
        $purchase->checkout_url = $data['checkout_url'] ?? '';
        $purchase->direct_post_url = $data['direct_post_url'] ?? '';
        $purchase->notes = $data['notes'] ?? null;
        $purchase->marked_as_paid = $data['marked_as_paid'] ?? false;
        $purchase->order_id = $data['order_id'] ?? null;
        $purchase->upsell_campaigns = $data['upsell_campaigns'] ?? [];
        $purchase->referral_campaign_id = $data['referral_campaign_id'] ?? null;
        $purchase->referral_code = $data['referral_code'] ?? null;
        $purchase->referral_code_details = isset($data['referral_code_details']) ? (object) $data['referral_code_details'] : null;
        $purchase->referral_code_generated = $data['referral_code_generated'] ?? null;
        $purchase->retain_level_details = isset($data['retain_level_details']) ? (object) $data['retain_level_details'] : null;
        $purchase->can_retrieve = $data['can_retrieve'] ?? false;
        $purchase->can_chargeback = $data['can_chargeback'] ?? false;
        $purchase->can_reverse_chargeback = $data['can_reverse_chargeback'] ?? false;
        $purchase->tags = $data['tags'] ?? [];

        return $purchase;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
