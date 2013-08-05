<?php
require_once('vendor/autoload.php');

if (PHP_SAPI == 'cli') {
	try {
		$command = new gentoid\utils\Command();
		$command->setExpectedOption('extract', null, true);
		$command->parseOptions($argv, $argc);

		if ($osmFile = $command->getOption('extract')) {
			$extractor = new \gentoid\route\Extractor();
			$extractor->extract($osmFile->getValue());
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage() . PHP_EOL;
		exit(1);
	}
}
