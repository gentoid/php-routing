<?php
/**
 * @date: 8/6/13
 * @author: viktor
 */

namespace gentoid\route;


class WayIDStartAndEndEdge {

	/** @var int */
	protected $wayId = -1;

	/** @var NodeID */
	protected $firstStart;

	/** @var NodeID */
	protected $firstTarget;

	/** @var NodeID */
	protected $lastStart;

	/** @var NodeID */
	protected $lastTarget;

	public function __construct() {
		$this->firstStart = new NodeID('-1');
		$this->firstTarget = new NodeID('-1');
		$this->lastStart = new NodeID('-1');
		$this->lastTarget = new NodeID('-1');
	}

	/**
	 * @return int
	 */
	public function getWayId() {
		return $this->wayId;
	}

	/**
	 * @param int $wayId
	 * @return WayIDStartAndEndEdge
	 */
	public function setWayId($wayId) {
		$this->wayId = intval($wayId);
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getFirstStart() {
		return $this->firstStart;
	}

	/**
	 * @param \gentoid\route\NodeID $firstStart
	 * @return WayIDStartAndEndEdge
	 */
	public function setFirstStart($firstStart) {
		$this->firstStart = $firstStart;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getFirstTarget() {
		return $this->firstTarget;
	}

	/**
	 * @param \gentoid\route\NodeID $firstTarget
	 * @return WayIDStartAndEndEdge
	 */
	public function setFirstTarget($firstTarget) {
		$this->firstTarget = $firstTarget;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getLastStart() {
		return $this->lastStart;
	}

	/**
	 * @param \gentoid\route\NodeID $lastStart
	 * @return WayIDStartAndEndEdge
	 */
	public function setLastStart($lastStart) {
		$this->lastStart = $lastStart;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getLastTarget() {
		return $this->lastTarget;
	}

	/**
	 * @param \gentoid\route\NodeID $lastTarget
	 * @return WayIDStartAndEndEdge
	 */
	public function setLastTarget($lastTarget) {
		$this->lastTarget = $lastTarget;
		return $this;
	}

	/**
	 * @param WayIDStartAndEndEdge $a
	 * @param WayIDStartAndEndEdge $b
	 * @return int
	 */
	public static function CmpWayByID(WayIDStartAndEndEdge $a, WayIDStartAndEndEdge $b) {
		return ($a->getWayId() < $b->getWayId()) ? -1 : 1;
	}

}
