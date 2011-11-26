<?php

/**
 * DummySource
 */
class DummySource extends DataSource {
        
        /**
         * __construct
         * 
         * @param array $config 
         */
        public function __construct($config = array()) {
                parent::__construct($config);
        }
        
        /**
         * isConnected
         * @return bool 
         */
        function isConnected() {
                return true;
        }
}
