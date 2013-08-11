<?php
/**
 * @author viktor
 * @date 11.08.13
 */

namespace gentoid\utils;


class DB {

	/** @var self */
	protected static $instance;

	/** @var \PDO */
	protected $dbh;

	protected function __construct() {
		$host = 'localhost';
		$db = 'pgsql';
		$dbname = 'test';
		$user = 'test';
		$pass = 'test';
		$this->dbh = new \PDO($db.':dbname='.$dbname.';host='.$host.';user='.$user.';password='.$pass);
	}

	public static function getInstance() {
		if (!(self::$instance instanceof self)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @return \PDO
	 */
	public function getDbh() {
		return $this->dbh;
	}

} 
