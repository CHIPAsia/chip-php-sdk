<?php

namespace Chip\Model;

class ClientDetails implements \JsonSerializable
{
    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $full_name;

    /**
     *
     * @var string
     */
    public $personal_code;

    /**
     *
     * @var string
     */
    public $street_address;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $zip_code;

    /**
     *
     * @var string
     */
    public $shipping_street_address;

    /**
     *
     * @var string
     */
    public $shipping_country;

    /**
     *
     * @var string
     */
    public $shipping_city;

    /**
     *
     * @var string
     */
    public $shipping_zip_code;

    /**
     *
     * @var string[]
     */
    public $cc;

    /**
     *
     * @var string[]
     */
    public $bcc;

    /**
     *
     * @var string
     */
    public $legal_name;

    /**
     *
     * @var string
     */
    public $brand_name;

    /**
     *
     * @var string
     */
    public $registration_number;

    /**
     *
     * @var string
     */
    public $tax_number;

    /**
     *
     * @var string|null
     */
    public $state;

    /**
     *
     * @var string|null
     */
    public $shipping_state;

    /**
     *
     * @var string|null
     */
    public $bank_account;

    /**
     *
     * @var string|null
     */
    public $bank_code;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $client = new self();
        $client->id = $data['id'] ?? '';
        $client->email = $data['email'] ?? '';
        $client->phone = $data['phone'] ?? '';
        $client->full_name = $data['full_name'] ?? '';
        $client->personal_code = $data['personal_code'] ?? '';
        $client->street_address = $data['street_address'] ?? '';
        $client->country = $data['country'] ?? '';
        $client->city = $data['city'] ?? '';
        $client->zip_code = $data['zip_code'] ?? '';
        $client->shipping_street_address = $data['shipping_street_address'] ?? '';
        $client->shipping_country = $data['shipping_country'] ?? '';
        $client->shipping_city = $data['shipping_city'] ?? '';
        $client->shipping_zip_code = $data['shipping_zip_code'] ?? '';
        $client->cc = $data['cc'] ?? [];
        $client->bcc = $data['bcc'] ?? [];
        $client->legal_name = $data['legal_name'] ?? '';
        $client->brand_name = $data['brand_name'] ?? '';
        $client->registration_number = $data['registration_number'] ?? '';
        $client->tax_number = $data['tax_number'] ?? '';
        $client->state = $data['state'] ?? null;
        $client->shipping_state = $data['shipping_state'] ?? null;
        $client->bank_account = $data['bank_account'] ?? null;
        $client->bank_code = $data['bank_code'] ?? null;

        return $client;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
