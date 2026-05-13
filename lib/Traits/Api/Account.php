<?php

namespace Chip\Traits\Api;

trait Account
{
    /**
     * @param array<string, mixed> $filters Optional query filters: tokenized, from, brand, terminal_uid, currency, payment_method, product, flow, country
     * @return array<string, mixed>
     */
    public function getBalance(array $filters = []): array
    {
        $response = $this->request('GET', 'account/json/balance/', [
            'query' => $filters,
        ]);

        $json = json_encode($response);

        return json_decode($json !== false ? $json : '[]', true);
    }

    /**
     * @param array<string, mixed> $filters Optional query filters: tokenized, from, to, brand, terminal_uid, currency, payment_method, product, flow, country
     * @return array<string, mixed>
     */
    public function getTurnover(array $filters = []): array
    {
        $response = $this->request('GET', 'account/json/turnover/', [
            'query' => $filters,
        ]);

        $json = json_encode($response);

        return json_decode($json !== false ? $json : '[]', true);
    }
}
