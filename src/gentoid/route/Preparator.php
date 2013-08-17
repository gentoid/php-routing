<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route;


use gentoid\route\DataStructures\ImportEdge;
use gentoid\route\DataStructures\SpeedProfileProperties;
use gentoid\route\profiles\CarProfile;
use gentoid\route\util\GraphLoader;

class Preparator {

	public static function run() {
		$usableRestrictionsCounter = 0; // todo: get number of restrictions
		/** @var TurnRestriction[] $inputRestrictions */
		$inputRestrictions = array(); // todo: load restrictions
		$speedProfile = new SpeedProfileProperties();
		/** @var NodeID[] $bollardNodes */
		$bollardNodes = array();
		/** @var NodeID[] $trafficLightNodes */
		$trafficLightNodes = array();
		/** @var NodeInfo[] $internalToExternalNodeMapping */
		$internalToExternalNodeMapping = array();

//		$yaml = new \Symfony\Component\Yaml\Parser();
//		$config = $yaml->parse(file_get_contents('./config/extractor.yaml'));
//		$profiles = (isset($config['profiles'])) ? $config['profiles'] : array('car');

		$profile = new CarProfile();
		$speedProfile
			->setTrafficSignalPenalty($profile->getTrafficSignalPenalty() * 10)
			->setUTurnPenalty($profile->getUTurnPenalty())
			->setHasTurnPenaltyFunction(method_exists($profile, 'turnFunction'));

		/** @var ImportEdge[] $edgeList */
		$edgeList = array();
		GraphLoader::readBinaryOSRMGraphFromStream($edgeList, $bollardNodes, $trafficLightNodes, $internalToExternalNodeMapping);
//		var_dump($bollardNodes, $trafficLightNodes, $internalToExternalNodeMapping, $edgeList);
		print_r($bollardNodes);
		print_r($trafficLightNodes);
		print_r($internalToExternalNodeMapping);
		print_r($edgeList);
	}

} 
