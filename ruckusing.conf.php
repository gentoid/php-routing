<?php

//----------------------------
// DATABASE CONFIGURATION
//----------------------------

/*

Valid types (adapters) are Postgres & MySQL:

'type' must be one of: 'pgsql' or 'mysql'

*/

return array(
        'db' => array(
                'development' => array(
                        'type'      => 'pgsql',
                        'host'      => 'localhost',
                        'port'      => 5432,
                        'database'  => 'test',
                        'user'      => 'test',
                        'password'  => 'test',
                ),

        ),

        'migrations_dir' => array('default' => RUCKUSING_WORKING_BASE . '/migrations'),

        'db_dir' => RUCKUSING_WORKING_BASE . DIRECTORY_SEPARATOR . 'db',

        'log_dir' => RUCKUSING_WORKING_BASE . DIRECTORY_SEPARATOR . 'logs',

        'ruckusing_base' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/ruckusing/ruckusing-migrations'

);
