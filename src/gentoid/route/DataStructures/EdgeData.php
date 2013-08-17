<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class EdgeData {

	/** @var \gentoid\route\NodeID */
	protected $id;

	/** @var boolean */
	protected $shortcut;

	/** @var int */
	protected $distance;

	/** @var boolean */
	protected $forward;

	/** @var boolean */
	protected $backward;

	public function __construct() {
		$this->id = new NodeID();
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param \gentoid\route\NodeID $id
	 * @return EdgeData
	 */
	public function setId(NodeID $id) {
		$this->id = $id;
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
	 * @return EdgeData
	 */
	public function setShortcut($shortcut) {
		$this->shortcut = (boolean)$shortcut;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDistance() {
		return $this->distance;
	}

	/**
	 * @param int $distance
	 * @return EdgeData
	 */
	public function setDistance($distance) {
		$this->distance = intval($distance);
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
	 * @return EdgeData
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
	 * @return EdgeData
	 */
	public function setBackward($backward) {
		$this->backward = (bool)$backward;
		return $this;
	}

}
