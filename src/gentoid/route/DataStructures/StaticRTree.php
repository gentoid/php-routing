<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;
use gentoid\utils\LogUtil;

class StaticRTree {

	/** @var TreeNode[] */
	protected $mSearchTree = array();

	/** @var int */
	protected $mElementCount;

	public function init2() {
		// todo: get TreeNodes from DB
		// todo: get leaf count (from DB?)
	}

	/**
	 * @param TreeNode[] $inputDataVector
	 */
	public function init3(array $inputDataVector) {
		$this->mElementCount = count($inputDataVector);

		LogUtil::infoAsIs('Constructing r-tree of '.$this->mElementCount.' elements');

		$inputWrapperVector = array();

		for ($elementCounter = '0'; bccomp($elementCounter, $this->mElementCount) == -1; bcadd($elementCounter, '1')) {
			$inputWrapperVector[$elementCounter] = new WrappedInputElement($elementCounter);
		}
	}

	/**
	 * @param FixedPointCoordinate $inputCoordinate
	 * @param PhantomNode $resultPhantomNode
	 * @param int $zoomLevel
	 * @return bool
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
		$currentMinDist = $this->mSearchTree[0]->getMinimumBoundingRectangle()->getMinDist($inputCoordinate);
		$qc = new QueryCandidate();
		$qc->setNodeId(new NodeID('0'), $currentMinDist);
		array_push($traversalQueue, $qc);

		while(!empty($traversalQueue)) {
			/** @var QueryCandidate $currentQueryNode */
			$currentQueryNode = array_pop($traversalQueue);
			++$exploredTreeNodesCount;
			$pruneDownward = ($currentQueryNode->getMinDist() >= $minMaxDist);
			$pruneUpward   = ($currentQueryNode->getMinDist() >= $minDist);
			if (!$pruneDownward && !$pruneUpward) {
				/** @var TreeNode $currentTreeNode */
				$currentTreeNode = $this->mSearchTree[$currentQueryNode->getNodeId()->getValue()];
				if ($currentTreeNode->getChildIsOnDisk()) {
					$currentLeafNode = new LeafNode();
					$this->loadLeafFromDisk($currentTreeNode->getChildren()[0], $currentLeafNode);
					++$IOCount;
					for ($i = 0; $i < $currentLeafNode->getObjectCount(); ++$i) {
						$currentEdge = $currentLeafNode->getObject($i);
						if (!$ignoreTinyComponents && $currentEdge->getBelongsToTinyComponent()) {
							continue;
						}
						if ($currentEdge->isIgnored()) {
							continue;
						}

						$currentRatio = 0.0;
						$currentPerpendicularDistance = $this->computePerpendicularDistance(
							$inputCoordinate,
							$currentEdge->getCoordinate1(),
							$currentEdge->getCoordinate2(),
							$nearest,
							$currentRatio
						);

						if ($currentPerpendicularDistance < $minDist && !$this->doubleEpsilonCompare($currentPerpendicularDistance, $minDist)) {
							$minDist = $currentPerpendicularDistance;
							$resultPhantomNode->getEdgeBasedNode()->setValue($currentEdge->getId()->getValue());
							$resultPhantomNode->getNodeBasedEdgeNameID()->setValue($currentEdge->getNameId()->getValue());;
							$resultPhantomNode->setWeight1($currentEdge->getWeight());
							$resultPhantomNode->setWeight2(PHP_INT_MAX);
							$resultPhantomNode->setLocation($nearest);

							$currentStartCoordinate->setLat($currentEdge->getCoordinate1()->getLat())
								->setLon($currentEdge->getCoordinate1()->getLon());
							$currentEndCoordinate->setLat($currentEdge->getCoordinate2()->getLat())
								->setLon($currentEdge->getCoordinate2()->getLon());

							$nearestEdge = $currentEdge;
							$foundANearestEdge = true;
						}
						elseif ($this->doubleEpsilonCompare($currentPerpendicularDistance, $minDist)
							&& 1 == abs($currentEdge->getId()->getValue() - $resultPhantomNode->getEdgeBasedNode()->getValue())
							&& $this->coordinatesAreEquivalent(
								$currentStartCoordinate,
								$currentEdge->getCoordinate1(),
								$currentEdge->getCoordinate2(),
								$currentEndCoordinate
							)
						) {
							$resultPhantomNode->setWeight2($currentEdge->getWeight());
							if ($currentEdge->getId()->lessThan($resultPhantomNode->getEdgeBasedNode())) {
								$resultPhantomNode->getEdgeBasedNode()->setValue($currentEdge->getId()->getValue());
								$tmpWeight = $resultPhantomNode->getWeight1();
								$resultPhantomNode->setWeight1($resultPhantomNode->getWeight2());
								$resultPhantomNode->setWeight2($tmpWeight);

								$tmpCoordinate = $currentStartCoordinate;
								$currentStartCoordinate = $currentEndCoordinate;
								$currentEndCoordinate = $tmpCoordinate;
							}
						}
					}
				}
				else {
					for ($i = 0; $i < $currentTreeNode->getChildCount(); ++$i) {
						$childId = $currentTreeNode->getChildren()[$i];
						/** @var TreeNode $childTreeNode */
						$childTreeNode = $this->mSearchTree[$childId];
						$childRectangle = $childTreeNode->getMinimumBoundingRectangle();
						$currentMinDist = $childRectangle->getMinDist($inputCoordinate);
						$currentMinMaxDist = $childRectangle->getMinMaxDist($inputCoordinate);
						if ($currentMinMaxDist < $minMaxDist) {
							$minMaxDist = $currentMinMaxDist;
						}
						if ($currentMinDist > $minMaxDist || $currentMinDist > $minDist) {
							continue;
						}
						$qc = new QueryCandidate();
						$qc->setNodeId(new NodeID($childId))->setMinDist($currentMinDist);
						array_push($traversalQueue, $qc);
					}
				}
			}
		}

		$ratio = $foundANearestEdge ? min(1.0, FixedPointCoordinate::ApproximateDistance($currentStartCoordinate, $resultPhantomNode->getLocation()) / FixedPointCoordinate::ApproximateDistance($currentStartCoordinate, $currentEndCoordinate)) : 0;
		$resultPhantomNode->setWeight1($resultPhantomNode->getWeight1() * $ratio);
		if (PHP_INT_MAX != $resultPhantomNode->getWeight2()) {
			$resultPhantomNode->setWeight2($resultPhantomNode->getWeight2() * ( 1.0 - $ratio));
		}
		$resultPhantomNode->setRatio($ratio);

		if (abs($inputCoordinate->getLon() - $resultPhantomNode->getLocation()->getLon()) == 1) {
			$resultPhantomNode->getLocation()->setLon($inputCoordinate->getLon());
		}
		if (abs($inputCoordinate->getLat() - $resultPhantomNode->getLocation()->getLat()) == 1) {
			$resultPhantomNode->getLocation()->setLat($inputCoordinate->getLat());
		}

		return $foundANearestEdge;
	}

	protected function loadLeafFromDisk($leafId, LeafNode $resultNode) {
		// todo: get LeafNode from DB
	}

	/**
	 * @param FixedPointCoordinate $inputPoint
	 * @param FixedPointCoordinate $source
	 * @param FixedPointCoordinate $target
	 * @param FixedPointCoordinate $nearest
	 * @param float $r
	 * @return float
	 */
	protected function computePerpendicularDistance(FixedPointCoordinate $inputPoint, FixedPointCoordinate $source, FixedPointCoordinate $target, FixedPointCoordinate $nearest, &$r) {
		$x = $inputPoint->getLat();
		$y = $inputPoint->getLon();
		$a = $source->getLat();
		$b = $source->getLon();
		$c = $target->getLat();
		$d = $target->getLon();

		if (abs($a - $c) > EPSILON) {
			$m = ($d - $b) / ($c - $a);
			$p = (($x + ($m * $y)) + ($m * $m * $a - $m * $b)) / (1.0 + $m * $m);
			$q = $b + $m * ($p - $a);
		}
		else {
			$p = $c;
			$q = $y;
		}

		if (abs($c) <= EPSILON) {
			$r = $target->isEqual($inputPoint) ? 1.0 : 0.0;
		}
		else {
			$nY = ($d * $p - $c * $q) / ($a * $d - $b * $c);
			$r = ($p - $nY * $a) / $c;
		}


		if ($r <= 0.0) {
			$nearest->setLat($source->getLat())->setLon($source->getLon());
			return ($b - $y) * ($b - $y) + ($a - $x) * ($a - $x);
		}
		elseif ($r >= 1.0) {
			$nearest->setLat($target->getLat())->setLon($target->getLon());
			return ($d - $y) * ($d - $y) + ($c - $x) * ($c - $x);
		}

		$nearest->setLat($p)->setLon($q);
		return ($p - $x) * ($p - $x) + ($q - $y) * ($q - $y);
	}

	/**
	 * @param FixedPointCoordinate $a
	 * @param FixedPointCoordinate $b
	 * @param FixedPointCoordinate $c
	 * @param FixedPointCoordinate $d
	 * @return bool
	 */
	protected function coordinatesAreEquivalent(FixedPointCoordinate $a, FixedPointCoordinate$b, FixedPointCoordinate$c, FixedPointCoordinate $d) {
		return ($a->isEqual($b) && $c->isEqual($d)) || ($a->isEqual($c) && $b->isEqual($d)) || ($a->isEqual($d) && $b->isEqual($c));
	}

	/**
	 * @param float $d1
	 * @param float $d2
	 * @return bool
	 */
	protected function doubleEpsilonCompare($d1, $d2) {
		return abs($d1 - $d2) < EPSILON;
	}

}
