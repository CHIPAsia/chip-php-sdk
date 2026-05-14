<?php

declare(strict_types=1);

namespace Chip\Http;

use Chip\Exception\ChipApiException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class RetryClient implements ClientInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly ClientInterface $client,
        private readonly int $maxRetries = 3,
        private readonly float $baseDelay = 1.0,
        ?LoggerInterface $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param array<string, mixed> $options
     * @return \stdClass|array<string, mixed>
     */
    public function request(string $method, string $endpoint, array $options = []): \stdClass|array
    {
        $attempt = 0;

        while (true) {
            try {
                return $this->client->request($method, $endpoint, $options);
            } catch (ChipApiException $e) {
                $statusCode = $e->getCode();

                if (! $this->shouldRetry($statusCode, $attempt)) {
                    throw $e;
                }

                $delay = $this->calculateDelay($e, $attempt);
                $attempt++;

                $this->logger->warning('CHIP API retry', [
                    'attempt' => $attempt,
                    'delay' => $delay,
                    'status' => $statusCode,
                    'endpoint' => $endpoint,
                ]);

                usleep((int) ($delay * 1_000_000));
            }
        }
    }

    private function shouldRetry(int $statusCode, int $attempt): bool
    {
        if ($attempt >= $this->maxRetries) {
            return false;
        }

        return $statusCode >= 500 || $statusCode === 429;
    }

    private function calculateDelay(ChipApiException $e, int $attempt): float
    {
        if ($e->getCode() === 429) {
            $body = $e->getResponseBody() ?? [];
            $retryAfter = $body['retry_after'] ?? null;

            if (is_numeric($retryAfter)) {
                return (float) $retryAfter;
            }
        }

        return $this->baseDelay * (2 ** $attempt);
    }
}
