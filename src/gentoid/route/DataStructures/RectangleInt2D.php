<?php
/**
 * @author viktor
 * @date 15.08.13
 */

namespace gentoid\route\DataStructures;


class RectangleInt2D {

	/** @var int */
	protected $minLat = PHP_INT_MAX;

	/** @var int */
	protected $maxLat = PHP_INT_MAX;

	/** @var int */
	protected $minLon = PHP_INT_MAX;

	/** @var int */
	protected $maxLon = PHP_INT_MAX;

	/**
	 * @param EdgeBasedNode[] $objects
	 * @param int $elementCount
	 */
	public function initializeMBRectangle(array $objects, $elementCount) {
		for ($i = 0; $i < $elementCount; $i++) {
			$lon1 = $objects[$i]->getCoordinate1()->getLon();
			$lon2 = $objects[$i]->getCoordinate2()->getLon();
			$lat1 = $objects[$i]->getCoordinate1()->getLat();
			$lat2 = $objects[$i]->getCoordinate2()->getLat();

			$this->minLon = min(array($lon1, $lon2, $this->minLon));
			$this->maxLon = max(array($lon1, $lon2, $this->maxLon));
			$this->minLat = min(array($lat1, $lat2, $this->minLat));
			$this->maxLat = max(array($lat1, $lat2, $this->maxLat));
		}
	}

	/**
	 * @param RectangleInt2D $other
	 * @return void
	 */
	public function augmentMBRectangle(RectangleInt2D $other) {
		$this->minLon = min($other->getMinLon(), $this->minLon);
		$this->maxLon = max($other->getMaxLon(), $this->maxLon);
		$this->minLat = min($other->getMinLat(), $this->minLat);
		$this->maxLat = max($other->getMaxLat(), $this->maxLat);
	}

	/**
	 * @return FixedPointCoordinate
	 */
	public function centroid() {
		$centroid = new FixedPointCoordinate();
		$centroid->setLon(($this->minLon + $this->maxLon) / 2)->setLat(($this->minLat + $this->maxLat) / 2);
		return $centroid;
	}

	/**
	 * @param RectangleInt2D $other
	 * @return bool
	 */
	public function intersects(RectangleInt2D $other) {
		$upperLeft  = new FixedPointCoordinate();
		$upperLeft ->setLat($other->getMaxLat())->setLon($other->getMinLon());
		$upperRight = new FixedPointCoordinate();
		$upperRight->setLat($other->getMaxLat())->setLon($other->getMaxLon());
		$lowerRight = new FixedPointCoordinate();
		$lowerRight->setLat($other->getMinLat())->setLon($other->getMaxLon());
		$lowerLeft  = new FixedPointCoordinate();
		$lowerLeft ->setLat($other->getMinLat())->setLon($other->getMinLon());

		return $this->contains($upperLeft)
			|| $this->contains($upperRight)
			|| $this->contains($lowerLeft)
			|| $this->contains($lowerRight);
	}

	/**
	 * @param FixedPointCoordinate $location
	 * @return float
	 */
	public function getMinDist(FixedPointCoordinate $location) {
		$isContained = $this->contains($location);
		if ($isContained) {
			return 0.0;
		}

		$corner1 = new FixedPointCoordinate();
		$corner1->setLat($this->maxLat)->setLon($this->minLon);
		$corner2 = new FixedPointCoordinate();
		$corner2->setLat($this->maxLat)->setLon($this->maxLon);
		$corner3 = new FixedPointCoordinate();
		$corner3->setLat($this->minLat)->setLon($this->maxLon);
		$corner4 = new FixedPointCoordinate();
		$corner4->setLat($this->minLat)->setLon($this->minLon);

		return min(array(
			(float)PHP_INT_MAX,
			FixedPointCoordinate::ApproximateDistance($location, $corner1),
			FixedPointCoordinate::ApproximateDistance($location, $corner2),
			FixedPointCoordinate::ApproximateDistance($location, $corner3),
			FixedPointCoordinate::ApproximateDistance($location, $corner4),
		));
	}

	/**
	 * @param FixedPointCoordinate $location
	 * @return float
	 */
	public function getMinMaxDist(FixedPointCoordinate $location) {
		$upperLeft = new FixedPointCoordinate();
		$upperLeft->setLat($this->maxLat)->setLon($this->minLon);
		$upperRight = new FixedPointCoordinate();
		$upperRight->setLat($this->maxLat)->setLon($this->maxLon);
		$lowerRight = new FixedPointCoordinate();
		$lowerRight->setLat($this->minLat)->setLon($this->maxLon);
		$lowerLeft = new FixedPointCoordinate();
		$lowerLeft->setLat($this->minLat)->setLon($this->minLon);

		return min(array(
			(float)PHP_INT_MAX,
			max(
				FixedPointCoordinate::ApproximateDistance($location, $upperLeft),
				FixedPointCoordinate::ApproximateDistance($location, $upperRight)
			),
			max(
				FixedPointCoordinate::ApproximateDistance($location, $upperRight),
				FixedPointCoordinate::ApproximateDistance($location, $lowerRight)
			),
			max(
				FixedPointCoordinate::ApproximateDistance($location, $lowerRight),
				FixedPointCoordinate::ApproximateDistance($location, $lowerLeft)
			),
			max(
				FixedPointCoordinate::ApproximateDistance($location, $lowerLeft),
				FixedPointCoordinate::ApproximateDistance($location, $upperLeft)
			),
		));
	}

	/**
	 * @param FixedPointCoordinate $location
	 * @return bool
	 */
	public function contains(FixedPointCoordinate $location) {
		$latsContained = $location->getLat() > $this->minLat && $location->getLat() < $this->maxLat;
		$lonsContained = $location->getLon() > $this->minLon && $location->getLon() < $this->maxLon;

		return $latsContained && $lonsContained;
	}

	/**
	 * @return int
	 */
	public function getMinLat() {
		return $this->minLat;
	}

	/**
	 * @return int
	 */
	public function getMaxLat() {
		return $this->maxLat;
	}

	/**
	 * @return int
	 */
	public function getMinLon() {
		return $this->minLon;
	}

	/**
	 * @return int
	 */
	public function getMaxLon() {
		return $this->maxLon;
	}

} 
