<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class EdgeBasedNode {

	/** @var NodeID */
	protected $id;

	/** @var FixedPointCoordinate */
	protected $coordinate1;

	/** @var FixedPointCoordinate */
	protected $coordinate2;

	/** @var boolean */
	protected $belongsToTinyComponent = false;

	/** @var NodeID */
	protected $nameId;

	/** @var int */
	protected $weight = PHP_INT_MAX;

	/** @var boolean */
	protected $ignoreIngrid = false;

	public function __construct() {
		$this->id = new NodeID();
		$this->coordinate1 = new FixedPointCoordinate();
		$this->nameId = new NodeID();
	}

	/**
	 * @param EdgeBasedNode $n
	 * @return bool
	 */
	public function isLessThan(EdgeBasedNode $n) {
		return $this->id->lessThan($n->getId());
	}

	/**
	 * @param EdgeBasedNode $n
	 * @return bool
	 */
	public function isEqual(EdgeBasedNode $n) {
		return$this->id->isEqual($n->getId());
	}

	/**
	 * @return FixedPointCoordinate
	 */
	public function centroid() {
		$centroid = new FixedPointCoordinate();
		$lat1 = $this->coordinate1->getLat();
		$lat2 = $this->coordinate2->getLat();
		$lon1 = $this->coordinate1->getLon();
		$lon2 = $this->coordinate2->getLon();
		$centroid
			->setLat((min($lat1, $lat2) + max($lat1, $lat2)) / 2)
			->setLon((min($lon1, $lon2) + max($lon1, $lon2)) / 2);

		return $centroid;
	}

	/**
	 * @return bool
	 */
	public function isIgnored() {
		return $this->ignoreIngrid;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param \gentoid\route\NodeID $id
	 * @return EdgeBasedNode
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return \gentoid\route\DataStructures\FixedPointCoordinate
	 */
	public function getCoordinate1() {
		return $this->coordinate1;
	}

	/**
	 * @param \gentoid\route\DataStructures\FixedPointCoordinate $coordinate1
	 */
	public function setCoordinate1($coordinate1) {
		$this->coordinate1 = $coordinate1;
		return $this;
	}

	/**
	 * @return \gentoid\route\DataStructures\FixedPointCoordinate
	 */
	public function getCoordinate2() {
		return $this->coordinate2;
	}

	/**
	 * @param \gentoid\route\DataStructures\FixedPointCoordinate $coordinate2
	 * @return EdgeBasedNode
	 */
	public function setCoordinate2($coordinate2) {
		$this->coordinate2 = $coordinate2;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getBelongsToTinyComponent() {
		return $this->belongsToTinyComponent;
	}

	/**
	 * @param boolean $belongsToTinyComponent
	 * @return EdgeBasedNode
	 */
	public function setBelongsToTinyComponent($belongsToTinyComponent) {
		$this->belongsToTinyComponent = $belongsToTinyComponent;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getNameId() {
		return $this->nameId;
	}

	/**
	 * @param \gentoid\route\NodeID $nameId
	 * @return EdgeBasedNode
	 */
	public function setNameId($nameId) {
		$this->nameId = $nameId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWeight() {
		return $this->weight;
	}

	/**
	 * @param int $weight
	 * @return EdgeBasedNode
	 */
	public function setWeight($weight) {
		$this->weight = $weight;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIgnoreIngrid() {
		return $this->ignoreIngrid;
	}

	/**
	 * @param boolean $ignoreIngrid
	 * @return EdgeBasedNode
	 */
	public function setIgnoreIngrid($ignoreIngrid) {
		$this->ignoreIngrid = $ignoreIngrid;
		return $this;
	}

}
