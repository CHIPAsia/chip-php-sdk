<?php

namespace Chip\Model\Billing;

class BillingTemplateClientAddSubscriber implements \JsonSerializable
{
    /** @var BillingTemplateClient|null */
    public $billing_template_client;

    /** @var \Chip\Model\Purchase|null */
    public $purchase;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
