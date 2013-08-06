<?php
/**
 * @date: 8/6/13
 * @author: viktor
 */

namespace gentoid\route;


class ExtractorRelation {

	/** @var WayType */
	protected $wayType;

	/** @var array */
	protected $keyVals = array();

	public function __construct() {
		$this->wayType = new \gentoid\route\WayType();
	}

	/**
	 * @return \gentoid\route\WayType
	 */
	public function getWayType() {
		return $this->wayType;
	}

	/**
	 * @param \gentoid\route\WayType $wayType
	 * @return ExtractorRelation
	 */
	public function setWayType(\gentoid\route\WayType $wayType) {
		$this->wayType = $wayType;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getKeyVals() {
		return $this->keyVals;
	}

	/**
	 * @param array $keyVals
	 * @return ExtractorRelation
	 */
	public function setKeyVals(array $keyVals) {
		$this->keyVals = $keyVals;
		return $this;
	}

}
 