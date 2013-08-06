<?php

namespace gentoid\route;


class NodeID extends BigInt {

	/**
	 * @param NodeID $a
	 * @param NodeID $b
	 * @return int
	 */
	public static function cmp(NodeID $a, NodeID $b) {
		return bccomp($a->getValue(), $b->getValue());
	}

}
