<?php
/**
 * @author viktor
 * @date 17.08.13
 */

namespace gentoid\route\util;


use gentoid\route\DataStructures\ImportEdge;
use gentoid\route\NodeID;
use gentoid\route\NodeInfo;
use gentoid\utils\DB;

class GraphLoader {

	/**
	 * @param ImportEdge[] $edgeList
	 * @param NodeID[] $bollardNodes
	 * @param NodeID[] $trafficLightNodes
	 * @param NodeInfo[] $int2ExtNodeMap
	 */
	public static function readBinaryOSRMGraphFromStream(
		array &$edgeList,
		array &$bollardNodes,
		array &$trafficLightNodes,
		array &$int2ExtNodeMap
	) {
		$dbh = DB::getInstance()->getDbh();
		$stmt = $dbh->query("SELECT * FROM phpr_nodes");
		while($node = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$osmNodeId = new NodeID($node['osm_node_id']);
			$nodeInfo = new NodeInfo();
			$nodeInfo->setNodeId($osmNodeId);
			$coordinate = $nodeInfo->getCoordinate();
			$coordinate->setLat($node['lat'])->setLon($node['lon']);
			array_push($int2ExtNodeMap, $nodeInfo);
			if ($node['bollard']) {
				array_push($bollardNodes, $osmNodeId);
			}
			if ($node['trafficLight']) {
				array_push($trafficLightNodes, $osmNodeId);
			}
		}

		$stmt = $dbh->query("SELECT * FROM phpr_edges");
		while ($edge = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$forward = $backward = true;
			if ($edge['direction'] == 1) {
				$backward = false;
			}
			elseif ($edge['direction'] == 2) {
				$forward = false;
			}

			$source = new NodeID($edge['node_id_start']);
			$target = new NodeID($edge['node_id_target']);
			if ($source->moreThan($target)) {
				$tmp = $source;
				$source = $target;
				$target = $tmp;
			}

			$inputEdge = new ImportEdge(
				$source,
				$target,
				new NodeID($edge['name_id']),
				$edge['weight'],
				$forward,
				$backward,
				$edge['type'],
				$edge['is_roundabout'],
				$edge['ignore_in_grid'],
				$edge['is_access_restricted'],
				$edge['is_contra_flow']
			);
//			$inputEdge
//				->setSource($source)
//				->setTarget($target)
//				->setName(new NodeID($edge['name_id']))
//				->setWeight($edge['weight'])
//				->setForward($forward)
//				->setBackward($backward)
//				->setType($edge['type'])
//				->setRoundabout($edge['is_roundabout'])
//				->setIgnoreInGrid($edge['ignore_in_grid'])
//				->setAccessRestricted($edge['is_access_restricted'])
//				->setContraFlow($edge['is_contra_flow']);
			array_push($edgeList, $inputEdge);
		}
//		usort($edgeList, array('\\gentoid\\route\\NodeID', 'cmp'));
	}

} 
