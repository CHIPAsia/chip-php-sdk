<?php

namespace Chip\Traits\Api;

use Chip\Model\Webhook as ModelWebHook;

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
}