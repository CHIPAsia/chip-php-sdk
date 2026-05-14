<?php

declare(strict_types=1);

namespace Chip;

use Chip\Http\ClientInterface;
use Chip\Http\GuzzleClient;
use Chip\Http\RetryClient;
use Chip\Resource\AccountResource;
use Chip\Resource\BillingResource;
use Chip\Resource\ClientsResource;
use Chip\Resource\PaymentMethodsResource;
use Chip\Resource\PublicKeyResource;
use Chip\Resource\PurchasesResource;
use Chip\Resource\StatementsResource;
use Chip\Resource\WebhooksResource;
use Psr\Log\LoggerInterface;

class ChipApi
{
    public readonly PurchasesResource $purchases;

    public readonly ClientsResource $clients;

    public readonly WebhooksResource $webhooks;

    public readonly PaymentMethodsResource $paymentMethods;

    public readonly PublicKeyResource $publicKey;

    public readonly AccountResource $account;

    public readonly StatementsResource $statements;

    public readonly BillingResource $billing;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        string $brandId,
        string $apiKey,
        string $base = 'https://gate.chip-in.asia/api/v1/',
        array $config = [],
        ?LoggerInterface $logger = null,
    ) {
        $httpClient = $this->createHttpClient($apiKey, $base, $config, $logger);

        $this->purchases = new PurchasesResource($httpClient);
        $this->clients = new ClientsResource($httpClient);
        $this->webhooks = new WebhooksResource($httpClient);
        $this->paymentMethods = new PaymentMethodsResource($httpClient, $brandId);
        $this->publicKey = new PublicKeyResource($httpClient);
        $this->account = new AccountResource($httpClient);
        $this->statements = new StatementsResource($httpClient);
        $this->billing = new BillingResource($httpClient);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function createHttpClient(string $apiKey, string $base, array $config, ?LoggerInterface $logger): ClientInterface
    {
        $guzzleClient = new GuzzleClient($apiKey, $base, $config, $logger);

        return new RetryClient($guzzleClient, 3, 1.0, $logger);
    }

    /**
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
