<?php
/**
 * @date: 8/7/13
 * @uathor: viktor
 */

namespace gentoid\route;


trait Tag {

	public function findTag($tag) {
		if (isset($this->tags) && isset($this->tags[$tag])) {
			return $this->tags[$tag];
		}

		return null;
	}

} 