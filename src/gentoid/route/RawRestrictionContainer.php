<?php

namespace gentoid\route;




class RawRestrictionContainer {

	const DEFAULT_VALUE = -1;

	/**@var \gentoid\route\TurnRestriction */
	protected $restriction;

	/** @var int */
	protected $fromWay = RawRestrictionContainer::DEFAULT_VALUE;

	/** @var int */
	protected $toWay = RawRestrictionContainer::DEFAULT_VALUE;

	/** @var \gentoid\route\NodeID */
	protected $viaNode;

	public function __construct() {
		$this->restriction = new TurnRestriction();
		$this->viaNode = new NodeID();
	}

	/**
	 * @param RawRestrictionContainer $a
	 * @param RawRestrictionContainer $b
	 * @return int
	 */
	public static function CmpRestrictionContainerByFrom(RawRestrictionContainer $a, RawRestrictionContainer $b) {
		return ($a->getFromWay() < $b->getFromWay()) ? -1 : 1;
	}

	/**
	 * @param RawRestrictionContainer $a
	 * @param RawRestrictionContainer $b
	 * @return int
	 */
	public static function CmpRestrictionContainerByTo(RawRestrictionContainer $a, RawRestrictionContainer $b) {
		return ($a->getToWay() < $b->getToWay()) ? -1 : 1;
	}

	/**
	 * @return \gentoid\route\TurnRestriction
	 */
	public function getRestriction() {
		return $this->restriction;
	}

	/**
	 * @param \gentoid\route\TurnRestriction $restriction
	 * @return \gentoid\route\RawRestrictionContainer
	 */
	public function setRestriction(\gentoid\route\TurnRestriction $restriction) {
		$this->restriction = $restriction;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFromWay() {
		return $this->fromWay;
	}

	/**
	 * @param int $fromWay
	 * @return \gentoid\route\RawRestrictionContainer
	 */
	public function setFromWay($fromWay) {
		$this->fromWay = intval($fromWay);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getToWay() {
		return $this->toWay;
	}

	/**
	 * @param int $toWay
	 * @return \gentoid\route\RawRestrictionContainer
	 */
	public function setToWay($toWay) {
		$this->toWay = intval($toWay);
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
	 * @return \gentoid\route\RawRestrictionContainer
	 */
	public function setViaNode(\gentoid\route\NodeID $viaNode) {
		$this->viaNode = $viaNode;
		return $this;
	}

}
