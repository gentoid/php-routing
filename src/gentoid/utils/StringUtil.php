<?php
/**
 * @date: 8/6/13
 * @author: viktor
 */

namespace gentoid\utils;


class StringUtil {

	/**
	 * @return string
	 */
	public static function getRandomString() {$alphaNum = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		$string = '';
		for ($i = 0; $i <= 127; $i++) {
			$string .= substr($alphaNum, rand(0, strlen($alphaNum) - 1), 1);
		}

		return $string;
	}

}
 