<?php

// Setup a 'default' cache configuration for use in the application.
// Cache::config('default', array('engine' => 'File'));

Cache::config('default', array(
        'engine'        => Configure::read('Cache.engine'),
        'duration'      => Configure::read('Cache.duration'),
        'probability'   => 99,
        'path'          => CACHE,
        'prefix'        => 'cake_',
        'lock'          => false,
        'serialize'     => true,
));
