<?php

namespace gentoid\route;


class BigInt {

	/** @var string */
	protected $value;

	/**
	 * @param string $value
	 */
	public function __construct($value = '0') {
		$this->setValue($value);
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 * @throws \Exception
	 */
	public function setValue($value) {
		$value = trim($value);
		if (!preg_match('/^(-)?\d+$/', $value)) {
			throw new \Exception($value.' is not an integer');
		}
		$this->value = $value;
	}

} 