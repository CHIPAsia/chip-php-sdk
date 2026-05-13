<?php

namespace Chip\Traits\Api;

use Chip\Model\ClientDetails as ModelClientDetails;
use Chip\Model\ClientList;
use Chip\Model\ClientRecurringToken;
use Chip\Model\ClientRecurringTokenList;

trait Client
{
    /**
     *
     * @param \Chip\Model\ClientDetails $client
     * @return \Chip\Model\ClientDetails
     */
    public function createClient(ModelClientDetails $client): ModelClientDetails
    {
        return $this->mapper->map($this->request('POST', 'clients/', [
            'json' => $client,
        ]), new ModelClientDetails());
    }

    /** @return ClientList */
    public function getClients()
    {
        return $this->mapper->map($this->request('GET', 'clients/'), new ClientList());
    }

    /** @return ModelClientDetails */
    public function getClient(string $clientId)
    {
        return $this->mapper->map($this->request('GET', "clients/$clientId/"), new ModelClientDetails());
    }

    /** @return ModelClientDetails */
    public function updateClient(string $clientId, ModelClientDetails $client)
    {
        return $this->mapper->map($this->request('PUT', "clients/$clientId/", [
            'json' => $client,
        ]), new ModelClientDetails());
    }

    /** @return ModelClientDetails */
    public function partialUpdateClient(string $clientId, ModelClientDetails $client)
    {
        return $this->mapper->map($this->request('PATCH', "clients/$clientId/", [
            'json' => $client,
        ]), new ModelClientDetails());
    }

    /** @return void */
    public function deleteClient(string $clientId): void
    {
        $this->request('DELETE', "clients/$clientId/");
    }

    /** @return ClientRecurringTokenList */
    public function listRecurringTokens(string $clientId)
    {
        return $this->mapper->map($this->request('GET', "clients/$clientId/recurring_tokens/"), new ClientRecurringTokenList());
    }

    /** @return ClientRecurringToken */
    public function getRecurringToken(string $clientId, string $purchaseId)
    {
        return $this->mapper->map($this->request('GET', "clients/$clientId/recurring_tokens/$purchaseId/"), new ClientRecurringToken());
    }

    /** @return void */
    public function deleteRecurringTokenByClient(string $clientId, string $purchaseId): void
    {
        $this->request('DELETE', "clients/$clientId/recurring_tokens/$purchaseId/");
    }
}
