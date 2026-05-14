<?php

namespace Chip\Model;

class BankAccount implements \JsonSerializable
{
    /**
     *
     * @var string
     */
    public $bank_account;

    /**
     *
     * @var string
     */
    public $bank_code;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $account = new self();
        $account->bank_account = $data['bank_account'] ?? '';
        $account->bank_code = $data['bank_code'] ?? '';

        return $account;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_filter((array) $this);
    }
}
