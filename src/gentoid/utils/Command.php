<?php

namespace gentoid\utils;

class Command {

	/**
	 * @var CommandOption[]
	 */
	protected $options = array();

	/**
	 * @param string $option
	 * @return CommandOption|null
	 */
	public function getOption($option) {
		return (isset($this->options[$option])) ? $this->options[$option] : null;
	}

	/**
	 * @param string $longName
	 * @param string|null $shortName
	 * @param bool $required
	 * @param string $usage
	 */
	public function setExpectedOption($longName, $shortName = null, $required = false, $usage = '') {
		$option = new CommandOption($longName);
		$option->setRequired($required);

		$this->options[$longName] = $option;
		if ($shortName) {
			$option->setShortName($shortName);
			$this->options[$shortName] = $option;
		}
	}

	/**
	 * @param array $argv
	 * @param int $argc
	 * @throws \Exception
	 */
	public function parseOptions(array $argv, $argc) {
		for ($k = 1; $k < $argc; $k = $k + 2) {
			if (!isset($this->options[$argv[$k]]) || !isset($argv[$k + 1])) {
				throw new \Exception('Wrong usage');
			}

			$this->options[$argv[$k]]->setValue($argv[$k + 1]);
		}

		foreach ($this->options as $option) {
			if ($option->getRequired() && !$option->getValue()) {
				throw new \Exception('Miss required option "'.$option->getLongName().'"');
			}
		}
	}

}
