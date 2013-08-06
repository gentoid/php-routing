<?php
/**
 * @date: 8/6/13
 * @author: viktor
 */

namespace gentoid\route;


class InternalExtractorEdge {

	/** @var int */
	protected $osmId = 0;

	/** @var NodeID */
	protected $start;

	/** @var NodeID */
	protected $target;

	/** @var int */
	protected $type = 0;

	/** @var int */
	protected $direction = 0;

	/** @var float */
	protected $speed = 0;

	/** @var int */
	protected $nameId = 0;

	/** @var boolean */
	protected $isRoundabout = false;

	/** @var boolean */
	protected $ignoreInGrid = false;

	/** @var boolean */
	protected $isDurationSet = false;

	/** @var boolean */
	protected $isAccessRestricted = false;

	/** @var boolean */
	protected $isContraFlow = false;

	/** @var Coordinate */
	protected $startCoord;

	/** @var Coordinate */
	protected $targetCoord;

	public function __construct() {
		$this->start = new NodeID();
		$this->target = new NodeID();
		$this->startCoord = new Coordinate();
		$this->targetCoord = new Coordinate();
	}

	/**
	 * @param InternalExtractorEdge $b
	 * @return bool
	 */
	public function CmpEdgeByStartID(\gentoid\route\InternalExtractorEdge $b) {
		return bccomp($this->start->getValue(), $b->getStart()->getValue()) == -1;
	}

	/**
	 * @param InternalExtractorEdge $b
	 * @return bool
	 */
	public function CmpEdgeByTargetID(\gentoid\route\InternalExtractorEdge $b) {
		return bccomp($this->target->getValue(), $b->getTarget()->getValue()) == -1;
	}

	/**
	 * @return int
	 */
	public function getOsmId() {
		return $this->osmId;
	}

	/**
	 * @param int $osmId
	 */
	public function setOsmId($osmId) {
		$this->osmId = $osmId;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * @param \gentoid\route\NodeID $start
	 */
	public function setStart($start) {
		$this->start = $start;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @param \gentoid\route\NodeID $target
	 */
	public function setTarget($target) {
		$this->target = $target;
	}

	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param int $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * @param int $direction
	 */
	public function setDirection($direction) {
		$this->direction = $direction;
	}

	/**
	 * @return float
	 */
	public function getSpeed() {
		return $this->speed;
	}

	/**
	 * @return int
	 */
	public function getNameId() {
		return $this->nameId;
	}

	/**
	 * @param int $nameId
	 */
	public function setNameId($nameId) {
		$this->nameId = $nameId;
	}

	/**
	 * @return boolean
	 */
	public function getIsRoundabout() {
		return $this->isRoundabout;
	}

	/**
	 * @param boolean $isRoundabout
	 */
	public function setIsRoundabout($isRoundabout) {
		$this->isRoundabout = $isRoundabout;
	}

	/**
	 * @return boolean
	 */
	public function getIgnoreInGrid() {
		return $this->ignoreInGrid;
	}

	/**
	 * @param boolean $ignoreInGrid
	 */
	public function setIgnoreInGrid($ignoreInGrid) {
		$this->ignoreInGrid = $ignoreInGrid;
	}

	/**
	 * @return boolean
	 */
	public function getIsDurationSet() {
		return $this->isDurationSet;
	}

	/**
	 * @return boolean
	 */
	public function getIsAccessRestricted() {
		return $this->isAccessRestricted;
	}

	/**
	 * @param boolean $isAccessRestricted
	 */
	public function setIsAccessRestricted($isAccessRestricted) {
		$this->isAccessRestricted = $isAccessRestricted;
	}

	/**
	 * @return boolean
	 */
	public function getIsContraFlow() {
		return $this->isContraFlow;
	}

	/**
	 * @param boolean $isContraFlow
	 */
	public function setIsContraFlow($isContraFlow) {
		$this->isContraFlow = $isContraFlow;
	}

	/**
	 * @return \gentoid\route\Coordinate
	 */
	public function getStartCoord() {
		return $this->startCoord;
	}

	/**
	 * @param \gentoid\route\Coordinate $startCoord
	 */
	public function setStartCoord($startCoord) {
		$this->startCoord = $startCoord;
	}

	/**
	 * @return \gentoid\route\Coordinate
	 */
	public function getTargetCoord() {
		return $this->targetCoord;
	}

	/**
	 * @param \gentoid\route\Coordinate $targetCoord
	 */
	public function setTargetCoord($targetCoord) {
		$this->targetCoord = $targetCoord;
	}


}
 