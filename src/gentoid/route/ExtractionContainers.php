<?php

namespace gentoid\route;


class ExtractionContainers {

	/** @var \gentoid\route\NodeID[] */
	protected $usedNodeIDs = array();

	/** @var \gentoid\route\Node[] */
	protected $allNodes = array();

	/** @var \gentoid\route\InternalExtractorEdge[] */
	protected $allEdges = array();

	/** @var string[] */
	protected $nameVector = array();

	/** @var \gentoid\route\RawRestrictionContainer[] */
	protected $restrictionsVector = array();

	/** @var \gentoid\route\WayIDStartAndEndEdge[] */
	protected $wayStartEndVector = array();

	public function prepareData($outputFileName, $restrictionsFileName) {
		$usedNodeCounter = 0;
		$usedEdgeCounter = 0;

		usort($this->usedNodeIDs, array('NodeID', 'cmp'));
	}

}
