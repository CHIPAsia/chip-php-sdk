<?php

namespace Chip\Traits\Api;

use Chip\Model\ClientDetails as ModelClientDetails;
use Chip\Model\ClientList;

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
			'json' => $client
		]), new ModelClientDetails());
	}

	public function getClients()
	{
		return $this->mapper->map($this->request('GET', 'clients/'), new ClientList());
	}
}
