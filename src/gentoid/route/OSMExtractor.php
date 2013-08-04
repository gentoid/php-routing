<?php

namespace gentoid\route;

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
		try {
			$config = $yaml->parse(file_get_contents('./config/extractor.yaml'));
			$this->config = $config;
		}
		catch (Yaml\Exception\ParseException $e) {
			echo 'Unable to parse the YAML string: '.$e->getMessage();
			throw $e;
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
