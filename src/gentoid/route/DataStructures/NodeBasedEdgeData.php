<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class NodeBasedEdgeData {

	/** @var  int */
	protected $distance;

	/** @var int */
	protected $edgeBasedNodeID;

	/** @var NodeID */
	protected $nameID;

	/** @var int */
	protected $type;

	/** @var boolean */
	protected $isAccessRestricted;

	/** @var boolean */
	protected $shortcut;

	/** @var boolean */
	protected $forward;

	/** @var boolean */
	protected $backward;

	/** @var boolean */
	protected $roundabout;

	/** @var boolean */
	protected $ignoreInGrid;

	/** @var boolean */
	protected $contraFlow;

	/**
	 * @return int
	 */
	public function getDistance() {
		return $this->distance;
	}

	/**
	 * @param int $distance
	 * @return NodeBasedEdgeData
	 */
	public function setDistance($distance) {
		$this->distance = intval($distance);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEdgeBasedNodeID() {
		return $this->edgeBasedNodeID;
	}

	/**
	 * @param int $edgeBasedNodeID
	 * @return NodeBasedEdgeData
	 */
	public function setEdgeBasedNodeID($edgeBasedNodeID) {
		$this->edgeBasedNodeID = intval($edgeBasedNodeID);
		return $this;
	}

	/**
	 * @return NodeID
	 */
	public function getNameID() {
		return $this->nameID;
	}

	/**
	 * @param NodeID $nameID
	 * @return NodeBasedEdgeData
	 */
	public function setNameID($nameID) {
		$this->nameID = $nameID;
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
	 * @return NodeBasedEdgeData
	 */
	public function setType($type) {
		$this->type = intval($type);
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
	 * @return NodeBasedEdgeData
	 */
	public function setIsAccessRestricted($isAccessRestricted) {
		$this->isAccessRestricted = (bool)$isAccessRestricted;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getShortcut() {
		return $this->shortcut;
	}

	/**
	 * @param boolean $shortcut
	 * @return NodeBasedEdgeData
	 */
	public function setShortcut($shortcut) {
		$this->shortcut = (bool)$shortcut;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getForward() {
		return $this->forward;
	}

	/**
	 * @param boolean $forward
	 * @return NodeBasedEdgeData
	 */
	public function setForward($forward) {
		$this->forward = (bool)$forward;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getBackward() {
		return $this->backward;
	}

	/**
	 * @param boolean $backward
	 * @return NodeBasedEdgeData
	 */
	public function setBackward($backward) {
		$this->backward = (bool)$backward;
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
	 * @return NodeBasedEdgeData
	 */
	public function setRoundabout($roundabout) {
		$this->roundabout = (bool)$roundabout;
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
	 * @return NodeBasedEdgeData
	 */
	public function setIgnoreInGrid($ignoreInGrid) {
		$this->ignoreInGrid = (bool)$ignoreInGrid;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getContraFlow() {
		return $this->contraFlow;
	}

	/**
	 * @param boolean $contraFlow
	 * @return NodeBasedEdgeData
	 */
	public function setContraFlow($contraFlow) {
		$this->contraFlow = (bool)$contraFlow;
		return $this;
	}

	public function __clone() {
		$this->nameID->setValue(self::getNameID()->getValue());
	}

}
