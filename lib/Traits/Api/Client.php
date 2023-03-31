<?php

namespace Chip\Traits\Api;

use Chip\Model\ClientDetails as ModelClientDetails;

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
}