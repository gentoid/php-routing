<?php

namespace gentoid\route;


class NodeID extends BigInt {

	const DEFAULT_VALUE = '-1';

	public function __construct($value = NodeID::DEFAULT_VALUE) {
		parent::__construct($value);
	}

	/**
	 * @param NodeID $a
	 * @param NodeID $b
	 * @return int
	 */
	public static function cmp(NodeID $a, NodeID $b) {
		return bccomp($a->getValue(), $b->getValue());
	}

	/**
	 * @param NodeID[] $array
	 * @throws \Exception
	 */
	public static function unique(array &$array) {
		$ids = array();

		foreach ($array as $key => $nodeId) {
			if (!($nodeId instanceof NodeID)) {
				throw new \Exception('Object is not instance of NodeID');
			}
			$id = $nodeId->getValue();
			if (isset($ids[$id])) {
				unset($array[$key]);
			}
			else {
				$ids[$id] = 1;
			}
		}
	}

	public function pack() {
		return pack('a20', $this->value);
	}

	/**
	 * @param int $value
	 * @return bool
	 */
	public function lessThanInt($value) {
		return bccomp($this->value, $value) === -1;
	}

	/**
	 * @param int $value
	 * @return bool
	 */
	public function moreThanInt($value) {
		return bccomp($this->value, $value) === 1;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function isEqualInt($value) {
		return bccomp($this->value, $value) === 0;
	}

	/**
	 * @param NodeID $n
	 * @return bool
	 */
	public function lessThan(NodeID $n) {
		return bccomp($this->value, $n->getValue()) === -1;
	}

	/**
	 * @param NodeID $n
	 * @return bool
	 */
	public function moreThan(NodeID $n) {
		return bccomp($this->value, $n->getValue()) === 1;
	}

	/**
	 * @param NodeID $n
	 * @return bool
	 */
	public function isEqual(NodeID $n) {
		return bccomp($this->value, $n->getValue()) === 0;
	}

}
