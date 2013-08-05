<?php

namespace gentoid\route\profiles;


use gentoid\route\Node;

class BasicProfile {

	/** @var \SimpleXMLElement */
	protected $xml;

	/**
	 * @param \SimpleXMLElement $xml
	 */
	public function loadXml(\SimpleXMLElement $xml) {
		$this->xml = $xml;
	}

	/**
	 * @return \gentoid\route\Node[]
	 */
	public function extractNodes() {
		foreach ($this->xml->xpath('node') as $xmlNode) {
			$node = new Node($xmlNode['id']);

		}
	}

	/**
	 * @return \gentoid\route\Way[]
	 */
	public function extractWays(){}
} 