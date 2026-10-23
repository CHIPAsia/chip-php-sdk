<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;
use Chip\Model\Billing\BillingTemplate;
use Chip\Model\Billing\BillingTemplateClient;
use Chip\Model\Billing\BillingTemplateClientAddSubscriber;
use Chip\Model\Billing\BillingTemplateClientList;
use Chip\Model\Billing\BillingTemplateList;
use Chip\Model\Purchase;

final class BillingResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    /**
     * @return array<string, mixed>|\stdClass|string
     */
    public function create(BillingTemplate $billing): array|\stdClass|string
    {
        return $this->client->request('POST', 'billing/', [
            'json' => $billing,
        ]);
    }

    public function createTemplate(BillingTemplate $billing): BillingTemplate
    {
        $response = $this->client->request('POST', 'billing_templates/', [
            'json' => $billing,
        ]);

        return BillingTemplate::fromArray((array) $response);
    }

    public function listTemplates(): BillingTemplateList
    {
        $response = $this->client->request('GET', 'billing_templates/');

        return BillingTemplateList::fromArray((array) $response);
    }

    /**
     * @return \Generator<int, BillingTemplate>
     */
    public function iterateTemplates(): \Generator
    {
        $url = 'billing_templates/';

        while ($url !== null) {
            $response = $this->client->request('GET', $url);
            $list = BillingTemplateList::fromArray((array) $response);

            foreach ($list->results ?? [] as $template) {
                yield $template;
            }

            // `next` is string|null per the API, but `false`/"" show up too:
            // a non-string is a TypeError and an empty string loops forever.
            $next = $list->next;
            $url = is_string($next) && '' !== $next ? $next : null;
        }
    }

    public function getTemplate(string $billingId): BillingTemplate
    {
        $response = $this->client->request('GET', "billing_templates/$billingId/");

        return BillingTemplate::fromArray((array) $response);
    }

    public function updateTemplate(string $billingId, BillingTemplate $billing): BillingTemplate
    {
        $response = $this->client->request('PUT', "billing_templates/$billingId/", [
            'json' => $billing,
        ]);

        return BillingTemplate::fromArray((array) $response);
    }

    public function deleteTemplate(string $billingId): void
    {
        $this->client->request('DELETE', "billing_templates/$billingId/");
    }

    public function sendInvoice(string $billingId, BillingTemplateClient $client): Purchase
    {
        $response = $this->client->request('POST', "billing_templates/$billingId/send_invoice/", [
            'json' => $client,
        ]);

        return Purchase::fromArray((array) $response);
    }

    public function addSubscriber(string $billingId, BillingTemplateClient $client): BillingTemplateClientAddSubscriber
    {
        $response = $this->client->request('POST', "billing_templates/$billingId/add_subscriber/", [
            'json' => $client,
        ]);

        return BillingTemplateClientAddSubscriber::fromArray((array) $response);
    }

    public function listClients(string $billingId): BillingTemplateClientList
    {
        $response = $this->client->request('GET', "billing_templates/$billingId/clients/");

        return BillingTemplateClientList::fromArray((array) $response);
    }

    /**
     * @return \Generator<int, BillingTemplateClient>
     */
    public function iterateClients(string $billingId): \Generator
    {
        $url = "billing_templates/$billingId/clients/";

        while ($url !== null) {
            $response = $this->client->request('GET', $url);
            $list = BillingTemplateClientList::fromArray((array) $response);

            foreach ($list->results ?? [] as $client) {
                yield $client;
            }

            // `next` is string|null per the API, but `false`/"" show up too:
            // a non-string is a TypeError and an empty string loops forever.
            $next = $list->next;
            $url = is_string($next) && '' !== $next ? $next : null;
        }
    }

    public function getClient(string $billingId, string $clientId): BillingTemplateClient
    {
        $response = $this->client->request('GET', "billing_templates/$billingId/clients/$clientId/");

        return BillingTemplateClient::fromArray((array) $response);
    }

    public function updateClient(string $billingId, string $clientId, BillingTemplateClient $client): BillingTemplateClient
    {
        $response = $this->client->request('PATCH', "billing_templates/$billingId/clients/$clientId/", [
            'json' => $client,
        ]);

        return BillingTemplateClient::fromArray((array) $response);
    }
}
