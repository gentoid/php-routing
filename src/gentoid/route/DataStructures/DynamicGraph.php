<?php
/**
 * @author viktor
 * @date 18.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class DynamicGraph {

	/** @var int */
	protected $mNumNodes;

	/** @var int */
	protected $mNumEdges;

	/** @var array */
	protected $mNodes = array();

	/** @var array */
	protected $mEdges = array();

	/**
	 * @param int $nodes
	 * @param InputEdge[] $graph
	 */
	public function __construct($nodes, array $graph = array()) {
		$this->mNumNodes = $nodes;
		$this->mNumEdges = count($graph);

		if (empty($graph)) {
			return;
		}

		$edge = 0;
		$position = 0;

		for ($node = 0; $node < $this->mNumNodes; $node++) {
			$lastEdge = $edge;
			while ($edge < $this->mNumEdges && $graph[$edge]->getSource()->isEqualInt($node)) {
				$edge++;
			}
			$this->mNodes[$node]['firstEdge'] = $position;
			$this->mNodes[$node]['edges'] = $edge - $lastEdge;
			$position += $this->mNodes[$node]['edges'];
		}
		$tmpNode = end($this->mNodes);
		$tmpNode['firstEdge'] = $position;
		$edge = 0;
		for ($node = 0; $node < $this->mNumNodes; $node++) {
			for ($i = $this->mNodes[$node]['firstEdge'], $e = $this->mNodes[$node]['firstEdge'] + $this->mNodes[$node]['edges']; $i != $e; $i++) {
				$this->mEdges[$i]['target'] = $graph[$edge]->getTarget();
				$this->mEdges[$i]['data']   = $graph[$edge]->getData();
				$edge++;
			}
		}
	}

	/**
	 * @return int
	 */
	public function getNumberOfNodes() {
		return $this->mNumNodes;
	}

	/**
	 * @return int
	 */
	public function getNumberOfEdges() {
		return $this->mNumEdges;
	}

	/**
	 * @param int $n
	 * @return int
	 */
	public function getOutDegree($n) {
		return $this->mNodes[$n]['edges'];
	}

	/**
	 * @param int $e
	 * @return NodeID
	 */
	public function getTarget($e) {
		return $this->mEdges[$e]['target'];
	}

	/**
	 * @param int $e
	 * @return NodeBasedEdgeData
	 */
	public function getEdgeData($e) {
		return $this->mEdges[$e]['data'];
	}

	/**
	 * @param int $n
	 * @return int
	 */
	public function beginEdges($n) {
		return $this->mNodes[$n]['firstEdge'];
	}

	/**
	 * @param int $n
	 * @return int
	 */
	public function endEdges($n) {
		return intval($this->mNodes[$n]['firstEdge']) + intval($this->mNodes[$n]['edges']);
	}

	public function insertEdge($n) {
		//
	}

} 
