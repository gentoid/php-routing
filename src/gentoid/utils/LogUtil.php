<?php
/**
 * @date: 8/8/13
 * @uathor: viktor
 */

namespace gentoid\utils;


class LogUtil {

	/**
	 * @param string $data
	 */
	public static function info($data) {
		$bt = debug_backtrace();
		echo '[i '.$bt[0]['file'].':'.$bt[0]['line'].'] '.$data.PHP_EOL;
	}

	/**
	 * @param string $data
	 */
	public static function warn($data) {
		$bt = debug_backtrace();
		echo '[? '.$bt[0]['file'].':'.$bt[0]['line'].'] '.$data.PHP_EOL;
	}

	/**
	 * @param string $data
	 * @throws \Exception
	 */
	public static function err($data) {
		$bt = debug_backtrace();
		throw new \Exception('[! '.$bt[0]['file'].':'.$bt[0]['line'].'] '.$data);
	}

	public static function infoAsIs($data) {
		echo $data.PHP_EOL;
	}

}
 