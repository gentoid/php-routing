<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;
use gentoid\route\NodeInfo;
use gentoid\route\TurnRestriction;

class EdgeBasedGraphFactory {

	/**
	 * @param int $numberOfNodes
	 * @param ImportEdge[] $inputEdgeList
	 * @param NodeID[] $barrierNodeList
	 * @param NodeID[] $trafficLightNodeList
	 * @param TurnRestriction[] $inputRestrictionsList
	 * @param NodeInfo[] $mNodeInfoList
	 * @param SpeedProfileProperties $speedProfile
	 */
	public function __construct(
		$numberOfNodes,
		array $inputEdgeList,
		array $barrierNodeList,
		array $trafficLightNodeList,
		array $inputRestrictionsList,
		array $mNodeInfoList,
		SpeedProfileProperties $speedProfile
	) {}

	public function run() {}

	public function getEdgeBasedEdges() {}

	public function getEdgeBasedNodes() {}

	public function getOriginalEdgeData() {}

	public function analizeTurn() {}

	public function getTurnPenalty() {}

	public function getNumberOfNodes() {}

}
