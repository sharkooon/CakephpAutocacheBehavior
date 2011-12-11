<?php

/****************************************************************************
* Cakephp AutocacheBehavior
* Nicholas de Jong - http://nicholasdejong.com - https://github.com/ndejong
* 26 November 2011
****************************************************************************/

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
	 * 
	 * @return bool 
	 */
	function isConnected() {
		return true;
	}
}
