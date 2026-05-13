<?php

namespace Chip\Exception;

use Exception;

class ChipApiException extends Exception
{
    /** @var array<string, mixed>|null */
    protected ?array $responseBody;

    /**
     * @param array<string, mixed>|null $responseBody
     */
    public function __construct(string $message = '', int $code = 0, ?array $responseBody = null, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->responseBody = $responseBody;
    }

    /** @return array<string, mixed>|null */
    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }
}
