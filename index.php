<?php

//const IGNORE_AREAS = true;

require_once('vendor/autoload.php');

$osmExtractor = new gentoid\route\OSMExtractor();

$osmExtractor->extract('ert');