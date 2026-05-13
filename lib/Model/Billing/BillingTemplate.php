<?php

namespace Chip\Model\Billing;

class BillingTemplate implements \JsonSerializable
{
  public $type;
  public $id;
  public $created_on;
  public $updated_on;

  public $clients; //required
  public $purchase; //required
  public $company_id;
  public $number_of_billing_cycles;
  public $is_test;
  public $user_id;
  public $brand_id; //required
  public $title; //required
  public $is_subscription; //required

  //invoice_* required if `is_subscription` is false
  public $invoice_issued;
  public $invoice_due;
  public $invoice_skip_capture;
  public $invoice_send_receipt;

  //subscription_* required if `is_subscription` is true
  public $subscription_period;
  public $subscription_period_units;
  public $subscription_due_period;
  public $subscription_due_period_units;
  public $subscription_charge_period_end;
  public $subscription_trial_periods;
  public $subscription_active;
  public $subscription_has_active_clients;

  public $force_recurring;

  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return array_filter((array) $this, array($this, 'allow_non_null'));
  }

  private function allow_non_null($var)
  {
    if (is_null($var)) {
      return false;
    }
    return true;
  }
}
