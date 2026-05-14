<?php

declare(strict_types=1);

namespace Chip\Http;

use Chip\Exception\AuthenticationException;
use Chip\Exception\ClientException;
use Chip\Exception\NotFoundException;
use Chip\Exception\ServerException;
use Chip\Exception\ValidationException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
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
     * @return \stdClass|array<string, mixed>
     */
    public function request(string $method, string $endpoint, array $options = []): \stdClass|array
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
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode((string) $response->getBody(), true) ?? [];
            $message = $body['detail'] ?? $body['message'] ?? $e->getMessage();

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
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode((string) $response->getBody(), true) ?? [];
            $message = $body['detail'] ?? $body['message'] ?? $e->getMessage();

            $this->logger->error('CHIP API server error', [
                'status' => $statusCode,
                'message' => $message,
            ]);

            throw new ServerException($message, $statusCode, $body, $e);
        }

        $body = (string) $response->getBody()->getContents();

        return json_decode($body, true) ?? new \stdClass();
    }
}
