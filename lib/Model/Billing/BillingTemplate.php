<?php

namespace Chip\Model\Billing;

class BillingTemplate implements \JsonSerializable
{
    /** @var string|null */
    public $type;

    /** @var string|null */
    public $id;

    /** @var int|null */
    public $created_on;

    /** @var int|null */
    public $updated_on;

    /** @var mixed|null */
    public $clients;

    /** @var mixed|null */
    public $purchase;

    /** @var string|null */
    public $company_id;

    /** @var int|null */
    public $number_of_billing_cycles;

    /** @var bool|null */
    public $is_test;

    /** @var string|null */
    public $user_id;

    /** @var string|null */
    public $brand_id;

    /** @var string|null */
    public $title;

    /** @var bool|null */
    public $is_subscription;

    /** @var int|null */
    public $invoice_issued;

    /** @var int|null */
    public $invoice_due;

    /** @var bool|null */
    public $invoice_skip_capture;

    /** @var bool|null */
    public $invoice_send_receipt;

    /** @var int|null */
    public $subscription_period;

    /** @var string|null */
    public $subscription_period_units;

    /** @var int|null */
    public $subscription_due_period;

    /** @var string|null */
    public $subscription_due_period_units;

    /** @var int|null */
    public $subscription_charge_period_end;

    /** @var int|null */
    public $subscription_trial_periods;

    /** @var bool|null */
    public $subscription_active;

    /** @var bool|null */
    public $subscription_has_active_clients;

    /** @var bool|null */
    public $force_recurring;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this, [$this, 'allow_non_null']);
    }

    /**
     * @param mixed $var
     * @return bool
     */
    private function allow_non_null($var)
    {
        if (is_null($var)) {
            return false;
        }

        return true;
    }
}
