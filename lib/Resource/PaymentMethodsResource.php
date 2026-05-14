<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;
use Chip\Model\PaymentMethods;

final class PaymentMethodsResource
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly string $brandId,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function list(string $currency = 'MYR', array $options = []): PaymentMethods
    {
        $response = $this->client->request('GET', 'payment_methods/', [
            'query' => array_merge([
                'brand_id' => $this->brandId,
                'currency' => $currency,
            ], $options),
        ]);

        return PaymentMethods::fromArray((array) $response);
    }
}
