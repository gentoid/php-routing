<?php

namespace gentoid\route;


class ImportNode extends Node {
	use Tag;

	/** @var array */
	protected $attributes = array();

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

}
