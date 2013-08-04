<?php
require_once('vendor/autoload.php');

if (PHP_SAPI == 'cli') {
	try {
		$command = new gentoid\utils\Command();
		$command->setExpectedOption('extract', null, true);
		$command->parseOptions($argv, $argc);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage() . PHP_EOL;
		exit(1);
	}

	if ($osmFile = $command->getOption('extract')) {
		$xml = new SimpleXMLElement(file_get_contents($osmFile));

		$extractor = new \gentoid\route\OSMExtractor();
	}
}
