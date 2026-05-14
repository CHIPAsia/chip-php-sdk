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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $client = new self();
        $client->type = $data['type'] ?? null;
        $client->id = $data['id'] ?? null;
        $client->created_on = $data['created_on'] ?? null;
        $client->updated_on = $data['updated_on'] ?? null;
        $client->client_id = $data['client_id'] ?? null;
        $client->number_of_billing_cycles_passed = $data['number_of_billing_cycles_passed'] ?? null;
        $client->status = $data['status'] ?? null;
        $client->subscription_billing_scheduled_on = $data['subscription_billing_scheduled_on'] ?? null;
        $client->payment_method_whitelist = $data['payment_method_whitelist'] ?? null;
        $client->send_invoice_on_charge_failure = $data['send_invoice_on_charge_failure'] ?? null;
        $client->send_invoice_on_add_subscriber = $data['send_invoice_on_add_subscriber'] ?? null;
        $client->send_receipt = $data['send_receipt'] ?? null;

        return $client;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
