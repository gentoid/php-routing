<?php

namespace gentoid\route\profiles;


abstract class BasicProfile {

	protected $restriction_exception_tags = array();

	/**
	 * @param array $restrictionExceptions
	 */
	public function getExceptions(array &$restrictionExceptions) {
		foreach ($this->restriction_exception_tags as $exception) {
			$restrictionExceptions[] = $exception;
		}
	}

	/**
	 * @param \gentoid\route\ImportNode $n
	 * @return mixed
	 */
	abstract public function nodeFunction(\gentoid\route\ImportNode $n);

	/**
	 * @param \gentoid\route\ExtractionWay $w
	 * @return mixed
	 */
	abstract public function wayFunction(\gentoid\route\ExtractionWay $w);

} 