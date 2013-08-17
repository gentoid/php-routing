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
	 * @return NodeInfo
	 */
	public function setCoordinate(FixedPointCoordinate $coordinate) {
		$this->coordinate = $coordinate;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getNodeId() {
		return $this->nodeId;
	}

	/**
	 * @param \gentoid\route\NodeID $nodeId
	 * @return NodeInfo
	 */
	public function setNodeId(NodeID $nodeId) {
		$this->nodeId = $nodeId;
		return $this;
	}
}
