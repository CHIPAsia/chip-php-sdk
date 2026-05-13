<?php

namespace Chip\Traits\Api;

use Chip\Model\PaymentMethods as ModelPaymentMethods;

trait PaymentMethod
{
    /**
     * @param array<string, mixed> $options Optional query parameters: country, recurring, skip_capture, preauthorization, language, amount
     */
    public function getPaymentMethods(string $currency = 'MYR', array $options = []): ModelPaymentMethods
    {
        return $this->mapper->map($this->request('GET', 'payment_methods/', [
            'query' => array_merge([
                'brand_id' => $this->brandId,
                'currency' => $currency,
            ], $options),
        ]), new ModelPaymentMethods());
    }
}
