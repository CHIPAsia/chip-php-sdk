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

    public function clientId(?string $clientId): self
    {
        $this->purchase->client_id = $clientId;

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

    public function sendReceipt(bool $sendReceipt): self
    {
        $this->purchase->send_receipt = $sendReceipt;

        return $this;
    }

    public function skipCapture(bool $skipCapture): self
    {
        $this->purchase->skip_capture = $skipCapture;

        return $this;
    }

    public function forceRecurring(bool $forceRecurring): self
    {
        $this->purchase->force_recurring = $forceRecurring;

        return $this;
    }

    public function reference(string $reference): self
    {
        $this->purchase->reference = $reference;

        return $this;
    }

    public function issued(?string $issued): self
    {
        $this->purchase->issued = $issued;

        return $this;
    }

    public function due(?int $due): self
    {
        $this->purchase->due = $due;

        return $this;
    }

    public function creatorAgent(string $creatorAgent): self
    {
        $this->purchase->creator_agent = $creatorAgent;

        return $this;
    }

    public function platform(string $platform): self
    {
        $this->purchase->platform = $platform;

        return $this;
    }

    public function tags(array $tags): self
    {
        $this->purchase->tags = $tags;

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

    public function clientPersonalCode(string $personalCode): self
    {
        $this->ensureClient();
        $this->purchase->client->personal_code = $personalCode;

        return $this;
    }

    public function clientStreetAddress(string $streetAddress): self
    {
        $this->ensureClient();
        $this->purchase->client->street_address = $streetAddress;

        return $this;
    }

    public function clientCountry(string $country): self
    {
        $this->ensureClient();
        $this->purchase->client->country = $country;

        return $this;
    }

    public function clientCity(string $city): self
    {
        $this->ensureClient();
        $this->purchase->client->city = $city;

        return $this;
    }

    public function clientZipCode(string $zipCode): self
    {
        $this->ensureClient();
        $this->purchase->client->zip_code = $zipCode;

        return $this;
    }

    public function clientState(?string $state): self
    {
        $this->ensureClient();
        $this->purchase->client->state = $state;

        return $this;
    }

    public function clientShippingStreetAddress(string $shippingStreetAddress): self
    {
        $this->ensureClient();
        $this->purchase->client->shipping_street_address = $shippingStreetAddress;

        return $this;
    }

    public function clientShippingCountry(string $shippingCountry): self
    {
        $this->ensureClient();
        $this->purchase->client->shipping_country = $shippingCountry;

        return $this;
    }

    public function clientShippingCity(string $shippingCity): self
    {
        $this->ensureClient();
        $this->purchase->client->shipping_city = $shippingCity;

        return $this;
    }

    public function clientShippingZipCode(string $shippingZipCode): self
    {
        $this->ensureClient();
        $this->purchase->client->shipping_zip_code = $shippingZipCode;

        return $this;
    }

    public function clientShippingState(?string $shippingState): self
    {
        $this->ensureClient();
        $this->purchase->client->shipping_state = $shippingState;

        return $this;
    }

    public function clientCc(array $cc): self
    {
        $this->ensureClient();
        $this->purchase->client->cc = $cc;

        return $this;
    }

    public function clientBcc(array $bcc): self
    {
        $this->ensureClient();
        $this->purchase->client->bcc = $bcc;

        return $this;
    }

    public function clientLegalName(string $legalName): self
    {
        $this->ensureClient();
        $this->purchase->client->legal_name = $legalName;

        return $this;
    }

    public function clientBrandName(string $brandName): self
    {
        $this->ensureClient();
        $this->purchase->client->brand_name = $brandName;

        return $this;
    }

    public function clientRegistrationNumber(string $registrationNumber): self
    {
        $this->ensureClient();
        $this->purchase->client->registration_number = $registrationNumber;

        return $this;
    }

    public function clientTaxNumber(string $taxNumber): self
    {
        $this->ensureClient();
        $this->purchase->client->tax_number = $taxNumber;

        return $this;
    }

    public function clientBankAccount(?string $bankAccount): self
    {
        $this->ensureClient();
        $this->purchase->client->bank_account = $bankAccount;

        return $this;
    }

    public function clientBankCode(?string $bankCode): self
    {
        $this->ensureClient();
        $this->purchase->client->bank_code = $bankCode;

        return $this;
    }

    public function addProduct(
        string $name,
        int $price,
        float|string $quantity = 1.0,
        ?int $discount = null,
        ?string $taxPercent = null,
        ?string $category = null,
        ?int $totalPriceOverride = null
    ): self {
        $product = new Product();
        $product->name = $name;
        $product->price = $price;
        $product->quantity = is_string($quantity) ? $quantity : (string) $quantity;
        $product->discount = $discount;
        $product->tax_percent = $taxPercent;
        $product->category = $category;
        $product->total_price_override = $totalPriceOverride;

        $this->purchase->purchase->products[] = $product;

        return $this;
    }

    public function notes(string $notes): self
    {
        $this->purchase->purchase->notes = $notes;

        return $this;
    }

    public function debt(int $debt): self
    {
        $this->purchase->purchase->debt = $debt;

        return $this;
    }

    public function subtotalOverride(?int $subtotalOverride): self
    {
        $this->purchase->purchase->subtotal_override = $subtotalOverride;

        return $this;
    }

    public function totalTaxOverride(?int $totalTaxOverride): self
    {
        $this->purchase->purchase->total_tax_override = $totalTaxOverride;

        return $this;
    }

    public function totalDiscountOverride(?int $totalDiscountOverride): self
    {
        $this->purchase->purchase->total_discount_override = $totalDiscountOverride;

        return $this;
    }

    public function totalOverride(?int $totalOverride): self
    {
        $this->purchase->purchase->total_override = $totalOverride;

        return $this;
    }

    public function requestClientDetails(array $requestClientDetails): self
    {
        $this->purchase->purchase->request_client_details = $requestClientDetails;

        return $this;
    }

    public function timezone(string $timezone): self
    {
        $this->purchase->purchase->timezone = $timezone;

        return $this;
    }

    public function dueStrict(bool $dueStrict): self
    {
        $this->purchase->purchase->due_strict = $dueStrict;

        return $this;
    }

    public function emailMessage(string $emailMessage): self
    {
        $this->purchase->purchase->email_message = $emailMessage;

        return $this;
    }

    public function shippingOptions(array $shippingOptions): self
    {
        $this->purchase->purchase->shipping_options = $shippingOptions;

        return $this;
    }

    public function paymentMethodDetails(?object $paymentMethodDetails): self
    {
        $this->purchase->purchase->payment_method_details = $paymentMethodDetails;

        return $this;
    }

    public function hasUpsellProducts(bool $hasUpsellProducts): self
    {
        $this->purchase->purchase->has_upsell_products = $hasUpsellProducts;

        return $this;
    }

    public function singleAttempt(bool $singleAttempt): self
    {
        $this->purchase->purchase->single_attempt = $singleAttempt;

        return $this;
    }

    public function metadata(?object $metadata): self
    {
        $this->purchase->purchase->metadata = $metadata;

        return $this;
    }

    public function paymentMethodWhitelist(array $methods): self
    {
        $this->purchase->payment_method_whitelist = $methods;

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
