<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 8/5/13
 * Time: 2:52 AM
 */

namespace gentoid\route\profiles;

interface BasicProfile {
	public function prepareNode();
	public function prepareWay();
} 