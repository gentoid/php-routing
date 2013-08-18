<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


use gentoid\route\NodeID;

class NodeBasedEdge {

	/** @var \gentoid\route\NodeID */
	protected $source;

	/** @var \gentoid\route\NodeID */
	protected $target;

	/** @var \gentoid\route\NodeID */
	protected $name;

	/** @var int */
	protected $weight;

	/** @var bool */
	protected $forward;

	/** @var bool */
	protected $backward;

	/** @var int */
	protected $type;

	/** @var bool */
	protected $roundabout;

	/** @var bool */
	protected $ignoreInGrid;

	/** @var bool */
	protected $accessRestricted;

	/** @var bool */
	protected $contraFlow;

	public function __construct() {
		$this->source = new NodeID();
		$this->target = new NodeID();
		$this->name   = new NodeID();
	}

	/**
	 * @param NodeID $s
	 * @param NodeID $t
	 * @param NodeID $n
	 * @param int $w
	 * @param boolean $f
	 * @param boolean $b
	 * @param int $ty
	 * @param boolean $ra
	 * @param boolean $ig
	 * @param boolean $ar
	 * @param boolean $cf
	 * @throws \Exception
	 */
	public function set(NodeID $s, NodeID $t, NodeID $n, $w, $f, $b, $ty, $ra, $ig,$ar, $cf) {
		if ($ty < 0) {
			throw new \Exception('Negative edge type');
		}
		$this->source = $s;
		$this->target = $t;
		$this->name = $n;
		$this->weight = intval($w);
		$this->forward = (bool)$f;
		$this->backward = (bool)$b;
		$this->type = intval($ty);
		$this->roundabout = (bool)$ra;
		$this->ignoreInGrid = (bool)$ig;
		$this->accessRestricted = (bool)$ar;
		$this->contraFlow = (bool)$cf;
	}

	/**
	 * @param NodeBasedEdge $e
	 * @return bool
	 */
	public function lessThan(NodeBasedEdge $e) {
		if ($this->source->isEqual($e->getSource())) {
			if ($this->target->isEqual($e->getTarget())) {
				if ($this->weight == $e->getWeight()) {
					return $this->forward && $this->backward && (!$e->getForward() || !$e->getBackward());
				}
				return $this->weight < $e->getWeight();
			}
			return $this->target->lessThan($e->getTarget());
		}
		return $this->source->lessThan($e->getSource());
	}

	public function isLocatable() {
		return $this->type != 14;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param \gentoid\route\NodeID $source
	 * @return NodeBasedEdge
	 */
	public function setSource(NodeID $source) {
		$this->source = $source;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @param \gentoid\route\NodeID $target
	 * @return NodeBasedEdge
	 */
	public function setTarget(NodeID $target) {
		$this->target = $target;
		return $this;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param \gentoid\route\NodeID $name
	 * @return NodeBasedEdge
	 */
	public function setName(NodeID $name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getWeight() {
		return $this->weight;
	}

	/**
	 * @param int $weight
	 * @return NodeBasedEdge
	 */
	public function setWeight($weight) {
		$this->weight = intval($weight);
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getForward() {
		return $this->forward;
	}

	/**
	 * @param boolean $forward
	 * @return NodeBasedEdge
	 */
	public function setForward($forward) {
		$this->forward = (bool)$forward;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getBackward() {
		return $this->backward;
	}

	/**
	 * @param boolean $backward
	 * @return NodeBasedEdge
	 */
	public function setBackward($backward) {
		$this->backward = (bool)$backward;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param int $type
	 * @return NodeBasedEdge
	 */
	public function setType($type) {
		$this->type = intval($type);
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getRoundabout() {
		return $this->roundabout;
	}

	/**
	 * @param boolean $roundabout
	 * @return NodeBasedEdge
	 */
	public function setRoundabout($roundabout) {
		$this->roundabout = (bool)$roundabout;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIgnoreInGrid() {
		return $this->ignoreInGrid;
	}

	/**
	 * @param boolean $ignoreInGrid
	 * @return NodeBasedEdge
	 */
	public function setIgnoreInGrid($ignoreInGrid) {
		$this->ignoreInGrid = (bool)$ignoreInGrid;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getAccessRestricted() {
		return $this->accessRestricted;
	}

	/**
	 * @param boolean $accessRestricted
	 * @return NodeBasedEdge
	 */
	public function setAccessRestricted($accessRestricted) {
		$this->accessRestricted = (bool)$accessRestricted;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getContraFlow() {
		return $this->contraFlow;
	}

	/**
	 * @param boolean $contraFlow
	 * @return NodeBasedEdge
	 */
	public function setContraFlow($contraFlow) {
		$this->contraFlow = (bool)$contraFlow;
		return $this;
	}

}
