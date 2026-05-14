<?php

namespace Chip\Model;

class PurchaseDetails implements \JsonSerializable
{
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

    /**
     *
     * @var array
     * @phpstan-var array<int, mixed>
     */
    public $shipping_options;

    /**
     *
     * @var object|null
     */
    public $payment_method_details;

    /**
     *
     * @var bool
     */
    public $has_upsell_products;

    /**
     *
     * @var bool
     */
    public $single_attempt;

    /**
     *
     * @var object|null
     */
    public $metadata;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $details = new self();
        $details->currency = $data['currency'] ?? '';
        $details->products = array_map(
            fn (array $p) => Product::fromArray($p),
            $data['products'] ?? []
        );
        $details->total = $data['total'] ?? 0;
        $details->language = $data['language'] ?? '';
        $details->notes = $data['notes'] ?? '';
        $details->debt = $data['debt'] ?? 0;
        $details->subtotal_override = $data['subtotal_override'] ?? 0;
        $details->total_tax_override = $data['total_tax_override'] ?? 0;
        $details->total_discount_override = $data['total_discount_override'] ?? 0;
        $details->total_override = $data['total_override'] ?? 0;
        $details->request_client_details = $data['request_client_details'] ?? [];
        $details->timezone = $data['timezone'] ?? '';
        $details->due_strict = $data['due_strict'] ?? false;
        $details->email_message = $data['email_message'] ?? '';
        $details->shipping_options = $data['shipping_options'] ?? [];
        $details->payment_method_details = isset($data['payment_method_details']) ? (object) $data['payment_method_details'] : null;
        $details->has_upsell_products = $data['has_upsell_products'] ?? false;
        $details->single_attempt = $data['single_attempt'] ?? false;
        $details->metadata = isset($data['metadata']) ? (object) $data['metadata'] : null;

        return $details;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
