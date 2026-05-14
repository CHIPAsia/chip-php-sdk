<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;
use Chip\Model\ClientDetails;
use Chip\Model\ClientList;
use Chip\Model\ClientRecurringToken;
use Chip\Model\ClientRecurringTokenList;

final class ClientsResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    public function create(ClientDetails $client): ClientDetails
    {
        $response = $this->client->request('POST', 'clients/', [
            'json' => $client,
        ]);

        return ClientDetails::fromArray((array) $response);
    }

    public function list(): ClientList
    {
        $response = $this->client->request('GET', 'clients/');

        return ClientList::fromArray((array) $response);
    }

    /**
     * @return \Generator<int, ClientDetails>
     */
    public function iterate(): \Generator
    {
        $url = 'clients/';

        while ($url !== null) {
            $response = $this->client->request('GET', $url);
            $list = ClientList::fromArray((array) $response);

            foreach ($list->results ?? [] as $client) {
                yield $client;
            }

            $url = $list->next;
        }
    }

    public function get(string $clientId): ClientDetails
    {
        $response = $this->client->request('GET', "clients/$clientId/");

        return ClientDetails::fromArray((array) $response);
    }

    public function update(string $clientId, ClientDetails $client): ClientDetails
    {
        $response = $this->client->request('PUT', "clients/$clientId/", [
            'json' => $client,
        ]);

        return ClientDetails::fromArray((array) $response);
    }

    public function partialUpdate(string $clientId, ClientDetails $client): ClientDetails
    {
        $response = $this->client->request('PATCH', "clients/$clientId/", [
            'json' => $client,
        ]);

        return ClientDetails::fromArray((array) $response);
    }

    public function delete(string $clientId): void
    {
        $this->client->request('DELETE', "clients/$clientId/");
    }

    public function listRecurringTokens(string $clientId): ClientRecurringTokenList
    {
        $response = $this->client->request('GET', "clients/$clientId/recurring_tokens/");

        return ClientRecurringTokenList::fromArray((array) $response);
    }

    public function getRecurringToken(string $clientId, string $purchaseId): ClientRecurringToken
    {
        $response = $this->client->request('GET', "clients/$clientId/recurring_tokens/$purchaseId/");

        return ClientRecurringToken::fromArray((array) $response);
    }

    public function deleteRecurringToken(string $clientId, string $purchaseId): void
    {
        $this->client->request('DELETE', "clients/$clientId/recurring_tokens/$purchaseId/");
    }
}
