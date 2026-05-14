<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ExceptionTest extends TestCase
{
    public function testChipApiExceptionStoresResponseBody(): void
    {
        $body = ['detail' => 'Something went wrong'];
        $e = new \Chip\Exception\ChipApiException('Server error', 500, $body);

        $this->assertEquals('Server error', $e->getMessage());
        $this->assertEquals(500, $e->getCode());
        $this->assertEquals($body, $e->getResponseBody());
    }

    public function testChipApiExceptionAcceptsNullResponseBody(): void
    {
        $e = new \Chip\Exception\ChipApiException('No body');

        $this->assertNull($e->getResponseBody());
    }

    public function testAuthenticationExceptionExtendsChipApiException(): void
    {
        $e = new \Chip\Exception\AuthenticationException('Invalid API key', 401, ['detail' => 'Invalid API key']);

        $this->assertInstanceOf(\Chip\Exception\ChipApiException::class, $e);
        $this->assertEquals('Invalid API key', $e->getMessage());
        $this->assertEquals(['detail' => 'Invalid API key'], $e->getResponseBody());
    }

    public function testNotFoundExceptionExtendsChipApiException(): void
    {
        $e = new \Chip\Exception\NotFoundException('Not found', 404);

        $this->assertInstanceOf(\Chip\Exception\ChipApiException::class, $e);
        $this->assertEquals('Not found', $e->getMessage());
    }

    public function testValidationExceptionExtractsErrorsFromResponseBody(): void
    {
        $body = ['detail' => 'Validation failed', 'errors' => ['email' => 'Required']];
        $e = new \Chip\Exception\ValidationException('Validation failed', 422, $body);

        $this->assertInstanceOf(\Chip\Exception\ChipApiException::class, $e);
        $this->assertEquals(['email' => 'Required'], $e->getErrors());
        $this->assertEquals($body, $e->getResponseBody());
    }

    public function testValidationExceptionReturnsEmptyErrorsWhenMissing(): void
    {
        $e = new \Chip\Exception\ValidationException('Validation failed', 422, ['detail' => 'Bad request']);

        $this->assertEquals([], $e->getErrors());
    }

    public function testValidationExceptionReturnsEmptyErrorsWhenNullBody(): void
    {
        $e = new \Chip\Exception\ValidationException('Validation failed');

        $this->assertEquals([], $e->getErrors());
    }

    public function testClientExceptionExtendsChipApiException(): void
    {
        $e = new \Chip\Exception\ClientException('Bad request', 400, ['detail' => 'Bad request']);

        $this->assertInstanceOf(\Chip\Exception\ChipApiException::class, $e);
    }

    public function testServerExceptionExtendsChipApiException(): void
    {
        $e = new \Chip\Exception\ServerException('Internal server error', 500, ['detail' => 'Internal server error']);

        $this->assertInstanceOf(\Chip\Exception\ChipApiException::class, $e);
    }

    public function testExceptionPreservesPreviousException(): void
    {
        $previous = new \RuntimeException('Network failure');
        $e = new \Chip\Exception\ServerException('Server error', 500, null, $previous);

        $this->assertSame($previous, $e->getPrevious());
    }
}
