<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;
use Chip\Model\Purchase;
use Chip\Support\Money;

final class PurchasesResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    public function create(Purchase $purchase): Purchase
    {
        $response = $this->client->request('POST', 'purchases/', [
            'json' => $purchase,
        ]);

        return Purchase::fromArray((array) $response);
    }

    public function get(string $purchaseId): Purchase
    {
        $response = $this->client->request('GET', "purchases/$purchaseId/");

        return Purchase::fromArray((array) $response);
    }

    public function cancel(string $purchaseId): Purchase
    {
        $response = $this->client->request('POST', "purchases/$purchaseId/cancel/");

        return Purchase::fromArray((array) $response);
    }

    public function release(string $purchaseId): Purchase
    {
        $response = $this->client->request('POST', "purchases/$purchaseId/release/");

        return Purchase::fromArray((array) $response);
    }

    public function capture(string $purchaseId, int|float|string|null $amount = null): Purchase
    {
        $options = [];
        if ($amount !== null) {
            $options['json'] = ['amount' => Money::coerce($amount)];
        }

        $response = $this->client->request('POST', "purchases/$purchaseId/capture/", $options);

        return Purchase::fromArray((array) $response);
    }

    public function charge(string $purchaseId, string $token): Purchase
    {
        $response = $this->client->request('POST', "purchases/$purchaseId/charge/", [
            'json' => ['recurring_token' => $token],
        ]);

        return Purchase::fromArray((array) $response);
    }

    public function deleteRecurringToken(string $purchaseId): Purchase
    {
        $response = $this->client->request('POST', "purchases/$purchaseId/delete_recurring_token/");

        return Purchase::fromArray((array) $response);
    }

    public function refund(string $purchaseId, int|float|string|null $amount = null): Purchase
    {
        $options = [];
        if ($amount !== null) {
            $options['json'] = ['amount' => Money::coerce($amount)];
        }

        $response = $this->client->request('POST', "purchases/$purchaseId/refund/", $options);

        return Purchase::fromArray((array) $response);
    }

    public function markAsPaid(string $purchaseId, ?int $utcTimestamp = null): Purchase
    {
        $options = [];
        if ($utcTimestamp !== null) {
            $options['json'] = ['paid_on' => $utcTimestamp];
        }

        $response = $this->client->request('POST', "purchases/$purchaseId/mark_as_paid/", $options);

        return Purchase::fromArray((array) $response);
    }

    public function resendInvoice(string $purchaseId): Purchase
    {
        $response = $this->client->request('POST', "purchases/$purchaseId/resend_invoice/");

        return Purchase::fromArray((array) $response);
    }
}
