<?php

declare(strict_types=1);

use Chip\Builder\PurchaseBuilder;
use Chip\Exception\InvalidMoneyValueException;
use Chip\Model\Product;
use Chip\Model\PurchaseDetails;
use Chip\Support\Money;
use PHPUnit\Framework\TestCase;

final class MoneyCoercionTest extends TestCase
{
    // ===== Money::coerce unit behaviour =====

    public function testCoercePassesThroughInt(): void
    {
        $this->assertSame(108, Money::coerce(108));
        $this->assertSame(0, Money::coerce(0));
    }

    public function testCoercePassesThroughNull(): void
    {
        $this->assertNull(Money::coerce(null));
    }

    public function testCoerceIntValuedFloat(): void
    {
        $this->assertSame(108, Money::coerce(108.0));
        $this->assertSame(10800, Money::coerce(10800.0));
    }

    public function testCoerceFloatRoundingNoise(): void
    {
        // 0.29 * 100 in binary floating point is 28.999999999999996 — a whole
        // number in intent, fractional in representation. This exact value was
        // rejected by the API with 400 "A valid integer is required.".
        $this->assertSame(29, Money::coerce(0.29 * 100));
        $this->assertSame(29, Money::coerce(28.999999999999996));
    }

    public function testCoerceNumericStrings(): void
    {
        $this->assertSame(108, Money::coerce('108'));
        $this->assertSame(108, Money::coerce('108.00'));
        $this->assertSame(10800, Money::coerce('108.00' * 100));
    }

    public function testCoerceRejectsGenuineFraction(): void
    {
        $this->expectException(InvalidMoneyValueException::class);
        Money::coerce(108.5);
    }

    public function testCoerceRejectsNonNumeric(): void
    {
        $this->expectException(InvalidMoneyValueException::class);
        Money::coerce('12abc');
    }

    public function testCoerceRejectsNanAndInfinity(): void
    {
        $this->expectException(InvalidMoneyValueException::class);
        Money::coerce(NAN);
    }

    // ===== Product serialization (covers direct model usage, v1-style apps) =====

    public function testProductSerializesFloatNoiseAsInteger(): void
    {
        $product = new \Chip\Model\Product();
        $product->name = 'X';
        $product->price = 0.29 * 100; // @phpstan-ignore assign.propertyType (raw app input on purpose)
        $product->quantity = '1';

        $decoded = json_decode((string) json_encode($product), true);
        $this->assertSame(29, $decoded['price']);
    }

    public function testProductSerializeRejectsGenuineFraction(): void
    {
        $product = new \Chip\Model\Product();
        $product->name = 'X';
        $product->price = 108.5; // @phpstan-ignore assign.propertyType (raw app input on purpose)

        $this->expectException(InvalidMoneyValueException::class);
        $this->expectExceptionMessage('price');
        json_encode($product);
    }

    public function testProductFromArrayCoercesMoneyFields(): void
    {
        $product = Product::fromArray([
            'name' => 'X',
            'price' => '108.00',
            'discount' => 2.0,
            'total_price_override' => 106.0,
        ]);

        $this->assertSame(108, $product->price);
        $this->assertSame(2, $product->discount);
        $this->assertSame(106, $product->total_price_override);
    }

    // ===== PurchaseDetails =====

    public function testPurchaseDetailsSerializesOverrideFloatsAsIntegers(): void
    {
        $details = new PurchaseDetails();
        $details->total = 100.0 * 100; // @phpstan-ignore assign.propertyType (raw app input on purpose)
        $details->total_override = '999.00'; // @phpstan-ignore assign.propertyType (raw app input on purpose)

        $decoded = json_decode((string) json_encode($details), true);
        $this->assertSame(10000, $decoded['total']);
        $this->assertSame(999, $decoded['total_override']);
    }

    public function testPurchaseDetailsSerializeRejectsFractionalOverride(): void
    {
        $details = new PurchaseDetails();
        $details->total_override = 108.25; // @phpstan-ignore assign.propertyType (raw app input on purpose)

        $this->expectException(InvalidMoneyValueException::class);
        $this->expectExceptionMessage('total_override');
        json_encode($details);
    }

    // ===== Builder =====

    public function testBuilderAcceptsFloatNoisePrice(): void
    {
        $purchase = PurchaseBuilder::create()
            ->currency('MYR')
            ->addProduct('X', 0.29 * 100)
            ->build();

        $this->assertSame(29, $purchase->purchase->products[0]->price);
        $this->assertStringNotContainsString('28.9', (string) json_encode($purchase));
    }

    public function testBuilderAcceptsStringPrice(): void
    {
        $purchase = PurchaseBuilder::create()
            ->currency('MYR')
            ->addProduct('X', '108.00')
            ->build();

        $this->assertSame(108, $purchase->purchase->products[0]->price);
    }

    public function testBuilderRejectsFractionalPrice(): void
    {
        $this->expectException(InvalidMoneyValueException::class);
        PurchaseBuilder::create()->addProduct('X', 108.999);
    }

    public function testBuilderCoercesDiscountAndOverride(): void
    {
        $purchase = PurchaseBuilder::create()
            ->currency('MYR')
            ->addProduct('X', 100, 1.0, 1.0, null, null, 99.0000000001)
            ->build();

        $product = $purchase->purchase->products[0];
        $this->assertSame(1, $product->discount);
        $this->assertSame(99, $product->total_price_override);
    }

    public function testBuilderCoercesTotalOverrideMoney(): void
    {
        $purchase = PurchaseBuilder::create()
            ->currency('MYR')
            ->addProduct('X', 100)
            ->totalOverride('1000.00')
            ->build();

        $decoded = json_decode((string) json_encode($purchase), true);
        $this->assertSame(1000, $decoded['purchase']['total_override']);
    }
}
