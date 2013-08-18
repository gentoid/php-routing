<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\Contractor;


use gentoid\route\DataStructures\DynamicGraph;
use gentoid\route\DataStructures\ImportEdge;
use gentoid\route\DataStructures\InputEdge;
use gentoid\route\DataStructures\NodeBasedEdge;
use gentoid\route\DataStructures\Percent;
use gentoid\route\DataStructures\SpeedProfileProperties;
use gentoid\route\NodeID;
use gentoid\route\NodeInfo;
use gentoid\route\TurnRestriction;
use gentoid\utils\LogUtil;

class EdgeBasedGraphFactory {

	/** @var \gentoid\route\DataStructures\SpeedProfileProperties */
	protected $speedProfile;

	/** @var int */
	protected $mTurnRestrictionsCount;

	/** @var array|\gentoid\route\NodeInfo[] */
	protected $mNodeInfoList;

	/** @var array */
	protected $mRestrictionMap = array();

	/** @var array vector of $emanatingRestrictionsVector's */
	protected $mRestrictionBucketList = array();

	/** @var array vector of $restrictionTarget's */
	protected $emanatingRestrictionsVector = array();

	/** @var array pair of NodeID and boolean */
	protected $restrictionTarget = array();

	/** @var array pair of two NodeID's */
	protected $restrictionSource = array();

	/** @var \gentoid\route\NodeID[] */
	protected $mBarrierNodes = array();

	/** @var \gentoid\route\NodeID[] */
	protected $mTrafficLights = array();

	/**
	 * @var \gentoid\route\DataStructures\DynamicGraph
	 */
	protected $mNodeBasedGraph;

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

		/** @var TurnRestriction $restriction */
		foreach ($inputRestrictionsList as $restriction) {
			$restrictionSource = array('first' => $restriction->getFromNode(), 'second' => $restriction->getViaNode());
			if (($index = $this->findRestrictionSourceInMap($restrictionSource)) === -1) {
				$index = count($this->mRestrictionBucketList);
				$this->mRestrictionMap[$index] = $restrictionSource;
			}
			else {
				if ($this->mRestrictionBucketList[$index][0]['second']) {
					continue;
				}
				elseif ($restriction->getIsOnly()) {
					$this->mTurnRestrictionCount -= count($this->mRestrictionBucketList[$index]);
					$this->mRestrictionBucketList[$index] = array();
				}
			}
			$this->mTurnRestrictionsCount++;
			array_push(
				$this->mRestrictionBucketList[$index],
				array(
					'first' => $restriction->getToNode(),
					'second' => $restriction->getIsOnly()
				)
			);
		}

		$this->mBarrierNodes  = $barrierNodeList;
		$this->mTrafficLights = $trafficLightNodeList;

		/** @var InputEdge[] $edgeList */
		$edgeList = array();
		/** @var ImportEdge $importEdge */
		foreach ($inputEdgeList as $importEdge) {
			$edge = new InputEdge();
			$data = $edge->getData();
			if (!$importEdge->getForward()) {
				$edge
					->setSource($importEdge->getTarget())
					->setTarget($importEdge->getSource());
				$data
					->setBackward($importEdge->getForward())
					->setForward($importEdge->getBackward());
			}
			else {
				$edge
					->setSource($importEdge->getSource())
					->setTarget($importEdge->getTarget());
				$data
					->setForward($importEdge->getForward())
					->setBackward($importEdge->getBackward());
			}
			if ($edge->getSource()->isEqual($edge->getTarget())) {
				continue;
			}
			$data
				->setDistance(max($importEdge->getWeight(), 1))
				->setShortcut(false)
				->setRoundabout($importEdge->getRoundabout())
				->setIgnoreInGrid($importEdge->getIgnoreInGrid())
				->setNameID($importEdge->getName())
				->setType($importEdge->getType())
				->setIsAccessRestricted($importEdge->getAccessRestricted())
				->setEdgeBasedNodeID(count($edgeList))
				->setContraFlow($importEdge->getContraFlow());
			$edgeList[] = $edge;
			if ($data->getBackward()) {
				$backEdge = clone $edge;
				$tmpNodeID = $backEdge->getSource();
				$backEdge
					->setSource($backEdge->getTarget())
					->setTarget($tmpNodeID)
					->getData()
						->setForward($importEdge->getBackward())
						->setBackward($importEdge->getForward())
						->setEdgeBasedNodeID(count($edgeList));
				$edgeList[] = $backEdge;
			}
		}

		// in OSRM here is
		/*
		 *     std::vector<ImportEdge>().swap(input_edge_list);
         *     std::sort( edges_list.begin(), edges_list.end() );
         *     m_node_based_graph = boost::make_shared<NodeBasedDynamicGraph>(
         *         number_of_nodes, edges_list
         *     );
		 */

		$this->mNodeBasedGraph = new DynamicGraph($numberOfNodes, $edgeList);
	}

	/**
	 * @param array $rs
	 * @return int
	 */
	protected function findRestrictionSourceInMap(array $rs) {
		foreach ($this->mRestrictionMap as $key => $item) {
			if ($rs['first']->isEqual($item['first']->getValue()) && $rs['second']->isEqual($item['second']->getValue())) {
				return $key;
			}
		}
		return -1;
	}

	public function run() {
		LogUtil::infoAsIs('Identifying components of the road network');

		$p = new Percent($this->mNodeBasedGraph->getNumberOfNodes());
		$skippedTurnsCounter  = 0;
		$nodeBasedEdgeCounter = 0;
		$originalEdgesCounter = 0;

		$currentComponent = 0;
		$currentComponentSize = 0;
		$bfsQueue = array();
		$q = \SplQueue::IT_MODE_FIFO;

		$componentIndexList = array();

		/** @var NodeID[] $componentSizeList */
		$componentSizeList = array();

		for ($node = new NodeID('0'), $lastNode = $this->mNodeBasedGraph->getNumberOfNodes(); $node->lessThanInt($lastNode); $node->inc()) {
			if (isset($componentIndexList[$node->getValue()])) {
				continue;
			}
			$bfsQueue[] = array('first' => $node, 'second' => $node);
			$p->printIncrement();
			while (!empty($bfsQueue)) {
				$currentQueueItem = array_shift($bfsQueue);
				/** @var NodeID $v */
				$v = $currentQueueItem['first'];
				/** @var NodeID $v */
				$v = $currentQueueItem['second'];
				$currentComponentSize++;
				$isBarrierNode = false; // todo
			}
		}
	}

} 
