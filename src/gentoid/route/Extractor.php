<?php

namespace gentoid\route;

use gentoid\route\profiles\BasicProfile;
use Symfony\Component\Yaml as Yaml;

class Extractor {

	/** @var array */
	protected $config;

	/** @var BasicProfile[] */
	protected $profiles;

	/** @var \SimpleXMLElement */
	protected $xml;

	/** @var Node[] */
	protected $nodes = array();

	/** @var Way[] */
	protected $ways = array();

	public function __construct() {
		$this->init();
	}

	/**
	 * @param string $file
	 * @throws \Exception
	 */
	public function extract($file) {
		if (!file_exists($file)) {
			throw new \Exception('Couldn\'t open file "'.$file.'"');
		}
		$this->xml = new \SimpleXMLElement(file_get_contents($file));

		foreach ($this->profiles as $profile) {
			$profile->loadXml($this->xml);
			$this->nodes = array_merge($this->nodes, $profile->extractNodes());

		}

		foreach ($this->profiles as $profile) {
			$this->ways  = array_merge($this->ways, $profile->extractWays());
		}
	}

	/**
	 * @throws \Exception|\Symfony\Component\Yaml\Exception\ParseException
	 */
	protected function init() {
		$yaml = new Yaml\Parser();
		$config = $yaml->parse(file_get_contents('./config/extractor.yaml'));
		$this->config = $config;

		$profiles = (isset($config['profiles'])) ? $config['profiles'] : array('car');

		foreach ($profiles as $name) {
			$className = "\\gentoid\\route\\profiles\\".ucwords($name)."Profile";

			try {
				/** @var BasicProfile $profile */
				$profile = new $className();
				$this->profiles[$name] = $profile;
			}
			catch (\Exception $e) {
				echo 'There\'s no profile '.$name.PHP_EOL;
				continue;
			}
		}
	}

}
