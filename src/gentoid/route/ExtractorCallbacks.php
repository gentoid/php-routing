<?php
/**
 * @date: 8/7/13
 * @uathor: viktor
 */

namespace gentoid\route;


class ExtractorCallbacks {

	/** @var string[] */
	protected $strings = array();

	/** @var ExtractionContainers */
	protected $external;

	/**
	 * @param Node $n
	 */
	public function nodeFunction(Node $n) {
		if ($n->getCoordinate()->getLat() <= 85 && $n->getCoordinate()->getLat() >= -85) {
			$this->external->addNode($n);
		}
	}

	/**
	 * @param RawRestrictionContainer $r
	 */
	public function restrictionFunction(RawRestrictionContainer $r) {
		$this->external->addRestriction($r);
	}

	/**
	 * @param ExtractionWay $w
	 */
	public function wayFunction(ExtractionWay $w) {
		if ($w->getSpeed() > 0 || $w->getDuration() > 0) {
			if ($w->getId() == -1) {
				return;
			}

			if ($w->getDuration() > 0) {
				$w->setSpeed($w->getDuration() / (count($w->getPath()) - 1));
			}

			if ($w->getSpeed() < 0) {
				return;
			}

			if ($key = array_search($w->getName(), $this->strings)) {
				$w->setNameId($key);
			}
			else {
				$id = count($this->external->getNameVector());
				$w->setNameId($id);
				$this->strings[$id] = $w->getName();
			}
			if ($w->getDirection()->getValue() == Direction::OPPOSITE) {
				$w->setPath(array_reverse($w->getPath()));
				$w->getDirection()->setValue(Direction::ONEWAY);
			}

			$split_bidirectional_edge = ($w->getBackwardSpeed() > 0) && ($w->getSpeed() != $w->getBackwardSpeed());

			$osmId = $w->getId();
			$path  = $w->getPath();
			$type  = $w->getType();
			$directionValue = ($split_bidirectional_edge) ? Direction::ONEWAY : $w->getDirection()->getValue();
			if ($directionValue === null) {
				$directionValue = Direction::NOT_SURE;
			}
			$speed = $w->getSpeed();
			$nameId = $w->getNameId();
			$roundabout = $w->getRoundabout();
			$ignoreInGrid = $w->getIgnoreInGrid();
			$isDurationSet = ($w->getDuration() > 0);
			$isAccessRestricted = $w->getIsAccessRestricted();
			for ($i = 0; $i < count($w->getPath()) - 1; $i++) {
				$direction = new Direction($directionValue);
				$edge = new InternalExtractorEdge();
				$edge->setOsmId($osmId)->setStart($path[$i])->setTarget($path[$i + 1])->setType($type)
					->setDirection($direction)->setSpeed($speed)->setNameId($nameId)->setIsRoundabout($roundabout)
					->setIgnoreInGrid($ignoreInGrid)->setIsDurationSet($isDurationSet)->setIsAccessRestricted($isAccessRestricted);
				$this->external->addEdge($edge);
				$this->external->addUsedNodeID($path[$i]);
			}
			$this->external->addUsedNodeID($path[count($path) - 1]);

			$edge = new WayIDStartAndEndEdge();
			$edge->setWayId($w->getId())->setFirstStart($path[0])->setFirstTarget($path[1])
				->setLastStart($path[count($path) - 2])->setLastTarget($path[count($path) - 1]);
			$this->external->addWayStartEndVector($edge);

			if ($split_bidirectional_edge) {
				$w->setPath(array_reverse($w->getPath()));
				$path  = $w->getPath();
				$speed = $w->getBackwardSpeed();
				$isContraFlow = ($w->getDirection() == Direction::ONEWAY);
				for ($i = 0; $i < count($path) - 1; $i++) {
					$edge = new InternalExtractorEdge();
					$direction = new Direction(Direction::ONEWAY);
					$edge->setOsmId($osmId)->setStart($path[$i])->setTarget($path[$i + 1])->setType($type)
						->setDirection($direction)->setSpeed($speed)->setNameId($nameId)->setIsRoundabout($roundabout)
						->setIgnoreInGrid($ignoreInGrid)->setIsDurationSet($isDurationSet)
						->setIsAccessRestricted($isAccessRestricted)->setIsContraFlow($isContraFlow);
					$this->external->addEdge($edge);
				}
				$edge = new WayIDStartAndEndEdge();
				$edge->setWayId($w->getId())->setFirstStart($path[0])->setFirstTarget($path[1])
					->setLastStart($path[count($path) - 2])->setLastTarget($path[count($path) - 1]);
				$this->external->addWayStartEndVector($edge);
			}
		}
	}

	/**
	 * @return \string[]
	 */
	public function getStrings() {
		return $this->strings;
	}

	/**
	 * @param \string[] $strings
	 * @return ExtractorCallbacks
	 */
	public function setStrings(array $strings) {
		$this->strings = $strings;
		return $this;
	}

	/**
	 * @return \gentoid\route\ExtractionContainers
	 */
	public function getExternal() {
		return $this->external;
	}

	/**
	 * @param \gentoid\route\ExtractionContainers $external
	 * @return ExtractorCallbacks
	 */
	public function setExternal(\gentoid\route\ExtractionContainers $external) {
		$this->external = $external;
		return $this;
	}


}
 