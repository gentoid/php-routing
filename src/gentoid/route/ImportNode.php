<?php

namespace gentoid\route;


class ImportNode extends Node {
	use Tag;

	/** @var array */
	protected $attributes = array();

	/** @var array */
	protected $keyVals = array();

	public function clear() {
		$this->coordinate->reset();
		$this->nodeId->setValue('0');
		$this->bollard = false;
		$this->trafficLight = false;
	}

	/**
	 * @param string $attr
	 * @return mixed
	 */
	public function findAttribute($attr) {
		if (isset($this->attributes[$attr])) {
			return $this->attributes[$attr];
		}
		return null;
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

}
