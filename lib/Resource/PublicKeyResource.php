<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;

final class PublicKeyResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    /**
     * Returns the PEM public key used to verify callback payload signatures.
     *
     * `GET /public_key/` answers with a JSON-encoded PEM string, so the decoded
     * body is the key itself. An object body (`{"public_key": "..."}`) is still
     * accepted for compatibility with older gateway responses.
     */
    public function get(): string
    {
        $response = $this->client->request('GET', 'public_key/');

        if (is_string($response)) {
            return trim($response);
        }

        $arr = (array) $response;

        return (string) ($arr['public_key'] ?? '');
    }
}
