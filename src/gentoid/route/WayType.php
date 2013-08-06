<?php
/**
 * @date: 8/6/13
 * @author: viktor
 */

namespace gentoid\route;


class WayType {

	const UNKNOWN = 0;
	const FERRY = 1;
	const TURN_RESTRICTION = 2;

	/**
	 * @var int
	 */
	protected $value;

	public function __construct($direction = WayType::UNKNOWN) {
		$this->setValue($direction);
	}

	/**
	 * @return int
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param int $value
	 * @throws \Exception
	 */
	public function setValue($value) {
		if (!is_int($value) || $value < WayType::UNKNOWN || $value > WayType::TURN_RESTRICTION) {
			throw new \Exception('Wrong direction '.$value);
		}
	}

}
