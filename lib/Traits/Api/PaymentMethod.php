<?php

namespace Chip\Traits\Api;

use Chip\Model\PaymentMethods as ModelPaymentMethods;

trait PaymentMethod
{
	/**
	 * 
	 * @param string $currency
	 * @return \Chip\Model\PaymentMethods
	 */
	public function getPaymentMethods(string $currency = 'MYR'): ModelPaymentMethods
	{
		return $this->mapper->map($this->request('GET', 'payment_methods/', [
			'query' => [
				'brand_id' => $this->brandId,
				'currency' => $currency
			]
		]), new ModelPaymentMethods());
	}
}