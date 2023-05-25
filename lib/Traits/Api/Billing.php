<?php

namespace Chip\Traits\Api;

use Chip\Model\Billing as ModelBilling;
use Chip\Model\BillingTemplate as ModelBillingTemplate;
use Chip\Model\BillingTemplates as ModelBillingTemplates;
use Chip\Model\BillingTemplateClient as ModelBillingTemplateClient;
use Chip\Model\BillingTemplateClients as ModelBillingTemplateClients;
use Chip\Model\Purchase as ModelPurchase;


trait Billing
{
	/**
	 * 
	 * @param \Chip\Model\Billing $billing
	 * @return \Chip\Model\Billing
	 */
	public function createBilling(ModelBilling $billing): ModelBilling
	{
		return $this->mapper->map($this->request('POST', 'billing/', [
			'json' => $billing
		]), new ModelBilling());
	}

	/**
	 * 
	 * @param \Chip\Model\BillingTemplate $billingTemplate
	 * @return \Chip\Model\BillingTemplate
	 */
	public function createBillingTemplate(ModelBillingTemplate $billingTemplate): ModelBillingTemplate
	{
		return $this->mapper->map($this->request('POST', 'billing_templates/', [
			'json' => $billingTemplate
		]), new ModelBillingTemplate());
	}
	
	/**
	 * 
	 * @param string $billingTemplateId
	 * @return \Chip\Model\BillingTemplate
	 */
	public function getAllBillingTemplate(): ModelBillingTemplates
	{
		return $this->mapper->map($this->request('GET', "billing_templates/"), new ModelBillingTemplates());
	}
	
	/**
	 * 
	 * @param string $billingTemplateId
	 * @return \Chip\Model\BillingTemplate
	 */
	public function getBillingTemplate(string $billingTemplateId): ModelBillingTemplate
	{
		return $this->mapper->map($this->request('GET', "billing_templates/$billingTemplateId/"), new ModelBillingTemplate());
	}
	
	/**
	 * 
	 * @param \Chip\Model\BillingTemplate $billingTemplate
	 * @param string $billingTemplateId
	 * @return \Chip\Model\BillingTemplate
	 */
	public function updateBillingTemplate(string $billingTemplateId, ModelBillingTemplate $billingTemplate): ModelBillingTemplate
	{
		return $this->mapper->map($this->request('PUT', "billing_templates/$billingTemplateId/"), new ModelBillingTemplate());
	}
	
	/**
	 * 
	 * @param string $billingTemplateId
	 * @return \Chip\Model\BillingTemplate
	 */
	public function deleteBillingTemplate(string $billingTemplateId): ModelBillingTemplate
	{
		return $this->mapper->map($this->request('DELETE', "billing_templates/$billingTemplateId/"), new ModelBillingTemplate());
	}

	/**
	 * 
	 * @param \Chip\Model\BillingTemplateClient $billingTemplateClient
	 * @return \Chip\Model\BillingTemplateClient
	 */
	public function sendBillingTemplateInvoice(string $billingTemplateId, ModelBillingTemplateClient $billingTemplateClient): ModelPurchase
	{
		return $this->mapper->map($this->request('POST', "billing_templates/$billingTemplateId/send_invoice/", [
			'json' => $billingTemplateClient
		]), new ModelBillingTemplateClient());
	}

	/**
	 * 
	 * @param \Chip\Model\BillingTemplateClient $billingTemplateClient
	 * @return \Chip\Model\BillingTemplateClient
	 */
	public function addBillingTemplateSubscriber(string $billingTemplateId, ModelBillingTemplateClient $billingTemplateClient): ModelBillingTemplateClient
	{
		return $this->mapper->map($this->request('POST', "billing_templates/$billingTemplateId/add_subscriber/", [
			'json' => $billingTemplateClient
		]), new ModelBillingTemplateClient());
	}

	/**
	 * 
	 * @param \Chip\Model\BillingTemplateClient $billingTemplateClient
	 * @return \Chip\Model\BillingTemplateClient
	 */
	public function getAllBillingTemplateClient(string $billingTemplateId): ModelBillingTemplateClients
	{
		return $this->mapper->map($this->request('GET', "billing_templates/$billingTemplateId/clients/"), new ModelBillingTemplateClients());
	}

	/**
	 * 
	 * @param \Chip\Model\BillingTemplateClient $billingTemplateClient
	 * @return \Chip\Model\BillingTemplateClient
	 */
	public function getBillingTemplateClientDetails(string $billingTemplateId, string $clientId): ModelBillingTemplateClient
	{
		return $this->mapper->map($this->request('GET', "billing_templates/$billingTemplateId/clients/$clientId"), new ModelBillingTemplateClient());
	}

	/**
	 * 
	 * @param \Chip\Model\BillingTemplateClient $billingTemplateClient
	 * @return \Chip\Model\BillingTemplateClient
	 */
	public function updateBillingTemplateClient(string $billingTemplateId, string $clientId): ModelBillingTemplateClient
	{
		return $this->mapper->map($this->request('PATCH', "billing_templates/$billingTemplateId/clients/$clientId"), new ModelBillingTemplateClient());
	}

}