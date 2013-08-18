<?php
/**
 * @author viktor
 * @date 18.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class InputEdge {

	/** @var NodeID */
	protected $source;

	/** @var NodeId */
	protected $target;

	/** @var NodeBasedEdgeData */
	protected $data;

	public function __construct() {
		$this->source = new NodeID();
		$this->target = new NodeID();
		$this->data   = new NodeBasedEdgeData();
	}

	/**
	 * @param InputEdge $right
	 * @return bool
	 */
	public function isLessThan(InputEdge $right) {
		if (!$this->source->isEqual($right->getSource())) {
			return $this->source->lessThan($right->getSource());
		}
		return $this->target->lessThan($right->getTarget());
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param \gentoid\route\NodeID $source
	 * @return InputEdge
	 */
	public function setSource(NodeID $source) {
		$this->source = $source;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @param \gentoid\route\NodeID $target
	 * @return InputEdge
	 */
	public function setTarget(NodeID $target) {
		$this->target = $target;
		return $this;
	}

	/**
	 * @return \gentoid\route\DataStructures\NodeBasedEdgeData
	 */
	public function getData() {
		return $this->data;
	}

	public function __clone() {
		$this->source->setValue(self::getSource()->getValue());
		$this->target->setValue(self::getTarget()->getValue());
		$this->data = clone self::getData();
	}

} 
