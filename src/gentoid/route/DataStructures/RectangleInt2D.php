<?php
/**
 * @author viktor
 * @date 15.08.13
 */

namespace gentoid\route\DataStructures;


class RectangleInt2D {

	protected $minLat = PHP_INT_MAX;
	protected $maxLat = PHP_INT_MAX;
	protected $minLon = PHP_INT_MAX;
	protected $maxLon = PHP_INT_MAX;

	public function initializeMBRectangle(LeafNode $objects, $elementCount) {

	}

} 
