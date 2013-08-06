<?php
/**
 * @date: 8/6/13
 * @author: viktor
 */

namespace gentoid\route;


class Direction {

	const NOT_SURE      = 0;
	const ONEWAY        = 1;
	const BIDIRECTIONAL = 2;
	const OPPOSITE      = 3;

	/**
	 * @var int
	 */
	protected $value;

	public function __construct($direction = Direction::NOT_SURE) {
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
		if (!is_int($value) || $value < Direction::NOT_SURE || $value > Direction::OPPOSITE) {
			throw new \Exception('Wrong direction '.$value);
		}
	}

}
 