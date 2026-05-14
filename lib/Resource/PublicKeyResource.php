<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;

final class PublicKeyResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    public function get(): string
    {
        $response = $this->client->request('GET', 'public_key/');
        $arr = (array) $response;

        return $arr['public_key'] ?? '';
    }
}
