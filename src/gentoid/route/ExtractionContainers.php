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
		NodeID::unique($this->usedNodeIDs);
		usort($this->allNodes, array('Node', 'CmpNodeByID'));
		usort($this->wayStartEndVector, array('WayIDStartAndEndEdge', 'CmpWayByID'));
		usort($this->restrictionsVector, array('RawRestrictionContainer', 'CmpRestrictionContainerByFrom'));

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

		usort($this->restrictionsVector, array('RawRestrictionContainer', 'CmpRestrictionContainerByTo'));

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
		// todo
		fclose($fd);
	}

}
