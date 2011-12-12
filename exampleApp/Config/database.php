<?php

class DATABASE_CONFIG {
        
        /**
         * $autocache - the AutocacheSource is *required* to make Autocache work
         * it is used as a dummy datasource to short-circuit model find queries 
         * when a cached value exists - you do NOT use this as your datasource!
         * 
         * @var array
         */
        public $autocache = array(
            'datasource' => 'AutocacheSource'
        );
        
	/**
         * $default - an example database connection
         * 
         * @var array
         */
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
        
	/**
         * $test - database configuration for PHP Unit tests
         * 
         * @var array
         */
        public $test = array(
            'datasource'    => 'Database/Mysql',
            'persistent'    => false,
            'host'          => '127.0.0.1',
	    'login'         => 'test',
	    'password'      => 'test',
	    'database'      => 'test',
	    'prefix'        => 'test_',
	    'encoding'      => 'utf8',
	);
}
