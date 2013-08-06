<?php

namespace gentoid\route;




class RawRestrictionContainer {

	/**@var \gentoid\route\Restriction */
	protected $restriction;

	/** @var int */
	protected $fromWay;

	/** @var int */
	protected $toWay;

	/** @var \gentoid\route\NodeID */
	protected $viaNode;

	/**
	 * @param RawRestrictionContainer $a
	 * @param RawRestrictionContainer $b
	 * @return bool
	 */
	public static function CmpRestrictionContainerByFrom(RawRestrictionContainer $a, RawRestrictionContainer $b) {
		return $a->getFromWay() < $b->getFromWay();
	}

	/**
	 * @param RawRestrictionContainer $a
	 * @param RawRestrictionContainer $b
	 * @return bool
	 */
	public static function CmpRestrictionContainerByTo(RawRestrictionContainer $a, RawRestrictionContainer $b) {
		return $a->getToWay() < $b->getToWay();
	}

	/**
	 * @return \gentoid\route\Restriction
	 */
	public function getRestriction() {
		return $this->restriction;
	}

	/**
	 * @param \gentoid\route\Restriction $restriction
	 * @return \gentoid\route\RawRestrictionContainer
	 */
	public function setRestriction($restriction) {
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
	public function setViaNode($viaNode) {
		$this->viaNode = $viaNode;
		return $this;
	}

}
