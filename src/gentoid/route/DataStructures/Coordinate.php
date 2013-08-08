<?php

namespace gentoid\route\DataStructures;


class Coordinate {

	const DEFAULT_VALUE = 1000;

	/** @var float */
	protected $lat = Coordinate::DEFAULT_VALUE;

	/** @var float */
	protected $lon = Coordinate::DEFAULT_VALUE;

	const RAD = 0.017453292519943295769236907684886;
	const EARTH_RADIUS = 6372797.560856;

	protected function round($float) {
		return round(floatval($float), 8, PHP_ROUND_HALF_UP);
	}

	/**
	 * @return float
	 */
	public function getLat() {
		return $this->lat;
	}

	/**
	 * @param float $lat
	 * @return Coordinate
	 */
	public function setLat($lat) {
		$this->lat = $this->round($lat);
		return $this;
	}

	/**
	 * @return float
	 */
	public function getLon() {
		return $this->lon;
	}

	/**
	 * @param float $lon
	 * @return Coordinate
	 */
	public function setLon($lon) {
		$this->lon = $this->round($lon);
		return $this;
	}

	public function reset() {
		$this->lat = null;
		$this->lon = null;
	}

	/**
	 * @return bool
	 */
	public function isCoordinateSet() {
		return $this->lat && $this->lon;
	}

	/**
	 * @return bool
	 */
	public function isValid() {
		return ($this->isCoordinateSet() && $this->lat > -90 && $this->lat < 90 && $this->lon > -180 && $this->lon < 180);
	}

	/**
	 * @param Coordinate $c
	 * @return bool
	 */
	public function isEqual(Coordinate $c) {
		return $this->lat === $c->getLat() && $this->lon === $c->getLon();
	}

	/**
	 * @param Coordinate $a
	 * @param Coordinate $b
	 * @return float|null
	 */
	public static function ApproximateDistance(Coordinate $a, Coordinate $b) {
		if (!$a->isValid() || !$b->isValid()) {
			return null;
		}

		$dLat1 = $a->getLat() * Coordinate::RAD;
		$dLat2 = $b->getLat() * Coordinate::RAD;

		$dLat = $dLat1 - $dLat2;
		$dLon = ($a->getLon() - $b->getLon()) * Coordinate::RAD;

		$aHarv = pow(sin($dLat/2.0), 2.0) + cos($dLat1) * cos($dLat2) * pow(sin($dLon/2.0), 2.0);
		return Coordinate::EARTH_RADIUS * 2 * atan2(sqrt($aHarv), sqrt(1.0 - $aHarv));
	}

	/**
	 * @param Coordinate $a
	 * @param Coordinate $b
	 * @return float|null
	 */
	public static function ApproximateDistanceByEuclid(Coordinate $a, Coordinate $b) {
		if (!$a->isValid() || !$b->isValid()) {
			return null;
		}

		$x = ($b->getLon() - $a->getLon()) * Coordinate::RAD * cos(Coordinate::RAD * ($a->getLat() + $b->getLat()) / 2.0);
		$y = ($b->getLat() - $a->getLat()) * Coordinate::RAD;

		return sqrt($x * $x + $y * $y) * Coordinate::EARTH_RADIUS;
	}

	/**
	 * @return string
	 */
	public function pack() {
		return pack('f', $this->lat) . pack('f', $this->lon);
	}

} 