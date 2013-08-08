<?php

namespace gentoid\route;


use gentoid\route\DataStructures\Coordinate;

class NodeCoords {

	/** @var \gentoid\route\DataStructures\Coordinate */
	protected $coordinate;

	/** @var \gentoid\route\NodeID */
	protected $nodeId;

	public function __construct() {
		$this->coordinate = new Coordinate();
		$this->nodeId = new NodeID();
	}

	/**
	 * @return \gentoid\route\DataStructures\Coordinate
	 */
	public function getCoordinate() {
		return $this->coordinate;
	}

	/**
	 * @param \gentoid\route\DataStructures\Coordinate $coordinate
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
