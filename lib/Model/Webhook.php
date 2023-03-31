<?php
namespace Chip\Model;

class Webhook implements \JsonSerializable
{
	/**
	 *
	 * @var string
	 */
	public $type;

	/**
	 *
	 * @var string
	 */
	public $id;

	/**
	 *
	 * @var int
	 */
	public $created_on;

	/**
	 *
	 * @var int
	 */
	public $updated_on;

	/**
	 *
	 * @var string
	 */
	public $title;

	/**
	 *
	 * @var bool
	 */
	public $all_events;

	/**
	 *
	 * @var string
	 */
	public $public_key;

	/**
	 *
	 * @var string[]
	 */
	public $events;

	/**
	 *
	 * @var string
	 */
	public $callback;

	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return array_filter((array) $this);
	}
}

