<?php

namespace gentoid\route\DataStructures;


class FixedPointCoordinate {

	/** @var float */
	protected $lat = PHP_INT_MAX;

	/** @var float */
	protected $lon = PHP_INT_MAX;

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
	 * @return FixedPointCoordinate
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
	 * @return FixedPointCoordinate
	 */
	public function setLon($lon) {
		$this->lon = $this->round($lon);
		return $this;
	}

	public function reset() {
		$this->lat = PHP_INT_MAX;
		$this->lon = PHP_INT_MAX;
	}

	/**
	 * @return bool
	 */
	public function isCoordinateSet() {
		return $this->lat != PHP_INT_MAX && $this->lon != PHP_INT_MAX;
	}

	/**
	 * @return bool
	 */
	public function isValid() {
		return ($this->isCoordinateSet() && $this->lat > -90 * (1 + EPSILON) && $this->lat < 90 * (1 + EPSILON) && $this->lon > -180 * (1 + EPSILON) && $this->lon < 180 * (1 + EPSILON));
	}

	/**
	 * @param FixedPointCoordinate $c
	 * @return bool
	 */
	public function isEqual(FixedPointCoordinate $c) {
		return abs($this->lat - $c->getLat()) < EPSILON && abs($this->lon - $c->getLon()) < EPSILON;
	}

	/**
	 * @param FixedPointCoordinate $a
	 * @param FixedPointCoordinate $b
	 * @return float|null
	 */
	public static function ApproximateDistance(FixedPointCoordinate $a, FixedPointCoordinate $b) {
		if (!$a->isValid() || !$b->isValid()) {
			return null;
		}

		$dLat1 = $a->getLat() * FixedPointCoordinate::RAD;
		$dLat2 = $b->getLat() * FixedPointCoordinate::RAD;

		$dLat = $dLat1 - $dLat2;
		$dLon = ($a->getLon() - $b->getLon()) * FixedPointCoordinate::RAD;

		$aHarv = pow(sin($dLat/2.0), 2.0) + cos($dLat1) * cos($dLat2) * pow(sin($dLon/2.0), 2.0);
		return FixedPointCoordinate::EARTH_RADIUS * 2 * atan2(sqrt($aHarv), sqrt(1.0 - $aHarv));
	}

	/**
	 * @param FixedPointCoordinate $a
	 * @param FixedPointCoordinate $b
	 * @return float|null
	 */
	public static function ApproximateDistanceByEuclid(FixedPointCoordinate $a, FixedPointCoordinate $b) {
		if (!$a->isValid() || !$b->isValid()) {
			return null;
		}

		$x = ($b->getLon() - $a->getLon()) * FixedPointCoordinate::RAD * cos(FixedPointCoordinate::RAD * ($a->getLat() + $b->getLat()) / 2.0);
		$y = ($b->getLat() - $a->getLat()) * FixedPointCoordinate::RAD;

		return sqrt($x * $x + $y * $y) * FixedPointCoordinate::EARTH_RADIUS;
	}

	/**
	 * @return string
	 */
	public function pack() {
		return pack('f', $this->lat) . pack('f', $this->lon);
	}

} 
