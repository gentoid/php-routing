<?php

namespace gentoid\route;


use gentoid\utils\LogUtil;

class Extractor {

	/** @var array */
	protected $config;

	/** @var string[] */
	protected $profiles;

	public function __construct($file) {
		$earlierTime = microtime(true);
		$this->init();
		$isPBF = is_int(strpos('osm.pbf', $file));

		$containers = new ExtractionContainers();
		$extractCallBacks = new ExtractorCallbacks();
		$extractCallBacks->setExternal($containers);

		foreach ($this->profiles as $profile) {
			if ($isPBF) {
				// todo
			}
			else {
				/** @var BaseParser $parser */
				$parser = new XMLParser($file, $extractCallBacks, new $profile);
			}

			$time = microtime(true);
			LogUtil::info('Parsing in progress...');
			$parser->parse();
			$timeDiff = microtime(true) - $time;
			LogUtil::info('Parsing finished after  '.$timeDiff.' seconds');
		}

		$containers->prepareData($file.'.phprd', $file.'.phprd.restrictions');
		$timeDiff = microtime(true) - $earlierTime;
		LogUtil::info('finished after  '.$timeDiff.' seconds');
	}

	/**
	 * @throws \Exception|\Symfony\Component\Yaml\Exception\ParseException
	 */
	protected function init() {
		$yaml = new \Symfony\Component\Yaml\Parser();
		$config = $yaml->parse(file_get_contents('./config/extractor.yaml'));
		$this->config = $config;

		$profiles = (isset($config['profiles'])) ? $config['profiles'] : array('car');

		foreach ($profiles as $name) {
			$this->profiles[$name] = "\\gentoid\\route\\profiles\\".ucwords($name)."Profile";
		}
	}

}
