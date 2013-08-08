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

	/**
	 * @param string $outputFileName
	 * @param string $restrictionsFileName
	 */
	public function prepareData($outputFileName, $restrictionsFileName) {
		$usedNodeCounter = 0;
		$usedEdgeCounter = 0;

		usort($this->usedNodeIDs, array('\\gentoid\\route\\NodeID', 'cmp'));
		NodeID::unique($this->usedNodeIDs);
		usort($this->allNodes, array('\\gentoid\\route\\Node', 'CmpNodeByID'));
		usort($this->wayStartEndVector, array('\\gentoid\\route\\WayIDStartAndEndEdge', 'CmpWayByID'));
		usort($this->restrictionsVector, array('\\gentoid\\route\\RawRestrictionContainer', 'CmpRestrictionContainerByFrom'));

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

		usort($this->restrictionsVector, array('\\gentoid\\route\\RawRestrictionContainer', 'CmpRestrictionContainerByTo'));

		$usableRestrictionsCounter = 0;

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

		$fd = fopen($restrictionsFileName, 'w');
		fwrite($fd, pack('L', $usableRestrictionsCounter));

		for ($k = 0; $k < count($this->restrictionsVector); $k++) {
			if (   $this->restrictionsVector[$k]->getRestriction()->getFromNode()->getValue() != NodeID::DEFAULT_VALUE
				&& $this->restrictionsVector[$k]->getRestriction()->getToNode()  ->getValue() != NodeID::DEFAULT_VALUE) {
				fwrite($fd, $this->restrictionsVector[$k]->getRestriction()->pack());
			}
		}

		fclose($fd);

		$fd = fopen($outputFileName, 'w');
		fwrite($fd, pack('L', $usedNodeCounter));

		$i = $k = 0;
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
				fwrite($fd, $node->pack());
				++$i;
				++$k;
				++$usedNodeCounter;
			}
		}

		$pos = ftell($fd);
		fseek($fd, 0);
		fwrite($fd, pack('L', $usedNodeCounter));
		fseek($fd, $pos);

		usort($this->allEdges, array('\\gentoid\\route\\InternalExtractorEdge', 'CmpEdgeByStartID'));

		fwrite($fd, pack('L', $usedNodeCounter));

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

		usort($this->allEdges, array('\\gentoid\\route\\InternalExtractorEdge', 'CmpEdgeByTargetID'));

		$i = $k = 0;
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
					$zero = '0';
					$one  = '1';

					fwrite($fd, $edge->getStart() ->pack());
					fwrite($fd, $edge->getTarget()->pack());
					fwrite($fd, pack('L', $distance));

					switch($edge->getDirection()->getValue()) {
						case Direction::NOT_SURE:
						case Direction::BIDIRECTIONAL:
							fwrite($fd, pack('a1', $zero));
							break;
						case Direction::ONEWAY:
						case Direction::OPPOSITE:
							fwrite($fd, pack('a1', $one));
					}

					fwrite($fd, pack('L', $weight));
					fwrite($fd, $edge->pack());
					++$usedEdgeCounter;
				}
				++$i;
			}
		}

		fseek($fd, $pos);
		fwrite($fd, pack('L', $usedEdgeCounter));
		fclose($fd);

		$nameOutFileName = $outputFileName . '.names';
		$fd = fopen($nameOutFileName, 'w');
		fwrite($fd, pack('L', count($this->nameVector)));
		foreach ($this->nameVector as $name) {
			fwrite($fd, pack('L', strlen($name)));
			fwrite($fd, pack('a'.strlen($name), $name));
		}
		fclose($fd);
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
