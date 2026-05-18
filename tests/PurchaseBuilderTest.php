<?php

use PHPUnit\Framework\TestCase;

final class PurchaseBuilderTest extends TestCase
{
    public function testBuildsPurchaseWithFluentApi(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->currency('USD')
            ->language('en')
            ->successRedirect('https://example.com/success')
            ->failureRedirect('https://example.com/failure')
            ->successCallback('https://example.com/callback')
            ->clientEmail('test@example.com')
            ->clientFullName('Test User')
            ->addProduct('Widget', 5000, 2)
            ->addProduct('Gadget', 3000)
            ->build();

        $this->assertInstanceOf(\Chip\Model\Purchase::class, $purchase);
        $this->assertEquals('brand_123', $purchase->brand_id);
        $this->assertEquals('USD', $purchase->purchase->currency);
        $this->assertEquals('en', $purchase->purchase->language);
        $this->assertEquals('https://example.com/success', $purchase->success_redirect);
        $this->assertEquals('https://example.com/failure', $purchase->failure_redirect);
        $this->assertEquals('https://example.com/callback', $purchase->success_callback);

        $this->assertInstanceOf(\Chip\Model\ClientDetails::class, $purchase->client);
        $this->assertEquals('test@example.com', $purchase->client->email);
        $this->assertEquals('Test User', $purchase->client->full_name);

        $this->assertCount(2, $purchase->purchase->products);
        $this->assertEquals('Widget', $purchase->purchase->products[0]->name);
        $this->assertEquals(5000, $purchase->purchase->products[0]->price);
        $this->assertEquals('2', $purchase->purchase->products[0]->quantity);
        $this->assertEquals('Gadget', $purchase->purchase->products[1]->name);
        $this->assertEquals(3000, $purchase->purchase->products[1]->price);
        $this->assertEquals('1', $purchase->purchase->products[1]->quantity);
    }

    public function testProductsArrayDefaultsToEmpty(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->build();

        $this->assertEmpty($purchase->purchase->products);
    }

    public function testTopLevelPurchaseFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->clientId('client_456')
            ->sendReceipt(true)
            ->skipCapture(true)
            ->forceRecurring(true)
            ->reference('INV-001')
            ->issued('2024-01-01')
            ->due(1704067200)
            ->creatorAgent('woocommerce/1.0')
            ->platform('web')
            ->tags(['tag1', 'tag2'])
            ->paymentMethodWhitelist(['visa', 'mastercard'])
            ->build();

