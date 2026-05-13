<?php

namespace Chip\Model\Billing;

class BillingTemplateClient implements \JsonSerializable
{
    /** @var string|null */
    public $type;

    /** @var string|null */
    public $id;

    /** @var int|null */
    public $created_on;

    /** @var int|null */
    public $updated_on;

    /** @var string|null */
    public $client_id;

    /** @var int|null */
    public $number_of_billing_cycles_passed;

    /** @var string|null */
    public $status;

    /** @var int|null */
    public $subscription_billing_scheduled_on;

    /** @var string[]|null */
    public $payment_method_whitelist;

    /** @var bool|null */
    public $send_invoice_on_charge_failure;

    /** @var bool|null */
    public $send_invoice_on_add_subscriber;

    /** @var bool|null */
    public $send_receipt;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
