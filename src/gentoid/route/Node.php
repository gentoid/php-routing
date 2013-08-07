<?php

namespace gentoid\route;

class Node extends NodeCoords {

	/** @var bool */
	protected $bollard = false;

	/** @var bool */
	protected $trafficLight = false;

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

	/**
	 * @param boolean $bollard
	 * @return \gentoid\route\Node
	 */
	public function setBollard($bollard) {
		$this->bollard = (bool)$bollard;
		return $this;
	}

	/**
	 * @param boolean $trafficLight
	 * @return \gentoid\route\Node
	 */
	public function setTrafficLight($trafficLight) {
		$this->trafficLight = (bool)$trafficLight;
		return $this;
	}

	/**
	 * @param Node $a
	 * @param Node $b
	 * @return int
	 */
	public static function CmpNodeByID(Node $a, Node $b) {
		return bccomp($a->getNodeId()->getValue(), $b->getNodeId()->getValue());
	}

}
