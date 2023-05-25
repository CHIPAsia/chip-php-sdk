<?php
namespace Chip\Model;

class BillingTemplateClients implements \JsonSerializable {

	/**
	 *
	 * @var BillingTemplateClient[]
	 */
	public $results;

  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}

}
