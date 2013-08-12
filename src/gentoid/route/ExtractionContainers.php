<?php

namespace gentoid\route;


use gentoid\route\DataStructures\Coordinate;
use gentoid\utils\DB;
use gentoid\utils\LogUtil;

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

	/** @var \PDO */
	protected $dbh;

	public function __construct() {
		$this->dbh = DB::getInstance()->getDbh();
	}

	/**
	 * @return void
	 */
	public function prepareData() {
		$usedNodeCounter = 0;
		$usedEdgeCounter = 0;

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Erasing duplicate nodes      ...');
		NodeID::unique($this->usedNodeIDs);
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Sorting used nodes           ...');
		usort($this->usedNodeIDs, array('\\gentoid\\route\\NodeID', 'cmp'));
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Sorting all nodes            ...');
		usort($this->allNodes, array('\\gentoid\\route\\Node', 'CmpNodeByID'));
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Sorting used ways            ...');
		usort($this->wayStartEndVector, array('\\gentoid\\route\\WayIDStartAndEndEdge', 'CmpWayByID'));
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Sorting restrictions. by from...');
		usort($this->restrictionsVector, array('\\gentoid\\route\\RawRestrictionContainer', 'CmpRestrictionContainerByFrom'));
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Fixing restriction starts    ...');
		$i = $k = 0;
		while (isset($this->wayStartEndVector[$i]) && isset($this->restrictionsVector[$k])) {
			$wayStartEndEdge      = &$this->wayStartEndVector[$i];
			$restrictionContainer = &$this->restrictionsVector[$k];

			if ($wayStartEndEdge->getWayId() < $restrictionContainer->getFromWay()) {
				++$i;
				continue;
			}
			if ($wayStartEndEdge->getWayId() > $restrictionContainer->getFromWay()) {
				++$k;
				continue;
			}

			$viaNodeValue  = $restrictionContainer->getRestriction()->getViaNode()->getValue();
			$fromNode = $restrictionContainer->getRestriction()->getFromNode();

			if ($wayStartEndEdge->getFirstStart()->getValue() == $viaNodeValue) {
				$fromNode->setValue($wayStartEndEdge->getFirstTarget()->getValue());
			}
			elseif ($wayStartEndEdge->getFirstTarget()->getValue() == $viaNodeValue) {
				$fromNode->setValue($wayStartEndEdge->getFirstStart()->getValue());
			}
			elseif ($wayStartEndEdge->getLastStart()->getValue() == $viaNodeValue) {
				$fromNode->setValue($wayStartEndEdge->getLastTarget()->getValue());
			}
			elseif ($wayStartEndEdge->getLastTarget()->getValue() == $viaNodeValue) {
				$fromNode->setValue($wayStartEndEdge->getLastStart()->getValue());
			}

			++$k;
		}
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Sorting restrictions. by to  ...');
		usort($this->restrictionsVector, array('\\gentoid\\route\\RawRestrictionContainer', 'CmpRestrictionContainerByTo'));
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$usableRestrictionsCounter = 0;

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Fixing restriction ends      ...');
		$i = $k = 0;
		while (isset($this->wayStartEndVector[$i]) && isset($this->restrictionsVector[$k])) {
			$wayStartEndEdge      = &$this->wayStartEndVector[$i];
			$restrictionContainer = &$this->restrictionsVector[$k];

			if ($wayStartEndEdge->getWayId() < $restrictionContainer->getToWay()) {
				++$i;
				continue;
			}
			if ($wayStartEndEdge->getWayId() > $restrictionContainer->getToWay()) {
				++$k;
				continue;
			}

			$viaNodeValue = $restrictionContainer->getRestriction()->getViaNode()->getValue();
			$toNode  = $restrictionContainer->getRestriction()->getToNode();

			if ($wayStartEndEdge->getLastStart()->getValue() == $viaNodeValue) {
				$toNode->setValue($wayStartEndEdge->getLastTarget()->getValue());
			}
			elseif ($wayStartEndEdge->getLastTarget()->getValue() == $viaNodeValue) {
				$toNode->setValue($wayStartEndEdge->getLastStart()->getValue());
			}
			elseif ($wayStartEndEdge->getFirstStart()->getValue() == $viaNodeValue) {
				$toNode->setValue($wayStartEndEdge->getFirstTarget()->getValue());
			}
			elseif ($wayStartEndEdge->getFirstTarget()->getValue() == $viaNodeValue) {
				$toNode->setValue($wayStartEndEdge->getFirstStart()->getValue());
			}

			if (   $restrictionContainer->getRestriction()->getFromNode()->getValue() != NodeID::DEFAULT_VALUE
				&& $restrictionContainer->getRestriction()->getToNode()  ->getValue() != NodeID::DEFAULT_VALUE) {
				++$usableRestrictionsCounter;
			}

			++$k;
		}
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] writing street name index    ...');

		$this->dbh->query('delete from phpr_names');

		foreach ($this->nameVector as $name) {
			$this->dbh->query("insert into phpr_names (name) values ('$name')");
		}
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		LogUtil::info('usable restrictions: '.$usableRestrictionsCounter);

		$this->dbh->query('delete from phpr_nodes');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Confirming/Writing used nodes...');
		$i = $k = $counter = 0;
		$nodes = array();
		while (isset($this->usedNodeIDs[$i]) && isset($this->allNodes[$k])) {
			$usedNodeID = &$this->usedNodeIDs[$i];
			$node = &$this->allNodes[$k];

			if ($usedNodeID->getValue() < $node->getNodeId()->getValue()) {
				++$i;
				continue;
			}
			if ($usedNodeID->getValue() > $node->getNodeId()->getValue()) {
				++$k;
				continue;
			}
			if ($usedNodeID->getValue() == $node->getNodeId()->getValue()) {
				if ($counter >= 100) {
					$this->insertNodes($nodes);
					$nodes = array();
					$counter = 0;
				}
				$counter++;
				$nodes[] = "({$node->getNodeId()->getValue()}, {$node->getCoordinate()->getLat()}, {$node->getCoordinate()->getLon()})";
				++$i;
				++$k;
				++$usedNodeCounter;
			}
		}
		if ($counter > 0) {
			$this->insertNodes($nodes);
		}

		$this->dbh->query('delete from phpr_restrictions');

		for ($k = 0; $k < count($this->restrictionsVector); $k++) {
			if (   $this->restrictionsVector[$k]->getRestriction()->getFromNode()->getValue() != NodeID::DEFAULT_VALUE
				&& $this->restrictionsVector[$k]->getRestriction()->getToNode()  ->getValue() != NodeID::DEFAULT_VALUE) {
				$restriction = $this->restrictionsVector[$k]->getRestriction();
				$isOnly = ($restriction->getIsOnly()) ? 'true' : 'false';
				$this->dbh->query("insert into phpr_restrictions (via_node, to_node, from_node, is_only) values (
					(select id from phpr_nodes where osm_node_id = '{$restriction->getViaNode()->getValue()}'),
					(select id from phpr_nodes where osm_node_id = '{$restriction->getToNode()->getValue()}'),
					(select id from phpr_nodes where osm_node_id = '{$restriction->getFromNode()->getValue()}'),
					{$isOnly}
				)");
			}
		}

		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Sorting edges by start       ...');
		usort($this->allEdges, array('\\gentoid\\route\\InternalExtractorEdge', 'CmpEdgeByStartID'));
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Setting start coords         ...');

		$i = $k = 0;
		while(isset($this->allEdges[$i]) && isset($this->allNodes[$k])) {
			$edge = &$this->allEdges[$i];
			$node = &$this->allNodes[$k];

			if ($edge->getStart()->getValue() < $node->getNodeId()->getValue()) {
				++$i;
				continue;
			}
			if ($edge->getStart()->getValue() > $node->getNodeId()->getValue()) {
				++$k;
				continue;
			}
			if ($edge->getStart()->getValue() == $node->getNodeId()->getValue()) {
				$edge->getStartCoord()->setLat($node->getCoordinate()->getLat());
				$edge->getStartCoord()->setLon($node->getCoordinate()->getLon());
				++$i;
			}
		}
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Sorting edges by target      ...');
		usort($this->allEdges, array('\\gentoid\\route\\InternalExtractorEdge', 'CmpEdgeByTargetID'));
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] Setting target coords        ...');
		$i = $k = $counter = 0;
		$values = array();
		$this->dbh->query('delete from phpr_edges');
		while (isset($this->allEdges[$i]) && isset($this->allNodes[$k])) {
			$edge = &$this->allEdges[$i];
			$node = &$this->allNodes[$k];

			if ($edge->getTarget()->getValue() < $node->getNodeId()->getValue()) {
				++$i;
				continue;
			}
			if ($edge->getTarget()->getValue() > $node->getNodeId()->getValue()) {
				++$k;
				continue;
			}
			if ($edge->getTarget()->getValue() == $node->getNodeId()->getValue()) {
				if (   $edge->getStartCoord()->getLat() != Coordinate::DEFAULT_VALUE
					&& $edge->getStartCoord()->getLon() != Coordinate::DEFAULT_VALUE) {
					$edge->getTargetCoord()->setLat($node->getCoordinate()->getLat());
					$edge->getTargetCoord()->setLon($node->getCoordinate()->getLon());

					$distance = Coordinate::ApproximateDistance($edge->getStartCoord(), $node->getCoordinate());
					$weight = ($distance * 10) / ($edge->getSpeed() * 3.6);
					$weight = max(1, round($edge->getIsDurationSet() ? $edge->getSpeed() : $weight), PHP_ROUND_HALF_UP);
					$distance = max(1, $distance);
					$direction = 'false';

					switch($edge->getDirection()->getValue()) {
//						case Direction::NOT_SURE:
//						case Direction::BIDIRECTIONAL:
//						$direction = 'false';
//							break;
						case Direction::ONEWAY:
						case Direction::OPPOSITE:
							$direction = 'true';
					}

					++$usedEdgeCounter;
					$isRoundabout = ($edge->getIsRoundabout()) ? 'true' : 'false';
					$ignoreInGrid = ($edge->getIgnoreInGrid()) ? 'true' : 'false';
					$isAccessRestricted = ($edge->getIsAccessRestricted()) ? 'true' : 'false';
					$isContraFlow = ($edge->getIsContraFlow()) ? 'true' : 'false';

					if ($counter >= 200) {
						$this->insertEdges($values);
						$counter = 0;
						$values = array();
					}
					$values[] = "((select id from phpr_nodes where osm_node_id = '{$edge->getStart()->getValue()}'), (select id from phpr_nodes where osm_node_id = '{$edge->getTarget()->getValue()}'), {$distance}, {$weight}, {$direction}, {$edge->getType()}, (select  id from phpr_names where name = '{$edge->getName()}'), {$isRoundabout}, {$ignoreInGrid}, {$isAccessRestricted}, {$isContraFlow}, {$edge->getOsmId()})";
					$counter++;
				}
				++$i;
			}
		}
		if ($counter > 0) {
			$this->insertEdges($values);
		}
		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		$time = microtime(true);
		LogUtil::infoAsIs('[extractor] setting number of edges      ...');

		$timeDiff = microtime(true) - $time;
		LogUtil::infoAsIs('ok, after '.$timeDiff.'s');

		LogUtil::info('Processed '.$usedNodeCounter.' nodes and '.$usedEdgeCounter.' edges');
	}

	protected function insertNodes(array $values) {
		if (empty($values)) {
			return;
		}
		$values = implode(", ", $values);
		$this->dbh->query("insert into phpr_nodes (osm_node_id, lat, lon) values {$values}");
	}

	protected function insertEdges(array $values) {
		if (empty($values)) {
			return;
		}
		$values = implode(", ", $values);
		$this->dbh->query("insert into phpr_edges (node_id_start, node_id_target, distance, weight, direction, type, name_id, is_roundabout, ignore_in_grid, is_access_restricted, is_contra_flow, osm_way_id) values {$values}");
//		var_dump($this->dbh->errorInfo());
	}

	/**
	 * @return \gentoid\route\Node[]
	 */
	public function getAllNodes() {
		return $this->allNodes;
	}

	/**
	 * @param \gentoid\route\Node $node
	 */
	public function addNode(\gentoid\route\Node $node) {
		$this->allNodes[] = $node;
	}

	/**
	 * @return \gentoid\route\InternalExtractorEdge[]
	 */
	public function getAllEdges() {
		return $this->allEdges;
	}

	/**
	 * @param \gentoid\route\InternalExtractorEdge $edge
	 */
	public function addEdge(\gentoid\route\InternalExtractorEdge $edge) {
		$this->allEdges[] = $edge;
	}

	/**
	 * @return \gentoid\route\RawRestrictionContainer[]
	 */
	public function getRestrictionsVector() {
		return $this->restrictionsVector;
	}

	/**
	 * @param \gentoid\route\RawRestrictionContainer $restriction
	 */
	public function addRestriction(\gentoid\route\RawRestrictionContainer $restriction) {
		$this->restrictionsVector[] = $restriction;
	}

	/**
	 * @return \string[]
	 */
	public function getNameVector() {
		return $this->nameVector;
	}

	/**
	 * @param \string $name
	 */
	public function addName($name) {
		$this->nameVector[] = $name;
	}

	/**
	 * @return \gentoid\route\NodeID[]
	 */
	public function getUsedNodeIDs() {
		return $this->usedNodeIDs;
	}

	/**
	 * @param \gentoid\route\NodeID $usedNodeID
	 */
	public function addUsedNodeID(\gentoid\route\NodeID $usedNodeID) {
		$this->usedNodeIDs[] = $usedNodeID;
	}

	/**
	 * @return \gentoid\route\WayIDStartAndEndEdge[]
	 */
	public function getWayStartEndVector() {
		return $this->wayStartEndVector;
	}

	/**
	 * @param \gentoid\route\WayIDStartAndEndEdge $wayStartEndEdge
	 */
	public function addWayStartEndVector(\gentoid\route\WayIDStartAndEndEdge $wayStartEndEdge) {
		$this->wayStartEndVector[] = $wayStartEndEdge;
	}

}
// todo: write asserts
