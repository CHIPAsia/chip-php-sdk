<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
{
    public function testPurchaseSerializesOnlyNonNullValues(): void
    {
        $purchase = new \Chip\Model\Purchase();
        $purchase->id = 'p123';
        $purchase->status = 'created';
        $purchase->brand_id = 'brand_456';

        $json = json_encode($purchase);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('p123', $decoded['id']);
        $this->assertEquals('created', $decoded['status']);
        $this->assertEquals('brand_456', $decoded['brand_id']);
        $this->assertArrayNotHasKey('reference', $decoded);
    }

    public function testPurchaseDetailsSerializesProductsArray(): void
    {
        $details = new \Chip\Model\PurchaseDetails();
        $details->currency = 'USD';
        $details->total = 500;
        $details->language = 'en';

        $product = new \Chip\Model\Product();
        $product->name = 'Widget';
        $product->price = 500;
        $product->quantity = '1.0';
        $details->products = [$product];

        $json = json_encode($details);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('USD', $decoded['currency']);
        $this->assertEquals(500, $decoded['total']);
        $this->assertCount(1, $decoded['products']);
        $this->assertEquals('Widget', $decoded['products'][0]['name']);
    }

    public function testProductStripsNullValues(): void
    {
        $product = new \Chip\Model\Product();
        $product->name = 'Gadget';
        $product->price = 100;
        $product->quantity = '2.0';

        $json = json_encode($product);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertArrayHasKey('name', $decoded);
        $this->assertArrayHasKey('price', $decoded);
        $this->assertArrayNotHasKey('discount', $decoded);
        $this->assertArrayNotHasKey('tax_percent', $decoded);
    }

    public function testClientDetailsSerializesAndStripsNulls(): void
    {
        $client = new \Chip\Model\ClientDetails();
        $client->email = 'test@example.com';
        $client->full_name = 'Test User';
        $client->country = 'MY';
        $client->city = 'Kuala Lumpur';

        $json = json_encode($client);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('test@example.com', $decoded['email']);
        $this->assertEquals('Test User', $decoded['full_name']);
        $this->assertArrayNotHasKey('phone', $decoded);
        $this->assertArrayNotHasKey('street_address', $decoded);
    }

    public function testWebhookSerializesAndStripsNulls(): void
    {
        $webhook = new \Chip\Model\Webhook();
        $webhook->id = 'wh_123';
        $webhook->title = 'Test Webhook';
        $webhook->callback = 'https://example.com/webhook';
        $webhook->all_events = true;
        $webhook->events = ['purchase.created'];

        $json = json_encode($webhook);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('wh_123', $decoded['id']);
        $this->assertEquals('Test Webhook', $decoded['title']);
        $this->assertEquals('https://example.com/webhook', $decoded['callback']);
        $this->assertTrue($decoded['all_events']);
        $this->assertEquals(['purchase.created'], $decoded['events']);
        $this->assertArrayNotHasKey('public_key', $decoded);
    }

    public function testPaymentMethodsSerializesAndStripsNulls(): void
    {
        $methods = new \Chip\Model\PaymentMethods();
        $methods->available_payment_methods = ['card', 'fpx'];
        $methods->by_country = ['MY' => ['fpx']];
        $methods->country_names = ['MY' => 'Malaysia'];
        $methods->names = ['card' => 'Credit Card'];
        $methods->card_methods = ['visa', 'mastercard'];

        $json = json_encode($methods);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals(['card', 'fpx'], $decoded['available_payment_methods']);
        $this->assertEquals(['MY' => ['fpx']], $decoded['by_country']);
        $this->assertArrayNotHasKey('logos', $decoded);
    }

    public function testBillingTemplateStripsNulls(): void
    {
        $template = new \Chip\Model\Billing\BillingTemplate();
        $template->brand_id = 'brand_123';
        $template->title = 'Monthly Plan';
        $template->is_subscription = true;

        $json = json_encode($template);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('brand_123', $decoded['brand_id']);
        $this->assertEquals('Monthly Plan', $decoded['title']);
        $this->assertTrue($decoded['is_subscription']);
        $this->assertArrayNotHasKey('invoice_issued', $decoded);
    }

    public function testBillingTemplateClientStripsNulls(): void
    {
        $client = new \Chip\Model\Billing\BillingTemplateClient();
        $client->client_id = 'client_123';
        $client->status = 'active';

        $json = json_encode($client);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('client_123', $decoded['client_id']);
        $this->assertEquals('active', $decoded['status']);
        $this->assertArrayNotHasKey('payment_method_whitelist', $decoded);
    }

    public function testBillingTemplateClientAddSubscriberStripsNulls(): void
    {
        $subscriber = new \Chip\Model\Billing\BillingTemplateClientAddSubscriber();

        $json = json_encode($subscriber);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertIsArray($decoded);
    }

    public function testBillingTemplateClientListStripsNulls(): void
    {
        $list = new \Chip\Model\Billing\BillingTemplateClientList();
        $list->results = [];

        $json = json_encode($list);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        // empty array is filtered out by array_filter
        $this->assertArrayNotHasKey('results', $decoded);
        $this->assertArrayNotHasKey('next', $decoded);
    }

    public function testBillingTemplateListStripsNulls(): void
    {
        $list = new \Chip\Model\Billing\BillingTemplateList();
        $list->results = [];

        $json = json_encode($list);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        // empty array is filtered out by array_filter
        $this->assertArrayNotHasKey('results', $decoded);
        $this->assertArrayNotHasKey('previous', $decoded);
    }

    public function testEmptyModelSerializesToEmptyObject(): void
    {
        $purchase = new \Chip\Model\Purchase();

        $json = json_encode($purchase);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertIsArray($decoded);
        $this->assertEmpty($decoded);
    }

    public function testNestedModelSerialization(): void
    {
        $purchase = new \Chip\Model\Purchase();
        $purchase->brand_id = 'brand_123';

        $details = new \Chip\Model\PurchaseDetails();
        $details->currency = 'EUR';
        $details->total = 100;

        $product = new \Chip\Model\Product();
        $product->name = 'Test';
        $product->price = 100;
        $product->quantity = '1.0';
        $details->products = [$product];

        $purchase->purchase = $details;

        $json = json_encode($purchase);
        $this->assertIsString($json);
        $decoded = json_decode($json, true);

        $this->assertEquals('brand_123', $decoded['brand_id']);
        $this->assertEquals('EUR', $decoded['purchase']['currency']);
        $this->assertCount(1, $decoded['purchase']['products']);
        $this->assertEquals('Test', $decoded['purchase']['products'][0]['name']);
    }
}
