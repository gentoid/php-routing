<?php

namespace gentoid\route;


class ImportNode extends Node {

	/** @var array */
	protected $attributes = array();

	public function clear() {
		$this->coordinate->reset();
		$this->nodeId->setValue('0');
		$this->bollard = false;
		$this->trafficLight = false;
	}

}
