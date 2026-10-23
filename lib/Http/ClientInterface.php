<?php

declare(strict_types=1);

namespace Chip\Http;

interface ClientInterface
{
    /**
     * `GET /public_key/` answers with a JSON-encoded PEM string rather than an
     * object, so a decoded string body is returned as-is.
     *
     * @param array<string, mixed> $options
     * @return \stdClass|array<string, mixed>|string
     * @throws \Chip\Exception\ChipApiException
     */
    public function request(string $method, string $endpoint, array $options = []): \stdClass|array|string;
}
