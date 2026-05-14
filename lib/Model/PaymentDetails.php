<?php

namespace Chip\Model;

class PaymentDetails implements \JsonSerializable
{
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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $details = new self();
        $details->is_outgoing = $data['is_outgoing'] ?? false;
        $details->payment_type = $data['payment_type'] ?? '';
        $details->amount = $data['amount'] ?? 0;
        $details->currency = $data['currency'] ?? '';
        $details->net_amount = $data['net_amount'] ?? 0;
        $details->fee_amount = $data['fee_amount'] ?? 0;
        $details->pending_amount = $data['pending_amount'] ?? 0;
        $details->pending_unfreeze_on = $data['pending_unfreeze_on'] ?? 0;
        $details->description = $data['description'] ?? '';
        $details->paid_on = $data['paid_on'] ?? 0;
        $details->remote_paid_on = $data['remote_paid_on'] ?? 0;

        return $details;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
