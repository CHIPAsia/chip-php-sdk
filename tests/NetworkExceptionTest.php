<?php

declare(strict_types=1);

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * A failure that never produced an HTTP response (DNS, connection refused, TLS,
 * timeout) used to escape as a raw Guzzle exception, so `catch (ChipApiException)`
 * never fired. It must now be a NetworkException.
 */
final class NetworkExceptionTest extends TestCase
{
    private const URI = 'https://gate.chip-in.asia/api/v1/purchases/x/';

    /**
     * @param array<int, Response|\Throwable> $queue
     * @return array{0: \Chip\ChipApi, 1: object{count: int}}
     */
    private function api(array $queue): array
    {
        $counter = new class () {
            public int $count = 0;
        };

        $stack = HandlerStack::create(new MockHandler($queue));
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($counter): RequestInterface {
            ++$counter->count;

            return $request;
        }));

        return [
            new \Chip\ChipApi('brand', 'key', 'https://gate.chip-in.asia/api/v1/', ['handler' => $stack]),
            $counter,
        ];
    }

    public function testConnectFailureBecomesCatchableChipApiException(): void
    {
        $transport = new ConnectException('Connection timed out', new Request('GET', self::URI));
        [$api] = $this->api([$transport]);

        try {
            $api->purchases->get('x');
            $this->fail('Expected NetworkException');
        } catch (\Chip\Exception\NetworkException $e) {
            $this->assertInstanceOf(\Chip\Exception\ChipApiException::class, $e);
            $this->assertStringContainsString('Connection timed out', $e->getMessage());
            $this->assertSame(0, $e->getCode(), 'a network failure has no HTTP status');
            $this->assertNull($e->getResponseBody());
            $this->assertSame($transport, $e->getPrevious(), 'the transport exception stays available');
        }
    }

    public function testNetworkFailureIsNotRetried(): void
    {
        $transport = new ConnectException('Connection refused', new Request('GET', self::URI));
        [$api, $counter] = $this->api([$transport]);

        try {
            $api->purchases->get('x');
            $this->fail('Expected NetworkException');
        } catch (\Chip\Exception\NetworkException $e) {
            $this->assertSame(1, $counter->count, 'network failures fail fast — retrying a POST is not safe');
        }
    }

    public function testHttpErrorsStillMapToTheirOwnExceptions(): void
    {
        [$api] = $this->api([new Response(404, [], '{"detail": "Purchase not found"}')]);

        $this->expectException(\Chip\Exception\NotFoundException::class);

        $api->purchases->get('x');
    }
}
