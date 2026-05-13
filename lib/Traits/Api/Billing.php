<?php

namespace Chip\Traits\Api;

use Chip\Model\Billing\BillingTemplate;
use Chip\Model\Billing\BillingTemplateClient;
use Chip\Model\Billing\BillingTemplateClientAddSubscriber;
use Chip\Model\Billing\BillingTemplateClientList;
use Chip\Model\Billing\BillingTemplateList;
use Chip\Model\Purchase;

trait Billing
{
    /**
     * Send an invoice to one or several clients.
     * @return mixed
     */
    public function createBilling(BillingTemplate $billing)
    {
        return $this->request('POST', 'billing/', [
            'json' => $billing,
        ]);
    }

    /**
     * Create a template to issue repeated invoices from in the future, with or without a subscription.
     * @return BillingTemplate
     */
    public function createBillingTemplate(BillingTemplate $billing)
    {
        return $this->mapper->map($this->request('POST', 'billing_templates/', [
            'json' => $billing,
        ]), new BillingTemplate());
    }

    /**
     * List all billing templates.
     * @return BillingTemplateList
     */
    public function getBillingTemplates()
    {
        return $this->mapper->map($this->request('GET', 'billing_templates/'), new BillingTemplateList());
    }

    /**
     * Retrieve a billing template by ID.
     * @return BillingTemplate
     */
    public function getBillingTemplate(string $billing_id)
    {
        return $this->mapper->map($this->request('GET', "billing_templates/$billing_id/"), new BillingTemplate());
    }

    /**
     * Update a billing template by ID.
     * @return BillingTemplate
     */
    public function updateBillingTemplate(string $billing_id, BillingTemplate $billing)
    {
        return $this->mapper->map($this->request('PUT', "billing_templates/$billing_id/", [
            'json' => $billing,
        ]), new BillingTemplate());
    }

    /**
     * Delete a billing template by ID.
     * @return mixed
     */
    public function deleteBillingTemplate(string $billing_id)
    {
        return $this->request('DELETE', "billing_templates/$billing_id/");
    }

    /**
     * Send an invoice, generating a purchase from billing template data.
     * @return Purchase
     */
    public function sendBillingTemplateInvoice(string $billing_id, BillingTemplateClient $billingTemplateClient)
    {
        return $this->mapper->map($this->request('POST', "billing_templates/$billing_id/send_invoice/", [
            'json' => $billingTemplateClient,
        ]), new Purchase());
    }

    /**
     * Add a billing template client and activate recurring billing (is_subscription: true).
     * @return BillingTemplateClientAddSubscriber
     */
    public function addBillingTemplateSubscriber(string $billing_id, BillingTemplateClient $billingTemplateClient)
    {
        return $this->mapper->map($this->request('POST', "billing_templates/$billing_id/add_subscriber/", [
            'json' => $billingTemplateClient,
        ]), new BillingTemplateClientAddSubscriber());
    }

    /**
     * List all billing template clients for this billing template.
     * @return BillingTemplateClientList
     */
    public function getBillingTemplateClients(string $billing_id)
    {
        return $this->mapper->map($this->request('GET', "billing_templates/$billing_id/clients/"), new BillingTemplateClientList());
    }

    /**
     * Retrieve a billing template client by client's ID.
     * @return BillingTemplateClient
     */
    public function getBillingTemplateClient(string $billing_id, string $billing_client_id)
    {
        return $this->mapper->map($this->request('GET', "billing_templates/$billing_id/clients/$billing_client_id/"), new BillingTemplateClient());
    }

    /**
     * Partially update a billing template client by client's ID.
     * @return BillingTemplateClient
     */
    public function updateBillingTemplateClient(string $billing_id, string $billing_client_id, BillingTemplateClient $billingTemplateClient)
    {
        return $this->mapper->map($this->request('PATCH', "billing_templates/$billing_id/clients/$billing_client_id/", [
            'json' => $billingTemplateClient,
        ]), new BillingTemplateClient());
    }
}
