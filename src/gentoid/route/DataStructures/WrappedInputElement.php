<?php
/**
 * @author viktor
 * @date 18.08.13
 */

namespace gentoid\route\DataStructures;


class WrappedInputElement {

	protected $arrayIndex;

	protected $hilbertValue;

	/**
	 * @param int $ai
	 * @param string $hv
	 */
	public function __construct($ai = PHP_INT_MAX, $hv = '0') {
		$this->arrayIndex = $ai;
		$this->hilbertValue = $hv;
	}

	public function isLessThan(WrappedInputElement $other) {
		return bccomp($this->hilbertValue, $other->getHilbertValue()) === -1;
	}

	/**
	 * @return int
	 */
	public function getArrayIndex() {
		return $this->arrayIndex;
	}

	/**
	 * @param int $arrayIndex
	 */
	public function setArrayIndex($arrayIndex) {
		$this->arrayIndex = $arrayIndex;
	}

	/**
	 * @return string
	 */
	public function getHilbertValue() {
		return $this->hilbertValue;
	}

	/**
	 * @param string $hilbertValue
	 */
	public function setHilbertValue($hilbertValue) {
		$this->hilbertValue = $hilbertValue;
	}

	/**
	 * @param string $add
	 */
	public function addToHilbertValue($add) {
		$this->hilbertValue = bcadd($this->hilbertValue, $add);
	}

} 
