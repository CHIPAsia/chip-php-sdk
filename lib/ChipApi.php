<?php

namespace Chip;

use Chip\Traits\Api\Purchase;
use Chip\Traits\Api\PaymentMethod;
use Chip\Traits\Api\Client;
use Chip\Traits\Api\Webhook;
use Chip\Traits\Api\Billing;
use Chip\Traits\Api\PublicKey;
use Chip\Traits\Api\Account;
use Chip\Traits\Api\Statements;

class ChipApi
{

	use Purchase, PaymentMethod, Client, Webhook, Billing, PublicKey, Account, Statements;

	protected $client;

	protected $mapper;

	public function __construct(
		protected string $brandId,
		protected string  $apiKey,
		protected string  $base = 'https://gate.chip-in.asia/api/v1/',
		array $config = []
	) {
		$this->mapper = new \JsonMapper();
		$this->mapper->bStrictNullTypes = false;
		$this->mapper->bEnforceMapType = false;

		$this->client = new \GuzzleHttp\Client(array_merge([
			'base_uri' => $this->base,
		], $config));
	}

	protected function request(string $method, string $endpoint, array $options = array())
	{
		$headers = [];
		if ($this->apiKey) {
			$headers['Authorization'] = 'Bearer ' . $this->apiKey;
		}
		$response = $this->client->request($method, $endpoint, array_merge(array(
			'headers' => $headers
		), $options));
		$body = (string)$response->getBody()->getContents();

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
