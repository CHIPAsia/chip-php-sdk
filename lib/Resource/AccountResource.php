<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;

final class AccountResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    public function balance(array $filters = []): array
    {
        $response = $this->client->request('GET', 'account/json/balance/', [
            'query' => $filters,
        ]);

        $json = json_encode($response);
        $decoded = json_decode($json !== false ? $json : '[]', true);

        // These endpoints answer with an object; a scalar or plain-text body must
        // not turn into a TypeError from the declared array return type.
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    public function turnover(array $filters = []): array
    {
        $response = $this->client->request('GET', 'account/json/turnover/', [
            'query' => $filters,
        ]);

        $json = json_encode($response);
        $decoded = json_decode($json !== false ? $json : '[]', true);

        // These endpoints answer with an object; a scalar or plain-text body must
        // not turn into a TypeError from the declared array return type.
        return is_array($decoded) ? $decoded : [];
    }
}
