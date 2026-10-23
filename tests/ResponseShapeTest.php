<?php

declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for response shapes that are valid JSON but not JSON objects:
 * string bodies, JSON scalars, and paginated `next` values that are not URLs.
 *
 * Each of these used to surface as a TypeError (strict types plus a typed
 * argument or return type) instead of a ChipApiException or a clean result.
 *
 * The pagination tests seed the mock with exactly one response: any attempt to
 * follow a `next` that is not a real URL fails loudly (second request runs out
 * of queued responses) instead of silently passing.
 */
final class ResponseShapeTest extends TestCase
{
    /**
     * @param array<int, Response> $responses
     */
    private function api(array $responses): \Chip\ChipApi
    {
        return new \Chip\ChipApi('brand', 'key', 'https://gate.chip-in.asia/api/v1/', [
            'handler' => HandlerStack::create(new MockHandler($responses)),
        ]);
    }

    public function testErrorWithArrayDetailBecomesChipApiException(): void
    {
        $api = $this->api([new Response(400, [], '{"detail": ["first", "second"]}')]);

        try {
            $api->purchases->get('p1');
            $this->fail('Expected ClientException');
        } catch (\Chip\Exception\ClientException $e) {
            $this->assertSame('["first","second"]', $e->getMessage());
            $this->assertSame(['detail' => ['first', 'second']], $e->getResponseBody());
        }
    }

    public function testErrorWithObjectDetailBecomesChipApiException(): void
    {
        $api = $this->api([new Response(404, [], '{"detail": {"field": "missing"}}')]);

        try {
            $api->purchases->get('p1');
            $this->fail('Expected NotFoundException');
        } catch (\Chip\Exception\NotFoundException $e) {
            $this->assertSame('{"field":"missing"}', $e->getMessage());
        }
    }

    public function testErrorWithJsonScalarBodyKeepsExceptionTypeAndEmptyBody(): void
    {
        $api = $this->api(array_fill(0, 4, new Response(500, [], '"internal error"')));

        try {
            $api->purchases->get('p1');
            $this->fail('Expected ServerException');
        } catch (\Chip\Exception\ServerException $e) {
            $this->assertSame([], $e->getResponseBody());
            $this->assertStringContainsString('500', $e->getMessage());
        }
    }

    public function testErrorWithPlainTextBodyKeepsExceptionType(): void
    {
        $api = $this->api(array_fill(0, 4, new Response(503, [], '<html>bad gateway</html>')));

        $this->expectException(\Chip\Exception\ServerException::class);

        $api->purchases->get('p1');
    }

    public function testErrorWithoutDetailFallsBackToTransportMessage(): void
    {
        $api = $this->api([new Response(400, [], '{"code": "invalid"}')]);

        try {
            $api->purchases->get('p1');
            $this->fail('Expected ClientException');
        } catch (\Chip\Exception\ClientException $e) {
            $this->assertStringContainsString('400', $e->getMessage());
            $this->assertSame(['code' => 'invalid'], $e->getResponseBody());
        }
    }

    public function testIterateStopsWhenNextIsFalse(): void
    {
        $api = $this->api([new Response(200, [], '{"results": [], "next": false, "previous": null}')]);

        $this->assertSame([], iterator_to_array($api->webhooks->iterate()));
    }

    public function testIterateStopsWhenNextIsEmptyString(): void
    {
        $api = $this->api([new Response(200, [], '{"results": [], "next": "", "previous": null}')]);

        $this->assertSame([], iterator_to_array($api->billing->iterateTemplates()));
    }

    public function testIterateFollowsStringNext(): void
    {
        $api = $this->api([
            new Response(200, [], '{"results": [{"id": "wh_1"}], "next": "webhooks/?page=2", "previous": null}'),
            new Response(200, [], '{"results": [{"id": "wh_2"}], "next": null, "previous": null}'),
        ]);

        $ids = [];
        foreach ($api->webhooks->iterate() as $webhook) {
            $ids[] = $webhook->id;
        }

        $this->assertSame(['wh_1', 'wh_2'], $ids);
    }

    public function testErrorWithEmptyArrayDetailFallsBackToTransportMessage(): void
    {
        $api = $this->api([new Response(400, [], '{"detail": []}')]);

        try {
            $api->purchases->get('p1');
            $this->fail('Expected ClientException');
        } catch (\Chip\Exception\ClientException $e) {
            $this->assertStringContainsString('400', $e->getMessage());
            $this->assertSame(['detail' => []], $e->getResponseBody());
        }
    }

    public function testErrorWithEmptyStringDetailFallsBackToTransportMessage(): void
    {
        $api = $this->api([new Response(400, [], '{"detail": ""}')]);

        try {
            $api->purchases->get('p1');
            $this->fail('Expected ClientException');
        } catch (\Chip\Exception\ClientException $e) {
            $this->assertStringContainsString('400', $e->getMessage());
        }
    }

    public function testErrorUsesMessageFieldWhenDetailMissing(): void
    {
        $api = $this->api([new Response(403, [], '{"message": ["blocked", "by", "rule"]}')]);

        try {
            $api->purchases->get('p1');
            $this->fail('Expected ClientException');
        } catch (\Chip\Exception\ClientException $e) {
            $this->assertSame('["blocked","by","rule"]', $e->getMessage());
        }
    }

    public function testStatementsIterateStopsOnNonStringNext(): void
    {
        $api = $this->api([new Response(200, [], '{"results": [], "next": false, "previous": null}')]);

        $this->assertSame([], iterator_to_array($api->statements->iterate()));
    }

    public function testBillingClientsIterateStopsOnEmptyNext(): void
    {
        $api = $this->api([new Response(200, [], '{"results": [], "next": "", "previous": null}')]);

        $this->assertSame([], iterator_to_array($api->billing->iterateClients('bt_1')));
    }

    public function testClientsIterateStopsOnNonStringNext(): void
    {
        $api = $this->api([new Response(200, [], '{"results": [], "next": false, "previous": null}')]);

        $this->assertSame([], iterator_to_array($api->clients->iterate()));
    }

    public function testListSkipsNonArrayResults(): void
    {
        $api = $this->api([new Response(200, [], '{"results": ["x", {"id": "wh_1"}], "next": null, "previous": null}')]);

        $list = $api->webhooks->list();

        $this->assertIsArray($list->results);
        $this->assertCount(1, $list->results);
        $this->assertSame('wh_1', $list->results[0]->id);
    }

    public function testBillingTemplateListSkipsNonArrayResults(): void
    {
        $api = $this->api([new Response(200, [], '{"results": ["x"], "next": null, "previous": null}')]);

        $list = $api->billing->listTemplates();

        $this->assertSame([], $list->results);
    }

    public function testBalanceReturnsEmptyArrayForNonObjectBody(): void
    {
        $api = $this->api([new Response(200, [], '"not-an-object"')]);

        $this->assertSame([], $api->account->balance());
    }

    public function testTurnoverReturnsEmptyArrayForNonObjectBody(): void
    {
        $api = $this->api([new Response(200, [], '"not-an-object"')]);

        $this->assertSame([], $api->account->turnover());
    }
}
