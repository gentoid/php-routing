<?php
/**
 * @date: 8/12/13
 * @uathor: viktor
 */

namespace gentoid\route\DataStructures;


use gentoid\utils\LogUtil;

class Percent {

	protected $maxValue;

	protected $currentValue;

	protected $internalPercent;

	protected $nextThreshold;

	protected $lastPercent;

	protected $step;

	public function __construct($maxValue, $step = 5) {
		$this->reinit($maxValue, $step);
	}

	public function reInit($maxValue, $step = 5) {
		$this->maxValue = $maxValue;
		$this->currentValue = 0;
		$this->internalPercent = $maxValue / 100;
		$this->nextThreshold = $this->internalPercent;
		$this->lastPercent = 0;
		$this->step = $step;
	}

	public function printStatus($currentValue) {
		if ($currentValue >= $this->nextThreshold) {
			$this->nextThreshold += $this->internalPercent;
			$this->printPercent( $this->currentValue / $this->maxValue * 100);
		}
		if ($this->currentValue + 1 == $this->maxValue) {
			LogUtil::infoAsIs(' 100%');
		}
	}

	public function printIncrement() {
		$this->printStatus(++$this->currentValue);
	}

	public function printAddition($addition) {
		$this->currentValue += $addition;
		$this->printStatus($this->currentValue);
	}

	protected function printPercent($percent) {
		while ($percent >= $this->lastPercent + $this->step) {
			$this->lastPercent += $this->step;
			if ($this->lastPercent % 10 == 0) {
				LogUtil::infoAsIs(' '.$this->lastPercent.'% ');
			}
			else {
				LogUtil::infoAsIs('.');
			}
		}
	}

}
