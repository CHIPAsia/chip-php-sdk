<?php

namespace Chip\Traits\Api;

trait Account
{
    /**
     * @param array $filters Optional query filters
     * @return array
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
     * @param array $filters Optional query filters
     * @return array
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
