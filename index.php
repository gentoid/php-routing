<?php
require_once('vendor/autoload.php');

try {
	if (PHP_SAPI == 'cli') {
		$command = new gentoid\utils\Command();
		$command->setExpectedOption('osm', false, true);
		$command->parseOptions($argv, $argc);
	}
}
catch (Exception $e) {
	echo "Error: ".$e->getMessage().PHP_EOL;
}
