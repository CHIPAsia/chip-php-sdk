<?php

namespace Chip\Model;

class IssuerDetails implements \JsonSerializable
{
    /**
     *
     * @var string
     */
    public $website;

    /**
     *
     * @var string
     */
    public $legal_street_address;

    /**
     *
     * @var string
     */
    public $legal_country;

    /**
     *
     * @var string
     */
    public $legal_city;

    /**
     *
     * @var string
     */
    public $legal_zip_code;

    /**
     *
     * @var BankAccount[]
     */
    public $bank_accounts;

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
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $details = new self();
        $details->website = $data['website'] ?? '';
        $details->legal_street_address = $data['legal_street_address'] ?? '';
        $details->legal_country = $data['legal_country'] ?? '';
        $details->legal_city = $data['legal_city'] ?? '';
        $details->legal_zip_code = $data['legal_zip_code'] ?? '';
        $details->bank_accounts = array_map(
            fn (array $a) => BankAccount::fromArray($a),
            $data['bank_accounts'] ?? []
        );
        $details->legal_name = $data['legal_name'] ?? '';
        $details->brand_name = $data['brand_name'] ?? '';
        $details->registration_number = $data['registration_number'] ?? '';
        $details->tax_number = $data['tax_number'] ?? '';

        return $details;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
