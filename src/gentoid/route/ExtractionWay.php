<?php
/**
 * @date: 8/6/13
 * @author: viktor
 */

namespace gentoid\route;


class ExtractionWay {

	/** @var Direction */
	protected $direction;

	/** @var int */
	protected $id;

	/** @var int */
	protected $nameId;

	/** @var string */
	protected $name;

	/** @var float */
	protected $speed;

	/** @var float */
	protected $backwardSpeed;

	/** @var float */
	protected $duration;

	/** @var int */
	protected $type;

	/** @var boolean */
	protected $access;

	/** @var boolean */
	protected $roundabout;

	/** @var boolean */
	protected $isAccessRestricted;

	/** @var boolean */
	protected $ignoreInGrid;

	/** @var int */
	protected $osmId;

	/** @var NodeID[] */
	protected $path;

	/** @var array */
	protected $keyVals;

	public function __construct() {
		$this->direction = new Direction();
		$this->clear();
	}

	public function clear() {
		$this->id = -1;
		$this->nameId = -1;
		$this->path = array();
		$this->keyVals = array();
		$this->direction->setValue(Direction::NOT_SURE);
		$this->speed = -1;
		$this->backwardSpeed = -1;
		$this->duration = -1;
		$this->type = -1;
		$this->access = true;
		$this->roundabout = false;
		$this->isAccessRestricted = false;
		$this->ignoreInGrid = false;
		$this->osmId = 0;
	}

	/**
	 * @return \gentoid\route\Direction
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * @param \gentoid\route\Direction $direction
	 * @return ExtractionWay
	 */
	public function setDirection(\gentoid\route\Direction $direction) {
		$this->direction = $direction;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return ExtractionWay
	 */
	public function setId($id) {
		$this->id = intval($id);
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
	 * @return ExtractionWay
	 */
	public function setNameId($nameId) {
		$this->nameId = intval($nameId);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return ExtractionWay
	 */
	public function setName($name) {
		$this->name = $name;
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
	 * @return ExtractionWay
	 */
	public function setSpeed($speed) {
		$this->speed = floatval($speed);
		return $this;
	}

	/**
	 * @return float
	 */
	public function getBackwardSpeed() {
		return $this->backwardSpeed;
	}

	/**
	 * @param float $backwardSpeed
	 * @return ExtractionWay
	 */
	public function setBackwardSpeed($backwardSpeed) {
		$this->backwardSpeed = floatval($backwardSpeed);
		return $this;
	}

	/**
	 * @return float
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * @param float $duration
	 * @return ExtractionWay
	 */
	public function setDuration($duration) {
		$this->duration = floatval($duration);
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
	 * @return ExtractionWay
	 */
	public function setType($type) {
		$this->type = intval($type);
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getAccess() {
		return $this->access;
	}

	/**
	 * @param boolean $access
	 * @return ExtractionWay
	 */
	public function setAccess($access) {
		$this->access = (bool)$access;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getRoundabout() {
		return $this->roundabout;
	}

	/**
	 * @param boolean $roundabout
	 * @return ExtractionWay
	 */
	public function setRoundabout($roundabout) {
		$this->roundabout = (bool)$roundabout;
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
	 * @return ExtractionWay
	 */
	public function setIsAccessRestricted($isAccessRestricted) {
		$this->isAccessRestricted = (bool)$isAccessRestricted;
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
	 * @return ExtractionWay
	 */
	public function setIgnoreInGrid($ignoreInGrid) {
		$this->ignoreInGrid = (bool)$ignoreInGrid;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOsmId() {
		return $this->osmId;
	}

	/**
	 * @param int $osmId
	 * @return ExtractionWay
	 */
	public function setOsmId($osmId) {
		$this->osmId = intval($osmId);
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID[]
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @param \gentoid\route\NodeID[] $path
	 * @throws \Exception
	 * @return ExtractionWay
	 */
	public function setPath(array $path) {
		foreach ($path as $node) {
			if (!($node instanceof \gentoid\route\NodeID)) {
				throw new \Exception('Not NodeID');
			}
		}
		$this->path = $path;
		return $this;
	}

	/**
	 * @param NodeID $n
	 * @return ExtractionWay
	 */
	public function addPathElement(NodeID $n) {
		$this->path[] = $n;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getKeyVals() {
		return $this->keyVals;
	}

	/**
	 * @param string $key
	 * @param mixed $val
	 * @return ExtractionWay
	 */
	public function addKeyVal($key, $val) {
		$this->keyVals[$key] = $val;
		return $this;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function findValByKey($key) {
		return (is_int(strpos($key, ':'))) ?
			(isset($this->keyVals->{$key})) ? $this->keyVals->{$key} : null
			: (isset($this->keyVals[$key])) ? $this->keyVals[$key] : null;
	}

}
