<?php

namespace gentoid\route;

class Registry {

	private static $instance;

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

}
