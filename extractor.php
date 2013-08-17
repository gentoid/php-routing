<?php
require_once('vendor/autoload.php');
const EPSILON = 0.00000001;

if (PHP_SAPI == 'cli') {
	try {
		$command = new gentoid\utils\Command();
		$command->setExpectedOption('extract', null, true);
		$command->parseOptions($argv, $argc);

		if ($osmFile = $command->getOption('extract')) {
			$extractor = new \gentoid\route\Extractor($osmFile->getValue());
			\gentoid\route\Preparator::run();
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage() . PHP_EOL;
		exit(1);
	}
}
