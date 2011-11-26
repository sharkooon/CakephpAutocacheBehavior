<?php

Configure::write('App.encoding', 'UTF-8');

date_default_timezone_set('UTC');
define('LOG_ERROR', 2);

Configure::write('debug', 2);

Configure::write('Error', array(
        'handler' => 'ErrorHandler::handleError',
        'level' => E_ALL & ~E_DEPRECATED,
        'trace' => true
));

Configure::write('Exception', array(
        'handler' => 'ErrorHandler::handleException',
        'renderer' => 'ExceptionRenderer',
        'log' => true
));

Configure::write('Security.level', 'medium');
Configure::write('Security.salt', 'there_is_no_security_here_we_are_just_testing');
Configure::write('Security.cipherSeed', '11111111111111111111111111111');

Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');

Configure::write('Session', array( 'defaults' => 'php' ));

Configure::write('Cache.disable', false);
//Configure::write('Cache.check', true); // relates to view level caching

Configure::write('Cache.engine','File');
//Configure::write('Cache.engine','Apc');

Configure::write('Cache.duration','+60 seconds');

/**
 * Configure the cache used for general framework caching.  Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
	'engine'        => Configure::read('Cache.engine'),
	'prefix'        => 'cake_core_',
	'path'          => CACHE . 'persistent' . DS,
	'serialize'     => (Configure::read('Cache.engine') === 'File'),
	'duration'      => Configure::read('Cache.duration')
));

/**
 * Configure the cache for model and datasource caches.  This cache configuration
 * is used to store schema descriptions, and table listings in connections.
 */
Cache::config('_cake_model_', array(
	'engine'        => Configure::read('Cache.engine'),
	'prefix'        => 'cake_model_',
	'path'          => CACHE . 'models' . DS,
	'serialize'     => (Configure::read('Cache.engine') === 'File'),
	'duration'      => Configure::read('Cache.duration')
));

