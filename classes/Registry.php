<?php

class Registry {

	private static $instance;

	private $accessTagsHierarchy = array('motorcar', 'motor_vehicle', 'vehicle', 'access');
	private $accessTagsBlackList = array('no', 'private', 'agricultural', 'forestry');
	private $barrierWhiteList = array('cattle_grid', 'border_control', 'toll_booth', 'sally_port', 'gate', 'designated');

	protected function __construct() {
		//
	}

	/**
	 * @return self
	 */
	public static function getInstance() {
		if (!(self::$instance instanceof self)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @return array
	 */
	public function getAccessTagsHierarchy() {
		return $this->accessTagsHierarchy;
	}

	/**
	 * @return array
	 */
	public function getAccessTagsBlackList() {
		return $this->accessTagsBlackList;
	}

	/**
	 * @return array
	 */
	public function getBarrierWhiteList() {
		return $this->barrierWhiteList;
	}

} 