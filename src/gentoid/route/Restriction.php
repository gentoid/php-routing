<?php

namespace gentoid\route;


class Restriction {

	/** @var \gentoid\route\NodeID */
	protected $viaNode;

	/** @var \gentoid\route\NodeID */
	protected $fromNode;

	/** @var \gentoid\route\NodeID */
	protected $toNode;

	/** @var bool */
	protected $isOnly = false;

	/**
	 * @param Restriction $a
	 * @param Restriction $b
	 * @return bool
	 */
	public static function CmpRestrictionByFrom(Restriction $a, Restriction $b) {
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
	 * @return \gentoid\route\Restriction
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
	 * @return \gentoid\route\Restriction
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
	 * @return \gentoid\route\Restriction
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
	 * @return \gentoid\route\Restriction
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
