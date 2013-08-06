<?php

namespace gentoid\route;


class ExtractionHelper {

	/** @var string */
	protected static $durationPattern = '/(\\d{1,2})(:(\\d{1,2})(:(\\d{1,2}))?)?/';

	/**
	 * @param string $duration
	 * @return bool
	 */
	public static function durationIsValid($duration) {
		return preg_match(self::$durationPattern, $duration) >= 1;
	}

	/**
	 * @param string $duration
	 * @return int
	 */
	public static function parseDuration($duration) {
		preg_match(self::$durationPattern, $duration, $matches);
		$h = $m = $s = 0;
		switch (count($matches)) {
			case 6:
				$s = $matches[5];
			case 5:
			case 4:
				$m = $matches[3];
			case 3:
			case 2:
				$h = $matches[1];
		}

		echo "h = $h, m = $m, s = $s".PHP_EOL;

		return 10 * (3600 * $h + 60 * $m + $s);
	}

	/**
	 * @param string $speed
	 * @return int
	 */
	public static function parseMaxspeed($speed) {
		$speed = strtolower($speed);
		$n = intval($speed);
		if ($n > 0) {
			if (strpos('mph', $speed) || strpos('mp/h', $speed)) {
				$n = $n * 1609 / 1000;
			}
		}
		else {
			$n = 0;
		}

		return (int)$n;
	}

}
