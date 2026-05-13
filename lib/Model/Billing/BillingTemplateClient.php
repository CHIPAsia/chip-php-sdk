<?php

namespace Chip\Model\Billing;

class BillingTemplateClient implements \JsonSerializable
{
  public $type;
  public $id;
  public $created_on;
  public $updated_on;

  public $client_id; //required
  public $number_of_billing_cycles_passed;
  public $status;
  public $subscription_billing_scheduled_on;
  public $payment_method_whitelist;
  public $send_invoice_on_charge_failure;
  public $send_invoice_on_add_subscriber;
  public $send_receipt;

  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return array_filter((array) $this);
  }
}
