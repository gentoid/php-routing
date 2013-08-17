<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class PhantomNode {

	/** @var \gentoid\route\NodeID */
	protected $edgeBasedNode;

	/** @var NodeID */
	protected $nodeBasedEdgeNameID;

	/** @var int */
	protected $weight1 = PHP_INT_MAX;

	/** @var int */
	protected $weight2 = PHP_INT_MAX;

	/** @var float */
	protected $ratio = 0.0;

	/** @var \gentoid\route\DataStructures\FixedPointCoordinate */
	protected $location;

	public function __construct() {
		$this->edgeBasedNode = new NodeID();
		$this->nodeBasedEdgeNameID = new NodeID();
		$this->location = new FixedPointCoordinate();
	}

	/**
	 * @return void
	 */
	public function reset() {
		$this->edgeBasedNode->setValue(NodeID::DEFAULT_VALUE);
		$this->nodeBasedEdgeNameID = PHP_INT_MAX;
		$this->weight1 = PHP_INT_MAX;
		$this->weight2 = PHP_INT_MAX;
		$this->ratio = 0.0;
		$this->location->reset();
	}

	/**
	 * @return bool
	 */
	public function isBiderected() {
		return $this->weight2 != PHP_INT_MAX;
	}

	/**
	 * @param int $numberOfNodes
	 * @return bool
	 */
	public function isValid($numberOfNodes) {
		return $this->location->isValid()
		&& $this->edgeBasedNode->lessThanInt($numberOfNodes)
		&& $this->weight1 != PHP_INT_MAX
		&& $this->ratio >= 0.0
		&& $this->ratio <= 1.0
		&& $this->nodeBasedEdgeNameID->getValue() != NodeID::DEFAULT_VALUE;
	}

	/**
	 * @param PhantomNode $n
	 * @return bool
	 */
	public function isEqual(PhantomNode $n) {
		return $this->location->isEqual($n->getLocation());
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getEdgeBasedNode() {
		return $this->edgeBasedNode;
	}

	/**
	 * @param \gentoid\route\NodeID $edgeBasedNode
	 * @return PhantomNode
	 */
	public function setEdgeBasedNode($edgeBasedNode) {
		$this->edgeBasedNode = $edgeBasedNode;
		return $this;
	}

	/**
	 * @return NodeID
	 */
	public function getNodeBasedEdgeNameID() {
		return $this->nodeBasedEdgeNameID;
	}

	/**
	 * @param NodeID $nodeBasedEdgeNameID
	 * @return PhantomNode
	 */
	public function setNodeBasedEdgeNameID($nodeBasedEdgeNameID) {
		$this->nodeBasedEdgeNameID = $nodeBasedEdgeNameID;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWeight1() {
		return $this->weight1;
	}

	/**
	 * @param int $weight1
	 * @return PhantomNode
	 */
	public function setWeight1($weight1) {
		$this->weight1 = intval($weight1);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWeight2() {
		return $this->weight2;
	}

	/**
	 * @param int $weight2
	 * @return PhantomNode
	 */
	public function setWeight2($weight2) {
		$this->weight2 = intval($weight2);
		return $this;
	}

	/**
	 * @return float
	 */
	public function getRatio() {
		return $this->ratio;
	}

	/**
	 * @param float $ratio
	 * @return PhantomNode
	 */
	public function setRatio($ratio) {
		$this->ratio = floatval($ratio);
		return $this;
	}

	/**
	 * @return \gentoid\route\DataStructures\FixedPointCoordinate
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @param \gentoid\route\DataStructures\FixedPointCoordinate $location
	 * @return PhantomNode
	 */
	public function setLocation($location) {
		$this->location = $location;
		return $this;
	}

}
