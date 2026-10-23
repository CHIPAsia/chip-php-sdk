<?php

declare(strict_types=1);

namespace Chip\Http;

use Chip\Exception\AuthenticationException;
use Chip\Exception\ClientException;
use Chip\Exception\NotFoundException;
use Chip\Exception\ServerException;
use Chip\Exception\ValidationException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class GuzzleClient implements ClientInterface
{
    private \GuzzleHttp\Client $client;
    private LoggerInterface $logger;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        private readonly string $apiKey,
        string $baseUri = 'https://gate.chip-in.asia/api/v1/',
        array $config = [],
        ?LoggerInterface $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger();

        $mergedConfig = array_merge([
            'base_uri' => $baseUri,
            'timeout' => $config['timeout'] ?? 30,
        ], $config);

        $this->client = new \GuzzleHttp\Client($mergedConfig);
    }

    /**
     * @param array<string, mixed> $options
     * @return \stdClass|array<string, mixed>|string
     */
    public function request(string $method, string $endpoint, array $options = []): \stdClass|array|string
    {
        $headers = [];
        if ($this->apiKey) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        $mergedOptions = array_merge([
            'headers' => $headers,
        ], $options);

        $this->logger->debug('CHIP API request', [
            'method' => $method,
            'endpoint' => $endpoint,
        ]);

        try {
            $response = $this->client->request($method, $endpoint, $mergedOptions);
        } catch (GuzzleClientException $e) {
            [$statusCode, $body, $message] = $this->describeError($e);

            $this->logger->error('CHIP API client error', [
                'status' => $statusCode,
                'message' => $message,
            ]);

            throw match ($statusCode) {
                401 => new AuthenticationException($message, $statusCode, $body, $e),
                404 => new NotFoundException($message, $statusCode, $body, $e),
                422 => new ValidationException($message, $statusCode, $body, $e),
                default => new ClientException($message, $statusCode, $body, $e),
            };
        } catch (GuzzleServerException $e) {
            [$statusCode, $body, $message] = $this->describeError($e);

            $this->logger->error('CHIP API server error', [
                'status' => $statusCode,
                'message' => $message,
            ]);

            throw new ServerException($message, $statusCode, $body, $e);
        }

        $body = (string) $response->getBody()->getContents();
        $decoded = json_decode($body, true);

        if (is_array($decoded) || is_string($decoded)) {
            return $decoded;
        }

        // Empty or non-array JSON body (e.g. `204 No Content`), or plain text
        // such as a bare PEM: return the raw body instead of discarding it so
        // plain-text endpoints stay usable, and keep `{}` for empty bodies.
        $raw = trim($body);

        return '' === $raw ? new \stdClass() : $raw;
    }

    /**
     * Normalise a failed response into `[status, body, message]`.
     *
     * A failure is not guaranteed to carry a JSON object: a proxy in front of the
     * gateway can answer with a JSON scalar or plain text, and `detail`/`message`
     * can themselves be a list or an object. Coercing here keeps the failure a
     * `ChipApiException` instead of letting a typed constructor argument turn it
     * into a `TypeError` the caller cannot catch as an API error.
     *
     * @return array{0: int, 1: array<string, mixed>, 2: string}
     */
    private function describeError(RequestException $e): array
    {
        $response = $e->getResponse();

        if (null === $response) {
            return [0, [], $e->getMessage()];
        }

        $statusCode = $response->getStatusCode();
        $decoded = json_decode((string) $response->getBody(), true);
        $body = is_array($decoded) ? $decoded : [];

        $detail = $body['detail'] ?? $body['message'] ?? null;
        $message = $e->getMessage();

        if (is_string($detail) && '' !== $detail) {
            $message = $detail;
        } elseif (is_array($detail) && [] !== $detail) {
            $encoded = json_encode($detail, \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);
            if (is_string($encoded)) {
                $message = $encoded;
            }
        }

        return [$statusCode, $body, $message];
    }
}
