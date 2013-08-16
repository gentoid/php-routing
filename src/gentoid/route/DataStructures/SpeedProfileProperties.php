<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\DataStructures;


class SpeedProfileProperties {

	/** @var int */
	protected $trafficSignalPenalty = 0;

	/** @var int */
	protected $uTurnPenalty = 0;

	/** @var bool */
	protected $hasTurnPenaltyFunction = false;

	/**
	 * @return int
	 */
	public function getTrafficSignalPenalty() {
		return $this->trafficSignalPenalty;
	}

	/**
	 * @param int $trafficSignalPenalty
	 * @return SpeedProfileProperties
	 */
	public function setTrafficSignalPenalty($trafficSignalPenalty) {
		$this->trafficSignalPenalty = intval($trafficSignalPenalty);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUTurnPenalty() {
		return $this->uTurnPenalty;
	}

	/**
	 * @param int $uTurnPenalty
	 * @return SpeedProfileProperties
	 */
	public function setUTurnPenalty($uTurnPenalty) {
		$this->uTurnPenalty = intval($uTurnPenalty);
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getHasTurnPenaltyFunction() {
		return $this->hasTurnPenaltyFunction;
	}

	/**
	 * @param boolean $hasTurnPenaltyFunction
	 * @return SpeedProfileProperties
	 */
	public function setHasTurnPenaltyFunction($hasTurnPenaltyFunction) {
		$this->hasTurnPenaltyFunction = (bool)$hasTurnPenaltyFunction;
		return $this;
	}

}
