<?php

const IGNORE_AREAS = true;

spl_autoload_register('my_autoloader');
function my_autoloader($class) {
	require_once("./classes/{$class}.php");
}

/**
 * @param string $param
 * @param SimpleXMLElement $from
 * @return string|bool
 */
function getAttr($param, SimpleXMLElement $from) {
	return (isset($from[$param])) ? $from[$param] : false;
}

/**
 * @param SimpleXMLElement $XMLWay
 * @return null|Way
 */
function way_function(SimpleXMLElement $XMLWay) {
	$registry = Registry::getInstance();

	$area = getAttr('area', $XMLWay);
	if (IGNORE_AREAS && $area == 'yes') {
		return null;
	}

	$oneway = getAttr('oneway', $XMLWay);
	if ($oneway == 'reversible') {
		return null;
	}

	foreach ($XMLWay->attributes() as $attr => $val) {
		if (in_array($attr, $registry->getAccessTagsHierarchy()) && in_array($val, $registry->getAccessTagsBlackList())) {
			return null;
		}
	}

	return new Way($XMLWay);

}

function readXML() {
	$nodes = array();
	$ways = array();
	$osm = file_get_contents('./tmp/test.osm');

	$xml = new SimpleXMLElement($osm);

	foreach ($xml->xpath('node') as $element) {
		$nodes[] = new Node($element);
	}

	foreach ($xml->xpath('way') as $XMLway) {
		$way = way_function($XMLway);
		if ($way) {
			$ways[] = $way;
		}
	}
}

readXML();