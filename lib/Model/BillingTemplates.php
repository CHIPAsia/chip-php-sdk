<?php
namespace Chip\Model;

class BillingTemplates implements \JsonSerializable {

	/**
	 *
	 * @var BillingTemplate[]
	 */
	public $results;

  #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}

}
