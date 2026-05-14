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
        $this->assertEquals(2.0, $purchase->purchase->products[0]->quantity);
        $this->assertEquals('Gadget', $purchase->purchase->products[1]->name);
        $this->assertEquals(3000, $purchase->purchase->products[1]->price);
        $this->assertEquals(1.0, $purchase->purchase->products[1]->quantity);
    }

    public function testProductsArrayDefaultsToEmpty(): void
    {
        $purchase = \Chip\Builder\PurchaseBuilder::create()
            ->brandId('brand_123')
            ->build();

        $this->assertEmpty($purchase->purchase->products);
    }
}
