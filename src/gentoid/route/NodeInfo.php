<?php

namespace gentoid\route;


use gentoid\route\DataStructures\FixedPointCoordinate;

class NodeInfo {

	/** @var \gentoid\route\DataStructures\FixedPointCoordinate */
	protected $coordinate;

	/** @var \gentoid\route\NodeID */
	protected $nodeId;

	public function __construct() {
		$this->coordinate = new FixedPointCoordinate();
		$this->nodeId = new NodeID();
	}

	/**
	 * @return \gentoid\route\DataStructures\FixedPointCoordinate
	 */
	public function getCoordinate() {
		return $this->coordinate;
	}

	/**
	 * @param \gentoid\route\DataStructures\FixedPointCoordinate $coordinate
	 */
	public function setCoordinate($coordinate) {
		$this->coordinate = $coordinate;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getNodeId() {
		return $this->nodeId;
	}

	/**
	 * @param \gentoid\route\NodeID $nodeId
	 */
	public function setNodeId($nodeId) {
		$this->nodeId = $nodeId;
	}
}
