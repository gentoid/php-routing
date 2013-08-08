<?php

namespace gentoid\route;


use gentoid\utils\LogUtil;

class XMLParser extends BaseParser {

	/**
	 * @var \XMLReader
	 */
	protected $xml;

	/**
	 * @param string $filename
	 * @param ExtractorCallbacks $ec
	 * @param profiles\BasicProfile $profile
	 */
	public function __construct($filename, ExtractorCallbacks $ec, \gentoid\route\profiles\BasicProfile $profile) {
		parent::__construct($ec, $profile);
		LogUtil::info('extracting data from input file '.$filename);
		$this->xml = new \XMLReader();
		$this->xml->open($filename);
	}

	/**
	 * @return bool
	 */
	public function readHead() { return true; }

	/**
	 * @return bool
	 */
	public function parse() {
		while ($this->xml->read()) {
			// 1 is Element
			if ($this->xml->nodeType != 1) {
				continue;
			}

			$name = $this->xml->name;
			if ($name === null) {
				continue;
			}

			if ($name == 'node') {
				$n = $this->readXMLNode();
				$this->parseNode($n);
				$this->extractor_callbacks->nodeFunction($n);
			}
			elseif ($name == 'way') {
				$w = $this->readXMLWay();
				$this->parseWay($w);
				$this->extractor_callbacks->wayFunction($w);
			}
			elseif ($name == 'relation' && $this->use_turn_restrictions) {
				$r = $this->readXMLRestriction();
				if ($r->getFromWay() != RawRestrictionContainer::DEFAULT_VALUE) {
					$this->extractor_callbacks->restrictionFunction($r);
				}
			}
		}

		return true;
	}

	/**
	 * @return \gentoid\route\RawRestrictionContainer
	 */
	protected function readXMLRestriction() {
		$restriction = new RawRestrictionContainer();
		$except_tag_string = '';

		if (!$this->xml->isEmptyElement) {
			$depth = $this->xml->depth;

			while ($this->xml->read()) {
				$childType  = $this->xml->nodeType;
				if ($childType != 1 && $childType != 15) {
					continue;
				}

				$childDepth = $this->xml->depth;
				$childName  = $this->xml->name;
				if ($childName === null) {
					continue;
				}

				if ($depth == $childDepth && $childType == 15 && $childName == 'relation') {
					break;
				}

				if ($childType != 1) {
					continue;
				}

				if ($childName == 'tag') {
					$k = $this->xml->getAttribute('k');
					$v = $this->xml->getAttribute('v');
					if ($k && $v) {
						if ($k == 'restriction' && strpos('only_', $v) !== false) {
							$restriction->getRestriction()->setIsOnly(true);
						}
						elseif ($k == 'except') {
							$except_tag_string = $v;
						}
					}
				}
				elseif ($childName == 'member') {
					$ref = $this->xml->getAttribute('ref');
					if ($ref) {
						$role = $this->xml->getAttribute('role');
						$type = $this->xml->getAttribute('type');
						if ($role == 'to' && $type == 'way') {
							$restriction->setToWay($ref);
						}
						elseif ($role == 'from' && $type == 'way') {
							$restriction->setFromWay($ref);
						}
						elseif ($role == 'via' && $type  == 'node') {
							$restriction->getRestriction()->setViaNode(new NodeID($ref));
						}
					}
				}
			}
		}

		// workaround to ignore the restriction
		if ($this->shouldIgnoreRestriction($except_tag_string)) {
			$restriction->setFromWay(RawRestrictionContainer::DEFAULT_VALUE);
		}

		return $restriction;
	}

	/**
	 * @return ExtractionWay
	 */
	protected function readXMLWay() {
		$way = new ExtractionWay();

		if (!$this->xml->isEmptyElement) {
			$depth = $this->xml->depth;
			while ($this->xml->read()) {
				$childType  = $this->xml->nodeType;
				if ($childType != 1 && $childType != 15) {
					continue;
				}

				$childDepth = $this->xml->depth;
				$childName  = $this->xml->name;
				if ($childName === null) {
					continue;
				}

				if ($depth == $childDepth && $childType == 15 && $childName == 'way') {
					$id = $this->xml->getAttribute('id');
					$way->setId($id)->setOsmId($id);
					break;
				}

				if ($childType != 1) {
					continue;
				}

				if ($childName == 'tag') {
					$k = $this->xml->getAttribute('k');
					$v = $this->xml->getAttribute('v');

					if ($k && $v) {
						$way->addKeyVal($k, $v);
					}
				}
				elseif ($childName == 'nd') {
					$ref = $this->xml->getAttribute('ref');
					if ($ref) {
						$way->addPathElement(new NodeID($ref));
					}
				}
			}
		}

		return $way;
	}

	/**
	 * @return ImportNode
	 */
	protected function readXMLNode() {
		$node = new ImportNode();

		$lat = $this->xml->getAttribute('lat');
		if ($lat) {
			$node->getCoordinate()->setLat($lat);
		}
		$lon = $this->xml->getAttribute('lon');
		if ($lon) {
			$node->getCoordinate()->setLon($lon);
		}
		$id = $this->xml->getAttribute('id');
		if ($id) {
			$node->setNodeId(new NodeID($id));
		}

		if (!$this->xml->isEmptyElement) {
			$depth = $this->xml->depth;
			while ($this->xml->read()) {
				$childType  = $this->xml->nodeType;
				if ($childType != 1 && $childType != 15) {
					continue;
				}

				$childDepth = $this->xml->depth;
				$childName  = $this->xml->name;
				if ($childName === null) {
					continue;
				}

				if ($depth == $childDepth && $childType == 15 && $childName == 'node') {
					break;
				}

				if ($childType != 1) {
					continue;
				}

				if ($childName == 'tag') {
					$k = $this->xml->getAttribute('k');
					$v = $this->xml->getAttribute('v');

					if ($k && $v) {
						$node->addKeyVal($k, $v);
					}
				}
			}
		}

		return $node;
	}

}
