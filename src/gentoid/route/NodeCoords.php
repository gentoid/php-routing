<?php

namespace gentoid\route;


class NodeCoords {

	/** @var \gentoid\route\Coordinate */
	protected $coordinate;

	/** @var \gentoid\route\NodeID */
	protected $nodeId;

	/**
	 * @return \gentoid\route\Coordinate
	 */
	public function getCoordinate() {
		return $this->coordinate;
	}

	/**
	 * @param \gentoid\route\Coordinate $coordinate
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
