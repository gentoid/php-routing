<?php
/**
 * @author viktor
 * @date 15.08.13
 */

namespace gentoid\route\DataStructures;


class LeafNode {

	/** @var int */
	protected $objectCount = 0;

	/** @var EdgeBasedNode[] */
	protected $objects = array();

	/**
	 * @return int
	 */
	public function getObjectCount() {
		return $this->objectCount;
	}

	/**
	 * @param int $objectCount
	 * @return LeafNode
	 */
	public function setObjectCount($objectCount) {
		$this->objectCount = intval($objectCount);
		return $this;
	}

	/**
	 * @return \gentoid\route\DataStructures\EdgeBasedNode[]
	 */
	public function getObjects() {
		return $this->objects;
	}

	/**
	 * @param $i
	 * @return EdgeBasedNode
	 */
	public function getObject($i) {
		return (isset($this->objects[$i]) ? $this->objects[$i] : new EdgeBasedNode());
	}

	/**
	 * @param \gentoid\route\DataStructures\EdgeBasedNode $object
	 * @return LeafNode
	 */
	public function addObject(EdgeBasedNode $object) {
		$this->objects = $object;
		return $this;
	}

} 
