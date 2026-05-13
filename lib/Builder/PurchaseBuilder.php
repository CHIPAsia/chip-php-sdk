<?php

namespace Chip\Builder;

use Chip\Model\ClientDetails;
use Chip\Model\Product;
use Chip\Model\Purchase;
use Chip\Model\PurchaseDetails;

class PurchaseBuilder
{
    private Purchase $purchase;

    public function __construct()
    {
        $this->purchase = new Purchase();
        $this->purchase->purchase = new PurchaseDetails();
    }

    public static function create(): self
    {
        return new self();
    }

    public function brandId(string $brandId): self
    {
        $this->purchase->brand_id = $brandId;

        return $this;
    }

    public function currency(string $currency): self
    {
        $this->purchase->purchase->currency = $currency;

        return $this;
    }

    public function language(string $language): self
    {
        $this->purchase->purchase->language = $language;

        return $this;
    }

    public function successRedirect(string $url): self
    {
        $this->purchase->success_redirect = $url;

        return $this;
    }

    public function failureRedirect(string $url): self
    {
        $this->purchase->failure_redirect = $url;

        return $this;
    }

    public function successCallback(string $url): self
    {
        $this->purchase->success_callback = $url;

        return $this;
    }

    public function cancelRedirect(string $url): self
    {
        $this->purchase->cancel_redirect = $url;

        return $this;
    }

    public function clientEmail(string $email): self
    {
        $this->ensureClient();
        $this->purchase->client->email = $email;

        return $this;
    }

    public function clientPhone(string $phone): self
    {
        $this->ensureClient();
        $this->purchase->client->phone = $phone;

        return $this;
    }

    public function clientFullName(string $fullName): self
    {
        $this->ensureClient();
        $this->purchase->client->full_name = $fullName;

        return $this;
    }

    public function addProduct(string $name, int $price, float $quantity = 1.0): self
    {
        $product = new Product();
        $product->name = $name;
        $product->price = $price;
        $product->quantity = $quantity;

        $this->purchase->purchase->products[] = $product;

        return $this;
    }

    public function build(): Purchase
    {
        return $this->purchase;
    }

    private function ensureClient(): void
    {
        if ($this->purchase->client === null) {
            $this->purchase->client = new ClientDetails();
        }
    }
}
