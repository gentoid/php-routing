<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


class TreeNode {

	/** @var \gentoid\route\DataStructures\RectangleInt2D */
	protected $minimumBoundingRectangle;

	/** @var int */
	protected $childCount = 0;

	/** @var bool */
	protected $childIsOnDisk = false;

	/** @var array */
	protected $children = array();

	public function __construct() {
		$this->minimumBoundingRectangle = new RectangleInt2D();
	}

	/**
	 * @return \gentoid\route\DataStructures\RectangleInt2D
	 */
	public function getMinimumBoundingRectangle() {
		return $this->minimumBoundingRectangle;
	}

	/**
	 * @param \gentoid\route\DataStructures\RectangleInt2D $minimumBoundingRectangle
	 * @return TreeNode
	 */
	public function setMinimumBoundingRectangle($minimumBoundingRectangle) {
		$this->minimumBoundingRectangle = $minimumBoundingRectangle;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getChildCount() {
		return $this->childCount;
	}

	/**
	 * @param int $childCount
	 * @return TreeNode
	 */
	public function setChildCount($childCount) {
		$this->childCount = intval($childCount);
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getChildIsOnDisk() {
		return $this->childIsOnDisk;
	}

	/**
	 * @param boolean $childIsOnDisk
	 * @return TreeNode
	 */
	public function setChildIsOnDisk($childIsOnDisk) {
		$this->childIsOnDisk = (bool)$childIsOnDisk;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param $child
	 * @return TreeNode
	 */
	public function addChild($child) {
		$this->children[] = $child;
		return $this;
	}

}
