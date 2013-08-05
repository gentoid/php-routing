<?php

namespace gentoid\route;

class Node {

	private $bollard = false;
	private $trafficLight = false;

	/** @var int */
	private $id = 0;

	/** @var array */
	protected $attributes = array();

	/**
	 * @param $id
	 */
	public function __construct($id) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return boolean
	 */
	public function getBollard() {
		return $this->bollard;
	}

	/**
	 * @return boolean
	 */
	public function getTrafficLight() {
		return $this->trafficLight;
	}

}
