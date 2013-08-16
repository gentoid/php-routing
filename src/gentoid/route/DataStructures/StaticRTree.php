<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


class StaticRTree {

	/**
	 * @param FixedPointCoordinate $inputCoordinate
	 * @param PhantomNode $resultPhantomNode
	 * @param int $zoomLevel
	 */
	public function findPhantomNodeForCoordinate(
		FixedPointCoordinate $inputCoordinate,
		PhantomNode $resultPhantomNode,
		$zoomLevel
	) {
		$ignoreTinyComponents = ($zoomLevel <= 14);
		$nearestEdge = new EdgeBasedNode();
		$IOCount = 0;
		$exploredTreeNodesCount = 0;
		$minDist = PHP_INT_MAX;
		$minMaxDist = PHP_INT_MAX;
		$foundANearestEdge = false;

		$nearest= new FixedPointCoordinate();
		$currentStartCoordinate = new FixedPointCoordinate();
		$currentEndCoordinate = new FixedPointCoordinate();

		$traversalQueue = array();

		// todo

	}

}
