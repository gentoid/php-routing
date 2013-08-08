<?php

namespace gentoid\route;


class ImportNode extends Node {

	/** @var array */
	protected $keyVals = array();

	public function clear() {
		$this->coordinate->reset();
		$this->nodeId->setValue('0');
		$this->bollard = false;
		$this->trafficLight = false;
	}

	/**
	 * @return array
	 */
	public function getKeyVals() {
		return $this->keyVals;
	}

	/**
	 * @param $key
	 * @param $val
	 */
	public function addKeyVal($key, $val) {
		$this->keyVals[$key] = $val;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function findValByKey($key) {
		return (isset($this->keyVals->{$key})) ? $this->keyVals->{$key} : null;
	}

}
