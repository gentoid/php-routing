<?php

namespace gentoid\utils;

class CommandOption {

	protected $longName;
	protected $shortName;
	protected $value;
	protected $required = false;

	/**
	 * @param $name
	 * @throws \Exception
	 */
	public function __construct($name) {
		if (!preg_match('/^\w{2,20}$/', $name)) {
			throw new \Exception('Wrong option name');
		}

		$this->longName = $name;
	}

	/**
	 * @param $shortName
	 * @return CommandOption
	 * @throws \Exception
	 */
	public function setShortName($shortName) {
		if (!preg_match('/^\w$/', $shortName)) {
			throw new \Exception('Wrong short option name');
		}
		$this->shortName = $shortName;
		return $this;
	}

	/**
	 * @param $required
	 * @return self
	 */
	public function setRequired($required) {
		$this->required = (bool)$required;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLongName() {
		return $this->longName;
	}

	/**
	 * @return string
	 */
	public function getShortName() {
		return $this->shortName;
	}

	/**
	 * @return boolean
	 */
	public function getRequired() {
		return $this->required;
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
		if (preg_match('/^-.*$/', $value)) {
			throw new \Exception('Wrong option value');
		}
		$this->value = $value;
	}
}
