<?php

namespace Chip\Traits\Api;

use Chip\Model\Purchase as ModelPurchase;

trait Purchase
{
	/**
	 * 
	 * @param \Chip\Model\Purchase $purchase
	 * @return \Chip\Model\Purchase
	 */
	public function createPurchase(ModelPurchase $purchase): ModelPurchase
	{
		return $this->mapper->map($this->request('POST', 'purchases/', [
			'json' => $purchase
		]), new ModelPurchase());
	}
	
	/**
	 * 
	 * @param int $purchaseId
	 * @return \Chip\Model\Purchase
	 */
	public function getPurchase(int $purchaseId): ModelPurchase
	{
		return $this->mapper->map($this->request('GET', "purchases/$purchaseId/"), new ModelPurchase());
	}
	
	/**
	 * 
	 * @param int $purchaseId
	 * @return \Chip\Model\Purchase
	 */
	public function cancelPurchase(int $purchaseId): ModelPurchase
	{
		return $this->mapper->map($this->request('POST', "purchases/$purchaseId/cancel/"), new ModelPurchase());
	}
	
	/**
	 * 
	 * @param int $purchaseId
	 * @return \Chip\Model\Purchase
	 */
	public function releasePurchase(int $purchaseId): ModelPurchase
	{
		return $this->mapper->map($this->request('POST', "purchases/$purchaseId/release/"), new ModelPurchase());
	}
	
	/**
	 * 
	 * @param int $purchaseId
	 * @param int $amount
	 * @return \Chip\Model\Purchase
	 */
	public function capturePurchase(int $purchaseId, int $amount = null): ModelPurchase
	{
		$options = [];
		if ($amount !== null) {
			$options['json'] = [
				'amount' => $amount
			];
		}
		return $this->mapper->map($this->request('POST', "purchases/$purchaseId/capture/", $options), new ModelPurchase());
	}
	
	/**
	 * 
	 * @param int $purchaseId
	 * @param string $token
	 * @return \Chip\Model\Purchase
	 */
	public function chargePurchase(int $purchaseId, string $token): ModelPurchase
	{
		return $this->mapper->map($this->request('POST', "purchases/$purchaseId/charge/", [
			'json' => [
				'recurring_token' => $token
			]
		]), new ModelPurchase());
	}

	/**
	 * 
	 * @param int $purchaseId
	 * @return \Chip\Model\Purchase
	 */
	public function deleteRecurringToken(int $purchaseId): ModelPurchase
	{
		return $this->mapper->map($this->request('POST', "purchases/$purchaseId/delete_recurring_token/"), new ModelPurchase());
	}
	
	/**
	 * 
	 * @param int $purchaseId
	 * @param int $amount
	 * @return \Chip\Model\Purchase
	 */
	public function refundPurchase(int $purchaseId, int $amount = null): ModelPurchase
	{
		$options = [];
		if ($amount !== null) {
			$options['json'] = [
				'amount' => $amount
			];
		}
		return $this->mapper->map($this->request('POST', "purchases/$purchaseId/refund/", $options), new ModelPurchase());
	}

	/**
	 * 
	 * @param int $purchaseId
	 * @param int $utcTimestamp
	 * @return \Chip\Model\Purchase
	 */
	public function markAsPaid(int $purchaseId, int $utcTimestamp = null): ModelPurchase
	{
		$options = [];
		if ($utcTimestamp !== null) {
			$options['json'] = [
				'paid_on' => $utcTimestamp
			];
		}
		return $this->mapper->map($this->request('POST', "purchases/$purchaseId/mark_as_paid/", $options), new ModelPurchase());
	}
}