<?php

namespace Chip\Traits\Api;

use Chip\Model\PaymentMethods as ModelPaymentMethods;

trait PaymentMethod
{
	/**
	 *
	 * @param string $currency
	 * @param array $options Optional query parameters: country, recurring, skip_capture, preauthorization, language, amount
	 * @return \Chip\Model\PaymentMethods
	 */
	public function getPaymentMethods(string $currency = 'MYR', array $options = []): ModelPaymentMethods
	{
		return $this->mapper->map($this->request('GET', 'payment_methods/', [
			'query' => array_merge([
				'brand_id' => $this->brandId,
				'currency' => $currency
			], $options)
		]), new ModelPaymentMethods());
	}
}