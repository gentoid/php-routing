<?php

class Init extends Ruckusing_Migration_Base
{
    public function up() {
		$prefix = 'phpr_';
		/** @var Ruckusing_Adapter_PgSQL_TableDefinition $t */
		$t = $this->create_table($prefix.'edges11');
//		$t->column();
		var_dump($t);
	}

    public function down()
    {
    }//down()
}
