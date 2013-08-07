<?php

namespace gentoid\route\profiles;


class CarProfile extends BasicProfile {

	protected $barrier_whitelist = array('cattle_grid' => true, 'border_control' => true, 'toll_booth' => true,
		'sally_port' => true, 'gate' => true, 'no' => true);

	protected $access_tag_whitelist = array('yes' => true, 'motorcar' => true, 'motor_vehicle' => true,
		'vehicle' => true, 'permissive' => true, 'designated' => true);

	protected $access_tag_blacklist = array('no' => true, 'private' => true, 'agricultural' => true, 'forestry' => true);

	protected $access_tag_restricted = array('destination' => true, 'delivery' => true);

	protected $access_tags = array('motorcar', 'motor_vehicle', 'vehicle');

	protected $access_tags_hierarchy = array('motorcar', 'motor_vehicle', 'vehicle', 'access');

	protected $service_tag_restricted = array('parking_aisle' => true);

	protected $ignore_in_grid = array('ferry' => true);

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

	public function parseMaxspeed() {}

	public function nodeFunction(\gentoid\route\ImportNode $n) {
		$barrier = $n->findAttribute('barrier');
	}

	public function wayFunction(\gentoid\route\ExtractionWay $w) {}

}
