<?php

namespace gentoid\route;


class XMLParser extends BaseParser {

	protected $xml;

	public function __construct($filename, ExtractorCallbacks $ec, \gentoid\route\profiles\BasicProfile $profile) {
		parent::__construct($ec, $profile);
		$this->xml = new \SimpleXMLElement(file_get_contents($filename));
	}

	/**
	 * @return bool
	 */
	public function readHead() { return true; }

	/**
	 * @return bool
	 */
	public function parse() {
		foreach ($this->xml->xpath('node') as $xmlNode) {
			$n = $this->readXMLNode($xmlNode);
			$this->parseNode($n);
			$this->extractor_callbacks->nodeFunction($n);
		}

		foreach ($this->xml->xpath('way') as $xmlWay) {
			$w = $this->readXMLWay($xmlWay);
			$this->parseWay($w);
			$this->extractor_callbacks->wayFunction($w);
		}

		if ($this->use_turn_restrictions) {
			foreach ($this->xml->xpath('relation') as $xmlRelation) {
				$r = $this->readXMLRestriction($xmlRelation);
				if ($r->getFromWay() != RawRestrictionContainer::DEFAULT_VALUE) {
					$this->extractor_callbacks->restrictionFunction($r);
				}
			}
		}

		return true;
	}

	/**
	 * @param \SimpleXMLElement $xmlRelation
	 * @return \gentoid\route\RawRestrictionContainer
	 */
	protected function readXMLRestriction(\SimpleXMLElement $xmlRelation) {
		$restriction = new RawRestrictionContainer();
		$except_tag_string = '';

		foreach ($xmlRelation->xpath('tag') as $tag) {

			$key = null;
			$value = null;
			foreach ($attributes = $this->readXMLAttributes($tag) as $k => $v) {
				if ($k == 'k') {
					$key = $v;
				}
				elseif ($k == 'v') {
					$value = $v;
				}
			}
			if ($key && $value) {
				if ($key == 'restriction' && strpos('only_', $value) !== false) {
					$restriction->getRestriction()->setIsOnly(true);
				}
				elseif ($key == 'except') {
					$except_tag_string = $value;
				}
			}
		}

		foreach ($xmlRelation->xpath('member') as $member) {
			$ref  = null;
			$role = null;
			$type = null;
			foreach ($attributes = $this->readXMLAttributes($member) as $k => $v) {
				if ($k == 'ref') {
					$ref = $v;
				}
				elseif ($k == 'role') {
					$role = $v;
				}
				elseif ($k == 'type') {
					$type = $v;
				}
			}
			if ($ref) {
				if ($role == 'to' && $type == 'way') {
					$restriction->setToWay($ref);
				}
				elseif ($role == 'from' && $type == 'way') {
					$restriction->setFromWay($ref);
				}
				elseif ($role == 'via' && $type  == 'node') {
					$restriction->setViaNode(new NodeID($ref));
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
	 * @param \SimpleXMLElement $xmlWay
	 * @return ExtractionWay
	 */
	protected function readXMLWay(\SimpleXMLElement $xmlWay) {
		$way = new ExtractionWay();

		foreach ($attributes = $this->readXMLAttributes($xmlWay) as $k => $v) {
			if ($k == 'id') {
				$way->setOsmId($v)->setId($v);
				break;
			}
		}

		foreach ($xmlWay->xpath('tag') as $tag) {
			$key = null;
			$val = null;
			foreach ($attributes = $this->readXMLAttributes($tag) as $k => $v) {
				if ($k == 'k') {
					$key = $v;
				}
				elseif ($k == 'v') {
					$val = $v;
				}
			}
			if ($key && $val) {
				$way->addKeyVal($key, $val);
			}
		}
		foreach ($xmlWay->xpath('nd') as $nd) {
			foreach ($attributes = $this->readXMLAttributes($nd) as $k => $v) {
				if ($k == 'ref' && $v) {
					$way->addPathElement(new NodeID($v));
					break;
				}
			}
		}

		return $way;
	}

	/**
	 * @param \SimpleXMLElement $xmlNode
	 * @return ImportNode
	 */
	protected function readXMLNode(\SimpleXMLElement $xmlNode) {
		$node = new ImportNode();
		foreach ($attributes = $this->readXMLAttributes($xmlNode) as $k => $v) {
			if ($k == 'lat' && $v) {
				$node->getCoordinate()->setLat($v);
			}
			elseif ($k == 'lon' && $v) {
				$node->getCoordinate()->setLon($v);
			}
			elseif ($k == 'id' && $v) {
				$node->setNodeId(new NodeID($v));
			}
		}

		foreach ($xmlNode->xpath('tag') as $tag) {
			$key = null;
			$val = null;
			foreach ($attributes = $this->readXMLAttributes($tag) as $k => $v) {
				if ($k == 'k') {
					$key = $v;
				}
				elseif ($k == 'v') {
					$val = $v;
				}
			}
			if ($key && $val) {
				$node->addKeyVal($key, $val);
			}
		}

		return $node;
	}

	/**
	 * @param \SimpleXMLElement $xml
	 * @return array|\SimpleXMLElement
	 */
	protected function readXMLAttributes(\SimpleXMLElement $xml) {
		if ($attributes = $xml->attributes()) {
			return $attributes;
		}

		return array();
	}

}
