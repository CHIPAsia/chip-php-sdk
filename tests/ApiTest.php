<?php declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use Chip\Model\Purchase as ModelPurchase;

class ApiTest extends TestCase
{
	public $purchase_id = "14483a7f-2bde-4e9d-a3d2-ffa6e09e72d7";

	public function testRefundWithoutAmount() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->refundPurchase($this->purchase_id);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/refund", $transaction['request']->getUri()->getPath());
		$this->assertEmpty($transaction['request']->getBody()->getContents());
	}
	
	public function testRefundWithAmount() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->refundPurchase($this->purchase_id, 100);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/refund", $transaction['request']->getUri()->getPath());
		$body = json_decode($transaction['request']->getBody()->getContents(), true);
		$this->assertEquals(100, $body['amount']);
	}
	
	public function testPaymentMethods() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->getPaymentMethods('USD');
		$transaction = $container[0];
		
		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('payment_methods/', $transaction['request']->getUri()->getPath());
		$body = json_decode($transaction['request']->getBody()->getContents(), true);
		$this->assertStringContainsString('currency=USD', $transaction['request']->getUri()->getQuery());
	}
	
	public function testCreatePurchase() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$model = new ModelPurchase();
		$api->createPurchase($model);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString('purchases/', $transaction['request']->getUri()->getPath());
	}
	
	public function testGetPurchase() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->getPurchase($this->purchase_id);
		$transaction = $container[0];
		
		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/", $transaction['request']->getUri()->getPath());
	}
	
	public function testCancelPurchase() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->cancelPurchase($this->purchase_id);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/cancel", $transaction['request']->getUri()->getPath());
	}
	
	public function testRelasePurchase() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->releasePurchase($this->purchase_id);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/release", $transaction['request']->getUri()->getPath());
	}
	
	public function testCaptureWithoutAmount() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->capturePurchase($this->purchase_id);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/capture", $transaction['request']->getUri()->getPath());
		$this->assertEmpty($transaction['request']->getBody()->getContents());
	}
	
	public function testCaptureWithAmount() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->capturePurchase($this->purchase_id, 100);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/capture", $transaction['request']->getUri()->getPath());
		$body = json_decode($transaction['request']->getBody()->getContents(), true);
		$this->assertEquals(100, $body['amount']);
	}
	
	public function testChargePurchase() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->chargePurchase($this->purchase_id, 'token');
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/charge", $transaction['request']->getUri()->getPath());
		$body = json_decode($transaction['request']->getBody()->getContents(), true);
		$this->assertEquals('token', $body['recurring_token']);
	}
	
	public function testDeleteRecurringToken() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->deleteRecurringToken($this->purchase_id);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/delete_recurring_token", $transaction['request']->getUri()->getPath());
	}
	
	public function testVerify() {
		$content = '{"id": "", "due": 1642060235, "type": "purchase", "client": {"cc": [], "bcc": [], "city": "", "email": "", "phone": "", "country": "", "zip_code": "", "bank_code": "", "full_name": "", "brand_name": "", "legal_name": "", "tax_number": "", "client_type": null, "bank_account": "", "personal_code": "", "shipping_city": "", "street_address": "", "shipping_country": "", "shipping_zip_code": "", "registration_number": "", "shipping_street_address": ""}, "issued": "", "status": "created", "is_test": true, "payment": null, "product": "purchases", "user_id": null, "brand_id": "", "order_id": null, "platform": "api", "purchase": {"debt": 0, "notes": "", "total": 100, "currency": "EUR", "language": "en", "products": [{"name": "test", "price": 100, "category": "", "discount": 0, "quantity": "1.0000", "tax_percent": "0.00"}], "timezone": "UTC", "due_strict": false, "email_message": "", "total_override": null, "shipping_options": [], "subtotal_override": null, "total_tax_override": null, "payment_method_details": {}, "request_client_details": [], "total_discount_override": null}, "client_id": null, "reference": "", "viewed_on": null, "company_id": "", "created_on": 1642056635, "event_type": "purchase.created", "updated_on": 1642056635, "invoice_url": null, "checkout_url": "", "send_receipt": false, "skip_capture": false, "creator_agent": "", "issuer_details": {"website": "", "brand_name": "", "legal_city": "", "legal_name": "", "tax_number": "", "bank_accounts": [{"bank_code": "", "bank_account": ""}], "legal_country": "", "legal_zip_code": "", "registration_number": "", "legal_street_address": ""}, "marked_as_paid": false, "status_history": [{"status": "created", "timestamp": 1642056635}], "cancel_redirect": "", "created_from_ip": "", "direct_post_url": null, "force_recurring": false, "recurring_token": null, "failure_redirect": "", "success_callback": "", "success_redirect": "", "transaction_data": {"flow": "payform", "extra": {}, "country": "", "attempts": [], "payment_method": ""}, "refundable_amount": 0, "is_recurring_token": false, "billing_template_id": null, "currency_conversion": null, "reference_generated": "", "refund_availability": "none", "payment_method_whitelist": null}';
		$signature = 'dHgVBR7qLldrgjMAM0exDnDIBsUU0ZpQC4lkPhAjmjZjkFlRoIYcaC4fR03avykxujZwakM1mGjvInFvCHE8zrrUemeJhHSHN+8n54zecQQ0U84JhdDufr0bSXvSduaqLW1cbBEOHKXm4UCVkMp3bRKzPGEYLM0L6PYd00x3yY53gDeOm05HWlXb5UG8hpKHJPhhr5S58r+hStlM0yAI7tkeTTy6neIin7WKS8imeiGGRh6n46mXEtIcwMzmOaRmQ7me3GAxvD8gDEPY6JV6r3eQZpTF7iX/rU0pod0P35XTvQ3pO2HMBCeRm5zfFCva9JGEVvtiJ1ZDZO/4/UfPEQ==';
		$publicKey = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArzedRaG/aa191+f3/Syf\nye4lbwaVDngwBpsV/JidZ3T/27oEAPtwZ3oqhmhsBQcVB/f94ecFdj49NTG1DZZN\nfkWjSZEViL22oEGBryK2MjkUrW30kY1Yh0vCa/e0nIG/+9b1TLfzHIwjm54hw1R/\nRi/m/tf1nLMEm06ogDNV/AUyg6uyNLqp21NxKP7+xV6yfPkfX1s+qSjciyCPzO6r\n+TsG3GTqopG1FSaWx+R0+bmsOEmV5YQKMUlLKlf0wJUD7mjsNioFomEp5QBpASbE\nLfNDO13L5FiUgLtWcz+ZazCZmNUdhstLvrEVt8NhvPWBy96YWm4GfXx7xr8F11yH\npQIDAQAB\n-----END PUBLIC KEY-----";
		
		$this->assertTrue(\Chip\ChipApi::verify($content, $signature, $publicKey));
	}
	
	public function testMarkAsPaid() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->markAsPaid($this->purchase_id);
		$transaction = $container[0];

		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/mark_as_paid/", $transaction['request']->getUri()->getPath());
	}
	
	public function testGetClient() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->getClient('client_123');
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('clients/client_123/', $transaction['request']->getUri()->getPath());
	}

	public function testUpdateClient() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$client = new \Chip\Model\ClientDetails();
		$client->email = 'updated@example.com';
		$api->updateClient('client_123', $client);
		$transaction = $container[0];

		$this->assertEquals('PUT', $transaction['request']->getMethod());
		$this->assertStringContainsString('clients/client_123/', $transaction['request']->getUri()->getPath());
	}

	public function testPartialUpdateClient() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$client = new \Chip\Model\ClientDetails();
		$client->email = 'updated@example.com';
		$api->partialUpdateClient('client_123', $client);
		$transaction = $container[0];

		$this->assertEquals('PATCH', $transaction['request']->getMethod());
		$this->assertStringContainsString('clients/client_123/', $transaction['request']->getUri()->getPath());
	}

	public function testDeleteClient() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(204, [], '')
		]), $history);
		$api->deleteClient('client_123');
		$transaction = $container[0];

		$this->assertEquals('DELETE', $transaction['request']->getMethod());
		$this->assertStringContainsString('clients/client_123/', $transaction['request']->getUri()->getPath());
	}

	public function testListRecurringTokens() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->listRecurringTokens('client_123');
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('clients/client_123/recurring_tokens/', $transaction['request']->getUri()->getPath());
	}

	public function testGetRecurringToken() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->getRecurringToken('client_123', 'purchase_456');
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('clients/client_123/recurring_tokens/purchase_456/', $transaction['request']->getUri()->getPath());
	}

	public function testDeleteRecurringTokenByClient() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(204, [], '')
		]), $history);
		$api->deleteRecurringTokenByClient('client_123', 'purchase_456');
		$transaction = $container[0];

		$this->assertEquals('DELETE', $transaction['request']->getMethod());
		$this->assertStringContainsString('clients/client_123/recurring_tokens/purchase_456/', $transaction['request']->getUri()->getPath());
	}

	public function testListWebhooks() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->listWebhooks();
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('webhooks/', $transaction['request']->getUri()->getPath());
	}

	public function testUpdateWebhook() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$webhook = new \Chip\Model\Webhook();
		$webhook->title = 'Updated';
		$api->updateWebhook('wh_123', $webhook);
		$transaction = $container[0];

		$this->assertEquals('PUT', $transaction['request']->getMethod());
		$this->assertStringContainsString('webhooks/wh_123/', $transaction['request']->getUri()->getPath());
	}

	public function testPartialUpdateWebhook() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$webhook = new \Chip\Model\Webhook();
		$webhook->title = 'Updated';
		$api->partialUpdateWebhook('wh_123', $webhook);
		$transaction = $container[0];

		$this->assertEquals('PATCH', $transaction['request']->getMethod());
		$this->assertStringContainsString('webhooks/wh_123/', $transaction['request']->getUri()->getPath());
	}

	public function testGetPublicKey() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], json_encode(['public_key' => 'pk_test']))
		]), $history);
		$key = $api->getPublicKey();
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('public_key/', $transaction['request']->getUri()->getPath());
		$this->assertEquals('pk_test', $key);
	}

	public function testGetBalance() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], json_encode(['MYR' => ['balance' => 100]]))
		]), $history);
		$result = $api->getBalance(['currency' => 'MYR']);
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('account/json/balance/', $transaction['request']->getUri()->getPath());
		$this->assertStringContainsString('currency=MYR', $transaction['request']->getUri()->getQuery());
		$this->assertEquals(['MYR' => ['balance' => 100]], $result);
	}

	public function testGetTurnover() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], json_encode(['incoming' => ['turnover' => 50], 'outgoing' => ['turnover' => 20]]))
		]), $history);
		$result = $api->getTurnover(['currency' => 'MYR', 'from' => 1609459200, 'to' => 1609545600]);
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('account/json/turnover/', $transaction['request']->getUri()->getPath());
		$query = $transaction['request']->getUri()->getQuery();
		$this->assertStringContainsString('currency=MYR', $query);
		$this->assertStringContainsString('from=1609459200', $query);
		$this->assertStringContainsString('to=1609545600', $query);
	}

	public function testScheduleStatement() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(201, [], '{}')
		]), $history);
		$statement = new \Chip\Model\CompanyStatement();
		$statement->format = 'csv';
		$api->scheduleStatement($statement);
		$transaction = $container[0];

		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString('company_statements/', $transaction['request']->getUri()->getPath());
	}

	public function testListStatements() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->listStatements();
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('company_statements/', $transaction['request']->getUri()->getPath());
	}

	public function testGetStatement() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->getStatement('stmt_123');
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$this->assertStringContainsString('company_statements/stmt_123/', $transaction['request']->getUri()->getPath());
	}

	public function testCancelStatement() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->cancelStatement('stmt_123');
		$transaction = $container[0];

		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString('company_statements/stmt_123/cancel', $transaction['request']->getUri()->getPath());
	}

	public function testResendInvoice() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->resendInvoice($this->purchase_id);
		$transaction = $container[0];

		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString("purchases/$this->purchase_id/resend_invoice", $transaction['request']->getUri()->getPath());
	}

	public function testPaymentMethodsWithOptions() {
		$container = [];
		$history = Middleware::history($container);
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->getPaymentMethods('MYR', ['country' => 'MY', 'recurring' => true]);
		$transaction = $container[0];

		$this->assertEquals('GET', $transaction['request']->getMethod());
		$query = $transaction['request']->getUri()->getQuery();
		$this->assertStringContainsString('country=MY', $query);
		$this->assertStringContainsString('recurring=1', $query);
	}

	protected function getMockApi($mock, $history) {
		$handlerStack = HandlerStack::create($mock);
		$handlerStack->push($history);
		return new \Chip\ChipApi('', '', 'https://gate.chip-in.asia/api/v1/', [
			'handler' => $handlerStack
		]);
	}

}