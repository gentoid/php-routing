<?php
/**
 * @date: 8/12/13
 * @uathor: viktor
 */

namespace gentoid\route\DataStructures;


class MerkatorUtil {

	public static function y2lat($a) {
		return 180 * M_1_PI * (2 * atan(exp($a * M_PI / 180)) - M_PI_2);
	}

	public static function lan2y($a) {
		return 180 * M_1_PI * log(tan(M_PI_4 + $a * M_PI_2 / 180));
	}

}
 