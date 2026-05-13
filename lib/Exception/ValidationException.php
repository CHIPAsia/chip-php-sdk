<?php

namespace Chip\Exception;

class ValidationException extends ChipApiException
{
    /** @var array<string, mixed> */
    protected array $errors = [];

    /**
     * @param array<string, mixed>|null $responseBody
     */
    public function __construct(string $message = '', int $code = 0, ?array $responseBody = null, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $responseBody, $previous);

        if ($responseBody !== null && isset($responseBody['errors']) && is_array($responseBody['errors'])) {
            $this->errors = $responseBody['errors'];
        }
    }

    /** @return array<string, mixed> */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
