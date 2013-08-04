<?php

namespace gentoid\route;

use gentoid\route\profiles\BasicProfile;
use Symfony\Component\Yaml as Yaml;

class OSMExtractor {

	/** @var array */
	protected $config;

	/** @var array */
	protected $accessTagsHierarchy;

	/** @var array */
	protected $accessTagsBlacklist;

	/** @var array */
	protected $barrierWhitelist;

	/** @var \SimpleXMLElement */
	protected $xml;

	/** @var Node[] */
	protected $nodes;

	public function __construct() {
		$this->loadConfig();
	}

	public function extract($file) {
		$this->xml = new \SimpleXMLElement(file_get_contents($file));

		$this->extractNodes();
	}

	/**
	 * @throws \Exception|\Symfony\Component\Yaml\Exception\ParseException
	 */
	protected function loadConfig() {
		$yaml = new Yaml\Parser();
		$config = $yaml->parse(file_get_contents('./config/extractor.yaml'));
		$this->config = $config;

		$profiles = (isset($config['profiles'])) ? $config['profiles'] : array('car');

		foreach ($profiles as $name) {
			$className = "\\gentoid\\route\\profiles\\".ucwords($name)."Profile";

			/** @var BasicProfile $profile */
			$profile = new $className();
		}

		if (isset($config['accessTags'])) {
			if (isset($config['accessTags']['hierarchy'])) {
				$this->accessTagsHierarchy = $config['access']['hierarchy'];
			}
			if (isset($config['accessTags']['blacklist'])) {
				$this->accessTagsBlacklist = $config['access']['blacklist'];
			}
		}

		if (isset($config['barrier']) && isset($config['barrier']['whitelist'])) {
			$this->barrierWhitelist = $config['barrier']['whitelist'];
		}
	}

	protected function extractNodes() {
		foreach ($this->xml->xpath('node') as $xmlNode) {

		}
	}

}
