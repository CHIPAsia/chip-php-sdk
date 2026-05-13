<?php

declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ApiTest extends TestCase
{
    public function testRefundWithoutAmount(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->refundPurchase('123');
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/refund', $transaction['request']->getUri()->getPath());
        $this->assertEmpty($transaction['request']->getBody()->getContents());
    }

    public function testRefundWithAmount(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->refundPurchase('123', 100);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/refund', $transaction['request']->getUri()->getPath());
        $body = json_decode($transaction['request']->getBody()->getContents(), true);
        $this->assertEquals(100, $body['amount']);
    }

    public function testPaymentMethods(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->getPaymentMethods('USD');
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('payment_methods/', $transaction['request']->getUri()->getPath());
        $body = json_decode($transaction['request']->getBody()->getContents(), true);
        $this->assertStringContainsString('currency=USD', $transaction['request']->getUri()->getQuery());
    }

    public function testCreatePurchase(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->createPurchase(new \Chip\Model\Purchase());
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/', $transaction['request']->getUri()->getPath());
    }

    public function testGetPurchase(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->getPurchase('123');
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/', $transaction['request']->getUri()->getPath());
    }

    public function testCancelPurchase(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->cancelPurchase('123');
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/cancel', $transaction['request']->getUri()->getPath());
    }

    public function testRelasePurchase(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->releasePurchase('123');
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/release', $transaction['request']->getUri()->getPath());
    }

    public function testCaptureWithoutAmount(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->capturePurchase('123');
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/capture', $transaction['request']->getUri()->getPath());
        $this->assertEmpty($transaction['request']->getBody()->getContents());
    }

    public function testCaptureWithAmount(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->capturePurchase('123', 100);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/capture', $transaction['request']->getUri()->getPath());
        $body = json_decode($transaction['request']->getBody()->getContents(), true);
        $this->assertEquals(100, $body['amount']);
    }

    public function testChargePurchase(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->chargePurchase('123', 'token');
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/charge', $transaction['request']->getUri()->getPath());
        $body = json_decode($transaction['request']->getBody()->getContents(), true);
        $this->assertEquals('token', $body['recurring_token']);
    }

    public function testDeleteRecurringToken(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->deleteRecurringToken('123');
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/delete_recurring_token', $transaction['request']->getUri()->getPath());
    }

    public function testVerify(): void
    {
        $content = '{"id": "", "due": 1642060235, "type": "purchase", "client": {"cc": [], "bcc": [], "city": "", "email": "", "phone": "", "country": "", "zip_code": "", "bank_code": "", "full_name": "", "brand_name": "", "legal_name": "", "tax_number": "", "client_type": null, "bank_account": "", "personal_code": "", "shipping_city": "", "street_address": "", "shipping_country": "", "shipping_zip_code": "", "registration_number": "", "shipping_street_address": ""}, "issued": "", "status": "created", "is_test": true, "payment": null, "product": "purchases", "user_id": null, "brand_id": "", "order_id": null, "platform": "api", "purchase": {"debt": 0, "notes": "", "total": 100, "currency": "EUR", "language": "en", "products": [{"name": "test", "price": 100, "category": "", "discount": 0, "quantity": "1.0000", "tax_percent": "0.00"}], "timezone": "UTC", "due_strict": false, "email_message": "", "total_override": null, "shipping_options": [], "subtotal_override": null, "total_tax_override": null, "payment_method_details": {}, "request_client_details": [], "total_discount_override": null}, "client_id": null, "reference": "", "viewed_on": null, "company_id": "", "created_on": 1642056635, "event_type": "purchase.created", "updated_on": 1642056635, "invoice_url": null, "checkout_url": "", "send_receipt": false, "skip_capture": false, "creator_agent": "", "issuer_details": {"website": "", "brand_name": "", "legal_city": "", "legal_name": "", "tax_number": "", "bank_accounts": [{"bank_code": "", "bank_account": ""}], "legal_country": "", "legal_zip_code": "", "registration_number": "", "legal_street_address": ""}, "marked_as_paid": false, "status_history": [{"status": "created", "timestamp": 1642056635}], "cancel_redirect": "", "created_from_ip": "", "direct_post_url": null, "force_recurring": false, "recurring_token": null, "failure_redirect": "", "success_callback": "", "success_redirect": "", "transaction_data": {"flow": "payform", "extra": {}, "country": "", "attempts": [], "payment_method": ""}, "refundable_amount": 0, "is_recurring_token": false, "billing_template_id": null, "currency_conversion": null, "reference_generated": "", "refund_availability": "none", "payment_method_whitelist": null}';
        $signature = 'dHgVBR7qLldrgjMAM0exDnDIBsUU0ZpQC4lkPhAjmjZjkFlRoIYcaC4fR03avykxujZwakM1mGjvInFvCHE8zrrUemeJhHSHN+8n54zecQQ0U84JhdDufr0bSXvSduaqLW1cbBEOHKXm4UCVkMp3bRKzPGEYLM0L6PYd00x3yY53gDeOm05HWlXb5UG8hpKHJPhhr5S58r+hStlM0yAI7tkeTTy6neIin7WKS8imeiGGRh6n46mXEtIcwMzmOaRmQ7me3GAxvD8gDEPY6JV6r3eQZpTF7iX/rU0pod0P35XTvQ3pO2HMBCeRm5zfFCva9JGEVvtiJ1ZDZO/4/UfPEQ==';
        $publicKey = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArzedRaG/aa191+f3/Syf\nye4lbwaVDngwBpsV/JidZ3T/27oEAPtwZ3oqhmhsBQcVB/f94ecFdj49NTG1DZZN\nfkWjSZEViL22oEGBryK2MjkUrW30kY1Yh0vCa/e0nIG/+9b1TLfzHIwjm54hw1R/\nRi/m/tf1nLMEm06ogDNV/AUyg6uyNLqp21NxKP7+xV6yfPkfX1s+qSjciyCPzO6r\n+TsG3GTqopG1FSaWx+R0+bmsOEmV5YQKMUlLKlf0wJUD7mjsNioFomEp5QBpASbE\nLfNDO13L5FiUgLtWcz+ZazCZmNUdhstLvrEVt8NhvPWBy96YWm4GfXx7xr8F11yH\npQIDAQAB\n-----END PUBLIC KEY-----";

        $this->assertTrue(\Chip\ChipApi::verify($content, $signature, $publicKey));
    }

    public function testGetPurchaseMapsResponseToModel(): void
    {
        $responseBody = $this->jsonResponse([
            'id' => 'purchase_123',
            'status' => 'created',
            'brand_id' => 'brand_456',
            'purchase' => [
                'currency' => 'EUR',
                'total' => 100,
                'products' => [
                    ['name' => 'Test Product', 'price' => 100, 'quantity' => 1],
                ],
            ],
            'client' => [
                'email' => 'test@example.com',
            ],
        ]);

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], $responseBody),
        ]), Middleware::history($container));

        $purchase = $api->getPurchase('purchase_123');

        $this->assertInstanceOf(\Chip\Model\Purchase::class, $purchase);
        $this->assertEquals('purchase_123', $purchase->id);
        $this->assertEquals('created', $purchase->status);
        $this->assertEquals('brand_456', $purchase->brand_id);
        $this->assertInstanceOf(\Chip\Model\PurchaseDetails::class, $purchase->purchase);
        $this->assertEquals('EUR', $purchase->purchase->currency);
        $this->assertEquals(100, $purchase->purchase->total);
        $this->assertCount(1, $purchase->purchase->products);
        $this->assertInstanceOf(\Chip\Model\Product::class, $purchase->purchase->products[0]);
        $this->assertEquals('Test Product', $purchase->purchase->products[0]->name);
        $this->assertInstanceOf(\Chip\Model\ClientDetails::class, $purchase->client);
        $this->assertEquals('test@example.com', $purchase->client->email);
    }

    public function testAuthenticationException(): void
    {
        $this->expectException(\Chip\Exception\AuthenticationException::class);
        $this->expectExceptionMessage('Invalid API key');

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(401, [], $this->jsonResponse(['detail' => 'Invalid API key'])),
        ]), Middleware::history($container));

        $api->getPurchase('123');
    }

    public function testNotFoundException(): void
    {
        $this->expectException(\Chip\Exception\NotFoundException::class);
        $this->expectExceptionMessage('Purchase not found');

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(404, [], $this->jsonResponse(['detail' => 'Purchase not found'])),
        ]), Middleware::history($container));

        $api->getPurchase('123');
    }

    public function testValidationException(): void
    {
        $this->expectException(\Chip\Exception\ValidationException::class);
        $this->expectExceptionMessage('Validation failed');

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(422, [], $this->jsonResponse(['detail' => 'Validation failed', 'errors' => ['email' => 'Required']])),
        ]), Middleware::history($container));

        $api->createPurchase(new \Chip\Model\Purchase());
    }

    public function testServerException(): void
    {
        $this->expectException(\Chip\Exception\ServerException::class);
        $this->expectExceptionMessage('Internal server error');

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(500, [], $this->jsonResponse(['detail' => 'Internal server error'])),
        ]), Middleware::history($container));

        $api->getPurchase('123');
    }

    public function testValidationExceptionExposesErrors(): void
    {
        try {
            $container = [];
            $api = $this->getMockApi(new MockHandler([
                new Response(422, [], $this->jsonResponse(['detail' => 'Validation failed', 'errors' => ['email' => 'Required', 'amount' => 'Must be positive']])),
            ]), Middleware::history($container));

            $api->createPurchase(new \Chip\Model\Purchase());
            $this->fail('Expected ValidationException');
        } catch (\Chip\Exception\ValidationException $e) {
            $this->assertEquals(['email' => 'Required', 'amount' => 'Must be positive'], $e->getErrors());
        }
    }

    public function testCreateClient(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $client = new \Chip\Model\ClientDetails();
        $client->email = 'test@example.com';
        $api->createClient($client);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('clients/', $transaction['request']->getUri()->getPath());
    }

    public function testCreateWebhook(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $webhook = new \Chip\Model\Webhook();
        $webhook->title = 'Test Webhook';
        $webhook->callback = 'https://example.com/webhook';
        $api->createWebhook($webhook);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('webhooks/', $transaction['request']->getUri()->getPath());
    }

    public function testGetWebhook(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $api->getWebhook('wh_123');
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('webhooks/wh_123/', $transaction['request']->getUri()->getPath());
    }

    public function testMarkAsPaid(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->markAsPaid('123');
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/mark_as_paid/', $transaction['request']->getUri()->getPath());
    }

    public function testMarkAsPaidWithTimestamp(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);
        $api->markAsPaid('123', 1642060235);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('purchases/123/mark_as_paid/', $transaction['request']->getUri()->getPath());
        $body = json_decode($transaction['request']->getBody()->getContents(), true);
        $this->assertEquals(1642060235, $body['paid_on']);
    }

    public function testPaymentMethodsMapsResponseToModel(): void
    {
        $responseBody = $this->jsonResponse([
            'available_payment_methods' => ['card', 'fpx'],
            'by_country' => ['MY' => ['fpx']],
            'country_names' => ['MY' => 'Malaysia'],
            'names' => ['card' => 'Credit Card', 'fpx' => 'FPX'],
            'card_methods' => ['visa', 'mastercard'],
        ]);

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], $responseBody),
        ]), Middleware::history($container));

        $methods = $api->getPaymentMethods('MYR');

        $this->assertInstanceOf(\Chip\Model\PaymentMethods::class, $methods);
        $this->assertEquals(['card', 'fpx'], $methods->available_payment_methods);
        $this->assertEquals(['MY' => ['fpx']], $methods->by_country);
        $this->assertEquals(['MY' => 'Malaysia'], $methods->country_names);
        $this->assertEquals(['card' => 'Credit Card', 'fpx' => 'FPX'], $methods->names);
        $this->assertEquals(['visa', 'mastercard'], $methods->card_methods);
    }

    public function testWebhookMapsResponseToModel(): void
    {
        $responseBody = $this->jsonResponse([
            'id' => 'wh_123',
            'title' => 'Test Webhook',
            'callback' => 'https://example.com/webhook',
            'public_key' => 'abc123',
            'all_events' => true,
            'events' => ['purchase.created', 'purchase.paid'],
        ]);

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], $responseBody),
        ]), Middleware::history($container));

        $webhook = $api->getWebhook('wh_123');

        $this->assertInstanceOf(\Chip\Model\Webhook::class, $webhook);
        $this->assertEquals('wh_123', $webhook->id);
        $this->assertEquals('Test Webhook', $webhook->title);
        $this->assertEquals('https://example.com/webhook', $webhook->callback);
        $this->assertEquals('abc123', $webhook->public_key);
        $this->assertTrue($webhook->all_events);
        $this->assertEquals(['purchase.created', 'purchase.paid'], $webhook->events);
    }

    public function testClientDetailsMapsResponseToModel(): void
    {
        $responseBody = $this->jsonResponse([
            'id' => 'client_123',
            'email' => 'test@example.com',
            'phone' => '+60123456789',
            'full_name' => 'Test User',
            'country' => 'MY',
            'city' => 'Kuala Lumpur',
        ]);

        $container = [];
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], $responseBody),
        ]), Middleware::history($container));

        $client = new \Chip\Model\ClientDetails();
        $client->email = 'test@example.com';
        $result = $api->createClient($client);

        $this->assertInstanceOf(\Chip\Model\ClientDetails::class, $result);
    }

    public function testLoggerReceivesDebugAndErrorCalls(): void
    {
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $logger->expects($this->once())
            ->method('debug')
            ->with('CHIP API request', $this->arrayHasKey('method'));
        $logger->expects($this->once())
            ->method('error')
            ->with('CHIP API client error', $this->arrayHasKey('status'));

        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(401, [], $this->jsonResponse(['detail' => 'Unauthorized'])),
        ]));
        $api = new \Chip\ChipApi('', '', 'https://gate.chip-in.asia/api/v1/', [
            'handler' => $handlerStack,
        ], $logger);

        try {
            $api->getPurchase('123');
            $this->fail('Expected AuthenticationException');
        } catch (\Chip\Exception\AuthenticationException $e) {
            // expected
        }
    }

    public function testTimeoutConfiguration(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(200, [], '{}'),
        ]));
        $handlerStack->push($history);

        $api = new \Chip\ChipApi('', '', 'https://gate.chip-in.asia/api/v1/', [
            'handler' => $handlerStack,
            'timeout' => 60,
        ]);

        $api->getPurchase('123');
        $transaction = $container[0];

        $this->assertEquals(60, $transaction['options']['timeout']);
    }

    public function testInvalidWebhookVerificationReturnsFalse(): void
    {
        $content = '{"id": "test"}';
        $signature = 'invalid_signature';
        $publicKey = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArzedRaG/aa191+f3/Syf\nye4lbwaVDngwBpsV/JidZ3T/27oEAPtwZ3oqhmhsBQcVB/f94ecFdj49NTG1DZZN\nfkWjSZEViL22oEGBryK2MjkUrW30kY1Yh0vCa/e0nIG/+9b1TLfzHIwjm54hw1R/\nRi/m/tf1nLMEm06ogDNV/AUyg6uyNLqp21NxKP7+xV6yfPkfX1s+qSjciyCPzO6r\n+TsG3GTqopG1FSaWx+R0+bmsOEmV5YQKMUlLKlf0wJUD7mjsNioFomEp5QBpASbE\nLfNDO13L5FiUgLtWcz+ZazCZmNUdhstLvrEVt8NhvPWBy96YWm4GfXx7xr8F11yH\npQIDAQAB\n-----END PUBLIC KEY-----";

        $this->assertFalse(\Chip\ChipApi::verify($content, $signature, $publicKey));
    }

    public function testCreateBilling(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $billing = new \Chip\Model\Billing\BillingTemplate();
        $billing->brand_id = 'brand_123';
        $api->createBilling($billing);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing/', $transaction['request']->getUri()->getPath());
    }

    public function testCreateBillingTemplate(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $billing = new \Chip\Model\Billing\BillingTemplate();
        $billing->brand_id = 'brand_123';
        $api->createBillingTemplate($billing);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/', $transaction['request']->getUri()->getPath());
    }

    public function testGetBillingTemplates(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $api->getBillingTemplates();
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/', $transaction['request']->getUri()->getPath());
    }

    public function testGetBillingTemplate(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $api->getBillingTemplate('bt_123');
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/', $transaction['request']->getUri()->getPath());
    }

    public function testUpdateBillingTemplate(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $billing = new \Chip\Model\Billing\BillingTemplate();
        $billing->title = 'Updated';
        $api->updateBillingTemplate('bt_123', $billing);
        $transaction = $container[0];

        $this->assertEquals('PUT', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/', $transaction['request']->getUri()->getPath());
    }

    public function testDeleteBillingTemplate(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $api->deleteBillingTemplate('bt_123');
        $transaction = $container[0];

        $this->assertEquals('DELETE', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/', $transaction['request']->getUri()->getPath());
    }

    public function testSendBillingTemplateInvoice(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $client = new \Chip\Model\Billing\BillingTemplateClient();
        $client->client_id = 'client_123';
        $api->sendBillingTemplateInvoice('bt_123', $client);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/send_invoice/', $transaction['request']->getUri()->getPath());
    }

    public function testAddBillingTemplateSubscriber(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $client = new \Chip\Model\Billing\BillingTemplateClient();
        $client->client_id = 'client_123';
        $api->addBillingTemplateSubscriber('bt_123', $client);
        $transaction = $container[0];

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/add_subscriber/', $transaction['request']->getUri()->getPath());
    }

    public function testGetBillingTemplateClients(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $api->getBillingTemplateClients('bt_123');
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/clients/', $transaction['request']->getUri()->getPath());
    }

    public function testGetBillingTemplateClient(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $api->getBillingTemplateClient('bt_123', 'bc_456');
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/clients/bc_456/', $transaction['request']->getUri()->getPath());
    }

    public function testUpdateBillingTemplateClient(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $client = new \Chip\Model\Billing\BillingTemplateClient();
        $client->status = 'active';
        $api->updateBillingTemplateClient('bt_123', 'bc_456', $client);
        $transaction = $container[0];

        $this->assertEquals('PATCH', $transaction['request']->getMethod());
        $this->assertStringContainsString('billing_templates/bt_123/clients/bc_456/', $transaction['request']->getUri()->getPath());
    }

    public function testGetClients(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $api = $this->getMockApi(new MockHandler([
            new Response(200, [], '{}'),
        ]), $history);

        $api->getClients();
        $transaction = $container[0];

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertStringContainsString('clients/', $transaction['request']->getUri()->getPath());
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function jsonResponse(array $data): string
    {
        $result = json_encode($data);
        $this->assertIsString($result);

        return $result;
    }

    protected function getMockApi(MockHandler $mock, callable $history): \Chip\ChipApi
    {
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        return new \Chip\ChipApi('', '', 'https://gate.chip-in.asia/api/v1/', [
            'handler' => $handlerStack,
        ]);
    }
}
