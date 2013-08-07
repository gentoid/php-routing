<?php

namespace gentoid\route;


use gentoid\route\profiles\BasicProfile;

abstract class BaseParser {

	/** @var ExtractorCallbacks */
	protected $extractor_callbacks;

	/** @var string[] */
	protected $restriction_exceptions = array();

	/** @var boolean */
	protected $use_turn_restrictions;

	/** @var BasicProfile */
	protected $profile;

	abstract public function readHead();
	abstract public function parse();

	/**
	 * @param ExtractorCallbacks $ec
	 * @param profiles\BasicProfile $profile
	 */
	public function __construct(ExtractorCallbacks $ec, BasicProfile $profile) {
		$this->extractor_callbacks = $ec;
		$this->profile = $profile;
		$this->use_turn_restrictions = true;
		$this->readRestrictionExceptions();
	}

	/**
	 * @param ImportNode $n
	 */
	public function parseNode(ImportNode $n) {
		try {
			$this->profile->nodeFunction($n);
		}
		catch (\Exception $e) {}
	}

	/**
	 * @param ExtractionWay $w
	 */
	public function parseWay(ExtractionWay $w) {
		try {
			$this->profile->wayFunction($w);
		}
		catch (\Exception $e) {}
	}

	protected function readRestrictionExceptions() {
		$this->profile->getExceptions($this->restriction_exceptions);
	}

	/**
	 * @param string $except_tag_string
	 * @return bool
	 */
	protected function shouldIgnoreRestriction($except_tag_string) {
		if (strlen($except_tag_string) == 0) {
			return false;
		}

		$exceptions = preg_split('/[;\s+]/', $except_tag_string);
		foreach ($exceptions as $exception) {
			if (in_array($exception, $this->restriction_exceptions)) {
				return true;
			}
		}

		return false;
	}

} 