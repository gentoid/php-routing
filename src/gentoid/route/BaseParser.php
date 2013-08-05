<?php

namespace gentoid\route;


abstract class BaseParser {

	public function __construct(\gentoid\route\profiles\BasicProfile $profile) {
		//
	}

	abstract public function ParseNode();
	abstract public function PArseWay();
	abstract public function reportError();

	protected function ReadUseRestrictionsSetting() {
		//
	}

	protected function ReadRestrictionExceptions() {
		//
	}

	protected function ShouldIgnoreRestriction() {
		//
	}

} 