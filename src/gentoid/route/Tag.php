<?php
/**
 * @date: 8/7/13
 * @uathor: viktor
 */

namespace gentoid\route;


trait Tag {

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function findValByKey($key) {
		return (isset($this->keyVals[$key])) ? $this->keyVals[$key] : null;
	}

}
