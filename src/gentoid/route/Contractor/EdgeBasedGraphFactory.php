<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\Contractor;


use gentoid\route\DataStructures\ImportEdge;
use gentoid\route\DataStructures\SpeedProfileProperties;
use gentoid\route\NodeID;
use gentoid\route\NodeInfo;
use gentoid\route\TurnRestriction;

class EdgeBasedGraphFactory {

	/** @var \gentoid\route\DataStructures\SpeedProfileProperties */
	protected $speedProfile;

	/** @var int */
	protected $mTurnRestrictionsCount;

	/** @var array|\gentoid\route\NodeInfo[] */
	protected $mNodeInfoList;

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
		array &$inputEdgeList,
		array &$barrierNodeList,
		array &$trafficLightNodeList,
		array &$inputRestrictionsList,
		array &$mNodeInfoList,
		SpeedProfileProperties $speedProfile
	) {
		$this->speedProfile = $speedProfile;
		$this->mTurnRestrictionsCount = 0;
		$this->mNodeInfoList = $mNodeInfoList;
	}

} 
