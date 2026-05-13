<?php

namespace Chip;

use Chip\Exception\AuthenticationException;
use Chip\Exception\ClientException;
use Chip\Exception\NotFoundException;
use Chip\Exception\ServerException;
use Chip\Exception\ValidationException;
use Chip\Traits\Api\Account;
use Chip\Traits\Api\Billing;
use Chip\Traits\Api\Client;
use Chip\Traits\Api\PaymentMethod;
use Chip\Traits\Api\PublicKey;
use Chip\Traits\Api\Purchase;
use Chip\Traits\Api\Statements;
use Chip\Traits\Api\Webhook;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ChipApi
{
    use Purchase;
    use PaymentMethod;
    use Client;
    use Webhook;
    use Billing;
    use PublicKey;
    use Account;
    use Statements;

    protected \GuzzleHttp\Client $client;

    protected \JsonMapper $mapper;

    protected LoggerInterface $logger;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        protected string $brandId,
        protected string  $apiKey,
        protected string  $base = 'https://gate.chip-in.asia/api/v1/',
        array $config = [],
        ?LoggerInterface $logger = null
    ) {
        $this->mapper = new \JsonMapper();
        $this->mapper->bStrictNullTypes = false;
        $this->mapper->bEnforceMapType = false;
        $this->logger = $logger ?? new NullLogger();

        $mergedConfig = array_merge([
            'base_uri' => $this->base,
            'timeout' => $config['timeout'] ?? 30,
        ], $config);

        $this->client = new \GuzzleHttp\Client($mergedConfig);
    }

    /**
     * @param array<string, mixed> $options
     * @return mixed
     */
    protected function request(string $method, string $endpoint, array $options = []): mixed
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

        return json_decode($body);
    }

    /**
     *
     * @param string $content
     * @param string $signature
     * @param string $publicKey
     * @return bool
     */
    public static function verify(string $content, string $signature, string $publicKey): bool
    {
        return 1 === openssl_verify(
            $content,
            base64_decode($signature),
            $publicKey,
            'sha256WithRSAEncryption'
        );
    }
}
