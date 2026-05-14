<?php

namespace Chip\Model\Billing;

use Chip\Model\Purchase;

class BillingTemplateClientAddSubscriber implements \JsonSerializable
{
    /** @var BillingTemplateClient|null */
    public $billing_template_client;

    /** @var Purchase|null */
    public $purchase;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $result = new self();
        $result->billing_template_client = isset($data['billing_template_client']) && is_array($data['billing_template_client'])
            ? BillingTemplateClient::fromArray($data['billing_template_client'])
            : null;
        $result->purchase = isset($data['purchase']) && is_array($data['purchase'])
            ? Purchase::fromArray($data['purchase'])
            : null;

        return $result;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
