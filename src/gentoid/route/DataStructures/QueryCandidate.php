<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class QueryCandidate {

	/** @var NodeID */
	protected $nodeId;

	/** @var float */
	protected $minDist = PHP_INT_MAX;

	public function __construct() {
		$this->nodeId = new NodeID();
	}

	public function isLessThan(QueryCandidate $qc) {
		return $this->minDist < $qc->getMinDist();
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getNodeId() {
		return $this->nodeId;
	}

	/**
	 * @param \gentoid\route\NodeID $nodeId
	 * @return QueryCandidate
	 */
	public function setNodeId($nodeId) {
		$this->nodeId = $nodeId;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getMinDist() {
		return $this->minDist;
	}

	/**
	 * @param float $minDist
	 * @return QueryCandidate
	 */
	public function setMinDist($minDist) {
		$this->minDist = floatval($minDist);
		return $this;
	}

}
