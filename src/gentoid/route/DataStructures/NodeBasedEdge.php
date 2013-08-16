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
	public function __construct(NodeID $s, NodeID $t, NodeID $n, $w, $f, $b, $ty, $ra, $ig,$ar, $cf) {
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
	 * @return \gentoid\route\NodeID
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @return \gentoid\route\NodeID
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getWeight() {
		return $this->weight;
	}

	/**
	 * @return boolean
	 */
	public function getForward() {
		return $this->forward;
	}

	/**
	 * @return boolean
	 */
	public function getBackward() {
		return $this->backward;
	}

	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return boolean
	 */
	public function getRoundabout() {
		return $this->roundabout;
	}

	/**
	 * @return boolean
	 */
	public function getIgnoreInGrid() {
		return $this->ignoreInGrid;
	}

	/**
	 * @return boolean
	 */
	public function getAccessRestricted() {
		return $this->accessRestricted;
	}

	/**
	 * @return boolean
	 */
	public function getContraFlow() {
		return $this->contraFlow;
	}

}
