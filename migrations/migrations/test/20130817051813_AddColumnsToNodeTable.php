<?php

class AddColumnsToNodeTable extends Ruckusing_Migration_Base
{
    public function up() {
	    $prefix = 'phpr_';

	    $this->add_column($prefix.'nodes', 'bollard', 'boolean');
	    $this->add_column($prefix.'nodes', 'trafficLight', 'boolean');
    }

    public function down() {
	    $prefix = 'phpr_';
	    $this->remove_column($prefix.'nodes', 'bollard');
	    $this->remove_column($prefix.'nodes', 'trafficLight');
    }
}
