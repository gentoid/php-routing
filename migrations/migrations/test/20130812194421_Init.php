<?php

class Init extends Ruckusing_Migration_Base
{
    public function up() {
		$prefix = 'phpr_';

		/** @var Ruckusing_Adapter_PgSQL_TableDefinition $t */
		$t = $this->create_table($prefix.'edges');
		$t->column('node_id_start',        'integer');
		$t->column('node_id_target',       'integer');
		$t->column('name_id',              'integer');
		$t->column('osm_way_id',           'integer');
		$t->column('weight',               'integer');
		$t->column('distance',             'float');
		$t->column('type',                 'smallinteger');
		$t->column('direction',            'smallinteger');
		$t->column('is_roundabout',        'boolean');
		$t->column('ignore_in_grid',       'boolean');
		$t->column('is_access_restricted', 'boolean');
		$t->column('is_contra_flow',       'boolean');
		$t->finish();

		/** @var Ruckusing_Adapter_PgSQL_TableDefinition $t */
		$t = $this->create_table($prefix.'names');
		$t->column('name', 'text');
		$t->finish();

		/** @var Ruckusing_Adapter_PgSQL_TableDefinition $t */
		$t = $this->create_table($prefix.'nodes');
		$t->column('osm_node_id', 'text');
		$t->column('lat',         'float');
		$t->column('lon',         'float');
		$t->finish();

		/** @var Ruckusing_Adapter_PgSQL_TableDefinition $t */
		$t = $this->create_table($prefix.'restrictions');
		$t->column('from_node_id', 'integer');
		$t->column('to_node_id',   'integer');
		$t->column('via_node_id',  'integer');
		$t->column('is_only',      'boolean');
		$t->finish();
	}

    public function down() {
		$prefix = 'phpr_';
		$this->drop_table($prefix.'edges');
		$this->drop_table($prefix.'names');
		$this->drop_table($prefix.'nodes');
		$this->drop_table($prefix.'restrictions');
	}
}
