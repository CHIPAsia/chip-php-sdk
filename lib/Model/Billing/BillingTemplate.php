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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $template = new self();
        $template->type = $data['type'] ?? null;
        $template->id = $data['id'] ?? null;
        $template->created_on = $data['created_on'] ?? null;
        $template->updated_on = $data['updated_on'] ?? null;
        $template->clients = $data['clients'] ?? null;
        $template->purchase = $data['purchase'] ?? null;
        $template->company_id = $data['company_id'] ?? null;
        $template->number_of_billing_cycles = $data['number_of_billing_cycles'] ?? null;
        $template->is_test = $data['is_test'] ?? null;
        $template->user_id = $data['user_id'] ?? null;
        $template->brand_id = $data['brand_id'] ?? null;
        $template->title = $data['title'] ?? null;
        $template->is_subscription = $data['is_subscription'] ?? null;
        $template->invoice_issued = $data['invoice_issued'] ?? null;
        $template->invoice_due = $data['invoice_due'] ?? null;
        $template->invoice_skip_capture = $data['invoice_skip_capture'] ?? null;
        $template->invoice_send_receipt = $data['invoice_send_receipt'] ?? null;
        $template->subscription_period = $data['subscription_period'] ?? null;
        $template->subscription_period_units = $data['subscription_period_units'] ?? null;
        $template->subscription_due_period = $data['subscription_due_period'] ?? null;
        $template->subscription_due_period_units = $data['subscription_due_period_units'] ?? null;
        $template->subscription_charge_period_end = $data['subscription_charge_period_end'] ?? null;
        $template->subscription_trial_periods = $data['subscription_trial_periods'] ?? null;
        $template->subscription_active = $data['subscription_active'] ?? null;
        $template->subscription_has_active_clients = $data['subscription_has_active_clients'] ?? null;
        $template->force_recurring = $data['force_recurring'] ?? null;

        return $template;
    }

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
