<?php

namespace gentoid\route;

class Node {

	private $bollard = false;
	private $trafficLight = false;
	private $id = 0;

	/**
	 * @param \SimpleXMLElement $node
	 * @throws \Exception
	 */
	public function __construct(\SimpleXMLElement $node) {
		if (isset($node['highway']) && $node['highway'] == 'traffic_signals') {
			$this->trafficLight = true;
		}

		$registry = Registry::getInstance();

		foreach ($node->attributes() as $attr => $val) {
			if ((in_array($attr, $registry->getAccessTagsHierarchy()) && in_array($val, $registry->getAccessTagsBlackList()))
			|| ($attr === 'barrier' && !in_array($val, $registry->getBarrierWhiteList()))) {
				$this->bollard = true;
			}
		}

		if (isset($node['id'])) {
			$this->id = (int)$node['id'];
		}
		else {
			throw new \Exception('Node has no ID attribute');
		}
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
