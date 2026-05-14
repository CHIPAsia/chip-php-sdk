<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;
use Chip\Model\Webhook;
use Chip\Model\WebhookList;

final class WebhooksResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    public function create(Webhook $webhook): Webhook
    {
        $response = $this->client->request('POST', 'webhooks/', [
            'json' => $webhook,
        ]);

        return Webhook::fromArray((array) $response);
    }

    public function list(): WebhookList
    {
        $response = $this->client->request('GET', 'webhooks/');

        return WebhookList::fromArray((array) $response);
    }

    /**
     * @return \Generator<int, Webhook>
     */
    public function iterate(): \Generator
    {
        $url = 'webhooks/';

        while ($url !== null) {
            $response = $this->client->request('GET', $url);
            $list = WebhookList::fromArray((array) $response);

            foreach ($list->results ?? [] as $webhook) {
                yield $webhook;
            }

            $url = $list->next;
        }
    }

    public function get(string $webhookId): Webhook
    {
        $response = $this->client->request('GET', "webhooks/$webhookId/");

        return Webhook::fromArray((array) $response);
    }

    public function update(string $webhookId, Webhook $webhook): Webhook
    {
        $response = $this->client->request('PUT', "webhooks/$webhookId/", [
            'json' => $webhook,
        ]);

        return Webhook::fromArray((array) $response);
    }

    public function partialUpdate(string $webhookId, Webhook $webhook): Webhook
    {
        $response = $this->client->request('PATCH', "webhooks/$webhookId/", [
            'json' => $webhook,
        ]);

        return Webhook::fromArray((array) $response);
    }

    public function delete(string $webhookId): void
    {
        $this->client->request('DELETE', "webhooks/$webhookId/");
    }
}