        $this->assertEquals('brand_123', $purchase->brand_id);
        $this->assertEquals('client_456', $purchase->client_id);
        $this->assertTrue($purchase->send_receipt);
        $this->assertTrue($purchase->skip_capture);
        $this->assertTrue($purchase->force_recurring);
        $this->assertEquals('INV-001', $purchase->reference);
        $this->assertEquals('2024-01-01', $purchase->issued);
        $this->assertEquals(1704067200, $purchase->due);
        $this->assertEquals('woocommerce/1.0', $purchase->creator_agent);
        $this->assertEquals('web', $purchase->platform);
        $this->assertEquals(['tag1', 'tag2'], $purchase->tags);
        $this->assertEquals(['visa', 'mastercard'], $purchase->payment_method_whitelist);
    }

    public function testNullableTopLevelFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->clientId(null)
            ->issued(null)
            ->due(null)
            ->build();

        $this->assertNull($purchase->client_id);
        $this->assertNull($purchase->issued);
        $this->assertNull($purchase->due);
    }

    public function testPurchaseDetailsFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->currency('MYR')
            ->notes('Invoice notes')
            ->debt(100)
            ->subtotalOverride(5000)
            ->totalTaxOverride(300)
            ->totalDiscountOverride(200)
            ->totalOverride(5100)
            ->requestClientDetails(['email', 'full_name'])
            ->timezone('Asia/Kuala_Lumpur')
            ->dueStrict(true)
            ->emailMessage('Thank you for your purchase')
            ->shippingOptions([['name' => 'Standard', 'price' => 500]])
            ->paymentMethodDetails((object)['card' => 'visa'])
            ->hasUpsellProducts(true)
            ->singleAttempt(true)
            ->metadata((object)['order_id' => '123'])
            ->addProduct('Test', 1000)
            ->build();

        $this->assertEquals('MYR', $purchase->purchase->currency);
        $this->assertEquals('Invoice notes', $purchase->purchase->notes);
        $this->assertEquals(100, $purchase->purchase->debt);
        $this->assertEquals(5000, $purchase->purchase->subtotal_override);
        $this->assertEquals(300, $purchase->purchase->total_tax_override);
        $this->assertEquals(200, $purchase->purchase->total_discount_override);
        $this->assertEquals(5100, $purchase->purchase->total_override);
        $this->assertEquals(['email', 'full_name'], $purchase->purchase->request_client_details);
        $this->assertEquals('Asia/Kuala_Lumpur', $purchase->purchase->timezone);
        $this->assertTrue($purchase->purchase->due_strict);
        $this->assertEquals('Thank you for your purchase', $purchase->purchase->email_message);
        $this->assertEquals([['name' => 'Standard', 'price' => 500]], $purchase->purchase->shipping_options);
        $this->assertEquals((object)['card' => 'visa'], $purchase->purchase->payment_method_details);
        $this->assertTrue($purchase->purchase->has_upsell_products);
        $this->assertTrue($purchase->purchase->single_attempt);
        $this->assertEquals((object)['order_id' => '123'], $purchase->purchase->metadata);
    }

    public function testNullablePurchaseDetailsFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->subtotalOverride(null)
            ->totalTaxOverride(null)
            ->totalDiscountOverride(null)
            ->totalOverride(null)
            ->paymentMethodDetails(null)
            ->metadata(null)
            ->addProduct('Test', 1000)
            ->build();

        $this->assertNull($purchase->purchase->subtotal_override);
        $this->assertNull($purchase->purchase->total_tax_override);
        $this->assertNull($purchase->purchase->total_discount_override);
        $this->assertNull($purchase->purchase->total_override);
        $this->assertNull($purchase->purchase->payment_method_details);
        $this->assertNull($purchase->purchase->metadata);
    }

    public function testClientDetailsFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->clientEmail('test@example.com')
            ->clientPhone('+60123456789')
            ->clientFullName('John Doe')
            ->clientPersonalCode('PC123')
            ->clientStreetAddress('123 Main St')
            ->clientCountry('MY')
            ->clientCity('Kuala Lumpur')
            ->clientZipCode('50000')
            ->clientState('KL')
            ->clientShippingStreetAddress('456 Shipping Ave')
            ->clientShippingCountry('MY')
            ->clientShippingCity('Petaling Jaya')
            ->clientShippingZipCode('47400')
            ->clientShippingState('PJ')
            ->clientCc(['cc@example.com'])
            ->clientBcc(['bcc@example.com'])
            ->clientLegalName('Legal Name Sdn Bhd')
            ->clientBrandName('Brand Name')
            ->clientRegistrationNumber('REG123')
            ->clientTaxNumber('TAX123')
            ->clientBankAccount('1234567890')
            ->clientBankCode('MBB')
            ->build();

        $client = $purchase->client;
        $this->assertInstanceOf(\Chip\Model\ClientDetails::class, $client);
        $this->assertEquals('test@example.com', $client->email);
        $this->assertEquals('+60123456789', $client->phone);
        $this->assertEquals('John Doe', $client->full_name);
        $this->assertEquals('PC123', $client->personal_code);
        $this->assertEquals('123 Main St', $client->street_address);
        $this->assertEquals('MY', $client->country);
        $this->assertEquals('Kuala Lumpur', $client->city);
        $this->assertEquals('50000', $client->zip_code);
        $this->assertEquals('KL', $client->state);
        $this->assertEquals('456 Shipping Ave', $client->shipping_street_address);
        $this->assertEquals('MY', $client->shipping_country);
        $this->assertEquals('Petaling Jaya', $client->shipping_city);
        $this->assertEquals('47400', $client->shipping_zip_code);
        $this->assertEquals('PJ', $client->shipping_state);
        $this->assertEquals(['cc@example.com'], $client->cc);
        $this->assertEquals(['bcc@example.com'], $client->bcc);
        $this->assertEquals('Legal Name Sdn Bhd', $client->legal_name);
        $this->assertEquals('Brand Name', $client->brand_name);
        $this->assertEquals('REG123', $client->registration_number);
        $this->assertEquals('TAX123', $client->tax_number);
        $this->assertEquals('1234567890', $client->bank_account);
        $this->assertEquals('MBB', $client->bank_code);
    }

    public function testNullableClientDetailsFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->clientEmail('test@example.com')
            ->clientState(null)
            ->clientShippingState(null)
            ->clientBankAccount(null)
            ->clientBankCode(null)
            ->build();

        $this->assertNull($purchase->client->state);
        $this->assertNull($purchase->client->shipping_state);
        $this->assertNull($purchase->client->bank_account);
        $this->assertNull($purchase->client->bank_code);
    }

    public function testProductWithAllFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->addProduct('Widget', 5000, 2, 100, '6.00', 'Electronics', 4900)
            ->build();

        $product = $purchase->purchase->products[0];
        $this->assertEquals('Widget', $product->name);
        $this->assertEquals(5000, $product->price);
        $this->assertEquals('2', $product->quantity);
        $this->assertEquals(100, $product->discount);
        $this->assertEquals('6.00', $product->tax_percent);
        $this->assertEquals('Electronics', $product->category);
        $this->assertEquals(4900, $product->total_price_override);
    }

    public function testProductWithNullOptionalFields(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->addProduct('Widget', 5000)
            ->build();

        $product = $purchase->purchase->products[0];
        $this->assertNull($product->discount);
        $this->assertNull($product->tax_percent);
        $this->assertNull($product->category);
        $this->assertNull($product->total_price_override);
    }

    public function testClientIsCreatedOnlyOnce(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->clientEmail('test@example.com')
            ->clientFullName('Test User')
            ->clientPhone('+60123456789')
            ->build();

        $this->assertSame($purchase->client, $purchase->client);
    }
}
