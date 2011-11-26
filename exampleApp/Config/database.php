<?php

class DATABASE_CONFIG {
        
	public $default = array(
		'datasource'    => 'Database/Mysql',
		'persistent'    => false,
		'host'          => '127.0.0.1',
		'login'         => 'test',
		'password'      => 'test',
		'database'      => 'test',
		'prefix'        => '',
		'encoding'      => 'utf8',
	);
        
        // NOTE: the DummySource is *required* to make Autocache work - it is 
        // used to short-circuit model find queries when a cached value exists
        public $dummy = array('datasource' => 'DummySource');
}
