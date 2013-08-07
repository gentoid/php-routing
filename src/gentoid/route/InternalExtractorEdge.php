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

	/** @var Direction */
	protected $direction;

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
		$this->direction = new Direction(Direction::NOT_SURE);
		$this->startCoord = new Coordinate();
		$this->targetCoord = new Coordinate();
	}

	/**
	 * @param InternalExtractorEdge $a
	 * @param InternalExtractorEdge $b
	 * @return int
	 */
	public function CmpEdgeByStartID(InternalExtractorEdge $a, InternalExtractorEdge $b) {
		return bccomp($a->getStart()->getValue(), $b->getStart()->getValue());
	}

	/**
	 * @param InternalExtractorEdge $a
	 * @param InternalExtractorEdge $b
	 * @return int
	 */
	public function CmpEdgeByTargetID(InternalExtractorEdge $a, InternalExtractorEdge $b) {
		return bccomp($a->getTarget()->getValue(), $b->getTarget()->getValue());
	}

	/**
	 * @return string
	 */
	public function pack() {
		return pack('L', $this->type) . pack('L', $this->nameId) . pack('S', $this->isRoundabout) . pack('S', $this->ignoreInGrid) . pack('S', $this->isAccessRestricted) . pack('S', $this->isContraFlow);
	}

	/**
	 * @return int
	 */
	public function getOsmId() {
		return $this->osmId;
	}

	/**
	 * @param int $osmId
	 * @return InternalExtractorEdge
	 */
	public function setOsmId($osmId) {
		$this->osmId = intval($osmId);
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * @param \gentoid\route\NodeID $start
	 * @return InternalExtractorEdge
	 */
	public function setStart(\gentoid\route\NodeID $start) {
		$this->start = $start;
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
	 * @return InternalExtractorEdge
	 */
	public function setTarget(\gentoid\route\NodeID $target) {
		$this->target = $target;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param int $type
	 * @return InternalExtractorEdge
	 */
	public function setType($type) {
		$this->type = intval($type);
		return $this;
	}

	/**
	 * @return Direction
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * @param Direction $direction
	 * @return InternalExtractorEdge
	 */
	public function setDirection(Direction $direction) {
		$this->direction = intval($direction);
		return $this;
	}

	/**
	 * @return float
	 */
	public function getSpeed() {
		return $this->speed;
	}

	/**
	 * @param float $speed
	 * @return InternalExtractorEdge
	 */
	public function setSpeed($speed) {
		$this->speed = floatval($speed);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getNameId() {
		return $this->nameId;
	}

	/**
	 * @param int $nameId
	 * @return InternalExtractorEdge
	 */
	public function setNameId($nameId) {
		$this->nameId = intval($nameId);
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsRoundabout() {
		return $this->isRoundabout;
	}

	/**
	 * @param boolean $isRoundabout
	 * @return InternalExtractorEdge
	 */
	public function setIsRoundabout($isRoundabout) {
		$this->isRoundabout = (boolean)$isRoundabout;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIgnoreInGrid() {
		return $this->ignoreInGrid;
	}

	/**
	 * @param boolean $ignoreInGrid
	 * @return InternalExtractorEdge
	 */
	public function setIgnoreInGrid($ignoreInGrid) {
		$this->ignoreInGrid = (boolean)$ignoreInGrid;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsDurationSet() {
		return $this->isDurationSet;
	}

	/**
	 * @param $isDurationSet
	 * @return InternalExtractorEdge
	 */
	public function setIsDurationSet($isDurationSet) {
		$this->isDurationSet = (boolean)$isDurationSet;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsAccessRestricted() {
		return $this->isAccessRestricted;
	}

	/**
	 * @param boolean $isAccessRestricted
	 * @return InternalExtractorEdge
	 */
	public function setIsAccessRestricted($isAccessRestricted) {
		$this->isAccessRestricted = (boolean)$isAccessRestricted;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsContraFlow() {
		return $this->isContraFlow;
	}

	/**
	 * @param boolean $isContraFlow
	 * @return InternalExtractorEdge
	 */
	public function setIsContraFlow($isContraFlow) {
		$this->isContraFlow = (boolean)$isContraFlow;
		return $this;
	}

	/**
	 * @return \gentoid\route\Coordinate
	 */
	public function getStartCoord() {
		return $this->startCoord;
	}

	/**
	 * @param \gentoid\route\Coordinate $startCoord
	 * @return InternalExtractorEdge
	 */
	public function setStartCoord(\gentoid\route\Coordinate $startCoord) {
		$this->startCoord = $startCoord;
		return $this;
	}

	/**
	 * @return \gentoid\route\Coordinate
	 */
	public function getTargetCoord() {
		return $this->targetCoord;
	}

	/**
	 * @param \gentoid\route\Coordinate $targetCoord
	 * @return InternalExtractorEdge
	 */
	public function setTargetCoord(\gentoid\route\Coordinate $targetCoord) {
		$this->targetCoord = $targetCoord;
		return $this;
	}


}
 