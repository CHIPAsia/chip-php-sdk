<?php

declare(strict_types=1);

namespace Chip\Http;

interface ClientInterface
{
    /**
     * @param array<string, mixed> $options
     * @return \stdClass|array<string, mixed>
     * @throws \Chip\Exception\ChipApiException
     */
    public function request(string $method, string $endpoint, array $options = []): \stdClass|array;
}
