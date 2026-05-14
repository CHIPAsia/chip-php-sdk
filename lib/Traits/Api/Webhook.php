<?php

namespace Chip\Traits\Api;

use Chip\Model\Webhook as ModelWebHook;
use Chip\Model\WebhookList;

trait Webhook
{
    /**
     *
     * @param \Chip\Model\Webhook $webhook
     * @return \Chip\Model\Webhook
     */
    public function createWebhook(ModelWebHook $webhook): ModelWebHook
    {
        return $this->mapper->map($this->request('POST', 'webhooks/', [
            'json' => $webhook
        ]), new ModelWebHook());
    }

    /**
     *
     * @param string $webhookId
     * @return \Chip\Model\Webhook
     */
    public function getWebhook(string $webhookId): ModelWebHook
    {
        return $this->mapper->map($this->request('GET', "webhooks/$webhookId/"), new ModelWebHook());
    }

    /**
     *
     * @param string $webhookId
     * @return mixed
     */
    public function deleteWebhook(string $webhookId): mixed
    {
        return $this->request('DELETE', "webhooks/$webhookId/");
    }

    /** @return WebhookList */
    public function listWebhooks()
    {
        return $this->mapper->map($this->request('GET', 'webhooks/'), new WebhookList());
    }

    /** @return ModelWebHook */
    public function updateWebhook(string $webhookId, ModelWebHook $webhook)
    {
        return $this->mapper->map($this->request('PUT', "webhooks/$webhookId/", [
            'json' => $webhook,
        ]), new ModelWebHook());
    }

    /** @return ModelWebHook */
    public function partialUpdateWebhook(string $webhookId, ModelWebHook $webhook)
    {
        return $this->mapper->map($this->request('PATCH', "webhooks/$webhookId/", [
            'json' => $webhook,
        ]), new ModelWebHook());
    }
}
