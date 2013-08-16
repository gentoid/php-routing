<?php

namespace gentoid\route;


class TurnRestriction {

	/** @var \gentoid\route\NodeID */
	protected $viaNode;

	/** @var \gentoid\route\NodeID */
	protected $fromNode;

	/** @var \gentoid\route\NodeID */
	protected $toNode;

	/** @var bool */
	protected $isOnly = false;

	public function __construct() {
		$this->viaNode  = new NodeID();
		$this->fromNode = new NodeID();
		$this->toNode   = new NodeID();
	}

	/**
	 * @param TurnRestriction $a
	 * @param TurnRestriction $b
	 * @return bool
	 */
	public static function CmpRestrictionByFrom(TurnRestriction $a, TurnRestriction $b) {
		return bccomp($a->getFromNode()->getValue(), $b->getFromNode()->getValue()) === -1;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getFromNode() {
		return $this->fromNode;
	}

	/**
	 * @param \gentoid\route\NodeID $fromNode
	 * @return \gentoid\route\TurnRestriction
	 */
	public function setFromNode(\gentoid\route\NodeID $fromNode) {
		$this->fromNode = $fromNode;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getViaNode() {
		return $this->viaNode;
	}

	/**
	 * @param \gentoid\route\NodeID $viaNode
	 * @return \gentoid\route\TurnRestriction
	 */
	public function setViaNode(\gentoid\route\NodeID $viaNode) {
		$this->viaNode = $viaNode;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getToNode() {
		return $this->toNode;
	}

	/**
	 * @param \gentoid\route\NodeID $toNode
	 * @return \gentoid\route\TurnRestriction
	 */
	public function setToNode(\gentoid\route\NodeID $toNode) {
		$this->toNode = $toNode;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsOnly() {
		return $this->isOnly;
	}

	/**
	 * @param boolean $isOnly
	 * @return \gentoid\route\TurnRestriction
	 */
	public function setIsOnly($isOnly) {
		$this->isOnly = (bool)$isOnly;
		return $this;
	}

	/**
	 * @return string
	 */
	public function pack() {
		return $this->viaNode->pack() . $this->fromNode->pack() . $this->toNode->pack() . pack('S', $this->isOnly);
	}

}
