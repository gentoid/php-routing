<?php

namespace gentoid\route\profiles;


use gentoid\route\Direction;
use gentoid\route\ExtractionHelper;

class CarProfile extends BasicProfile {

	protected $barrier_whitelist = array('cattle_grid', 'border_control', 'toll_booth',
		'sally_port', 'gate', 'no');

	protected $access_tag_whitelist = array('yes', 'motorcar', 'motor_vehicle',
		'vehicle', 'permissive', 'designated');

	protected $access_tag_blacklist = array('no', 'private', 'agricultural', 'forestry');

	protected $access_tag_restricted = array('destination', 'delivery');

	protected $access_tags = array('motorcar', 'motor_vehicle', 'vehicle');

	protected $access_tags_hierarchy = array('motorcar', 'motor_vehicle', 'vehicle', 'access');

	protected $service_tag_restricted = array('parking_aisle');

	protected $ignore_in_grid = array('ferry');

	protected $restriction_exception_tags = array('motorcar', 'motor_vehicle', 'vehicle');

	protected $speed_profile = array(
		'motorway' => 90,
		'motorway_link' => 75,
		'trunk' => 85,
		'trunk_link' => 70,
		'primary' => 65,
		'primary_link' => 60,
		'secondary' => 55,
		'secondary_link' => 50,
		'tertiary' => 40,
		'tertiary_link' => 30,
		'unclassified' => 25,
		'residential' => 25,
		'living_street' => 10,
		'service' => 15,
//		'track' => 5,
		'ferry' => 5,
		'shuttle_train' => 10,
		'default' => 50,
	);

	protected $take_minimum_of_speeds = false;

	protected $obey_oneway = true;

	protected $obey_bollards = true;

	protected $use_restrictions = true;

	protected $ignore_areas = true;

	protected $traffic_signal_penalty = 2;

	protected $u_turn_penalty = 20;

	/**
	 * @param string $speed
	 * @return int|number
	 */
	protected function parseMaxspeed($speed) {
		if (is_string($speed) && is_int($n = intval($speed))) {
			if (is_int(strpos('mph',   $speed)) || is_int(strpos('mp/h', $speed))) {
				return abs($n * 1609 / 1000);
			}
		}

		return 0;
	}

	/**
	 * @param \gentoid\route\ImportNode $n
	 * @return void
	 */
	public function nodeFunction(\gentoid\route\ImportNode $n) {
		$barrier = $n->findValByKey('barrier');
		$access  = null;
		foreach ($this->access_tags_hierarchy as $tag) {
			if ($access = $n->findValByKey($tag)) {
				break;
			}
		}
		if (   ($access  &&  in_array($access,  $this->access_tag_blacklist))
			|| ($barrier && !in_array($barrier, $this->barrier_whitelist))) {
			$n->setBollard(true);
		}
		if ($n->findValByKey('highway') == 'traffic_signals') {
			$n->setTrafficLight(true);
		}
	}

	/**
	 * @param \gentoid\route\ExtractionWay $w
	 * @return void
	 */
	public function wayFunction(\gentoid\route\ExtractionWay $w) {
		if ($this->ignore_areas && $w->findValByKey('area') == 'yes') {
			return;
		}

		$oneway = $w->findValByKey('oneway');
		if ($oneway == 'reversible') {
			return;
		}

		$access  = null;
		foreach ($this->access_tags_hierarchy as $tag) {
			if ($access = $w->findValByKey($tag)) {
				break;
			}
		}

		if (in_array($access, $this->access_tag_blacklist)) {
			return;
		}

		$highway = $w->findValByKey('highway');
		$name = $w->findValByKey('name');
		$ref = $w->findValByKey('ref');
		$junction = $w->findValByKey('junction');
		$route = $w->findValByKey('route');
		$maxspeed = $this->parseMaxspeed($w->findValByKey('maxspeed'));
		$maxspeedForward = $this->parseMaxspeed($w->findValByKey('maxspeed:forward'));
		$maxspeedBackward = $this->parseMaxspeed($w->findValByKey('maxspeed:backward'));
//		$barrier = $w->findValByKey('barrier');
//		$cycleway = $w->findValByKey('cycleway');
		$duration = $w->findValByKey('duration');
		$service = $w->findValByKey('service');

		if ($ref) {
			$w->setName($ref);
		}
		elseif ($name) {
			$w->setName($name);
		}

		if ($junction == 'roundabout') {
			$w->setRoundabout(true);
		}

		if (isset($this->speed_profile[$route]) && $this->speed_profile[$route] > 0) {
			if (ExtractionHelper::durationIsValid($duration)) {
				$w->setDuration(max(ExtractionHelper::parseDuration($duration), 1));
			}
			$w->getDirection()->setValue(Direction::BIDIRECTIONAL);
			$highway = $route;
			if ($w->getDuration() < 0) {
				$w->setSpeed($this->speed_profile[$highway]);
			}
		}

		if (isset($this->speed_profile[$highway]) && $w->getSpeed() == -1) {
			if ($maxspeed > $this->speed_profile[$highway]) {
				$w->setSpeed($maxspeed);
			}
			else {
				if ($maxspeed == 0) {
					$maxspeed = PHP_INT_MAX;
				}
				$w->setSpeed(min($this->speed_profile[$highway], $maxspeed));
			}
		}

		if ($highway && isset($this->access_tag_blacklist[$access]) && $w->getSpeed() == -1) {
			if ($maxspeed == 0) {
				$maxspeed = PHP_INT_MAX;
			}
			$w->setSpeed(min($this->speed_profile['default'], $maxspeed));
		}

		if (($access && isset($this->access_tag_restricted[$access]))
			|| ($service && isset($this->access_tag_restricted[$service]))) {
			$w->setIsAccessRestricted(true);
		}

		$w->getDirection()->setValue(Direction::BIDIRECTIONAL);
		if ($this->obey_oneway) {
			if ($oneway == -1) {
				$w->getDirection()->setValue(Direction::OPPOSITE);
			}
			elseif ($oneway == 'yes' || $oneway == '1' || $oneway == 'true' || $junction == 'roundabout' || $highway == 'motorway_link' || $highway == 'motorway') {
				$w->getDirection()->setValue(Direction::ONEWAY);
			}
		}

		if ($maxspeedForward && $maxspeedForward > 0) {
			if ($w->getDirection()->getValue() == Direction::BIDIRECTIONAL) {
				$w->setBackwardSpeed($w->getSpeed());
			}
			$w->setSpeed($maxspeedForward);
		}

		if ($maxspeedBackward && $maxspeedBackward > 0) {
			$w->setBackwardSpeed($maxspeedBackward);
		}

		if (isset($this->ignore_in_grid[$highway])) {
			$w->setIgnoreInGrid(true);
		}

		$w->setType(1);
	}

}
