<?php

/****************************************************************************
* Cakephp AutocacheBehavior
* Nicholas de Jong - http://nicholasdejong.com - https://github.com/ndejong
* 26 November 2011
****************************************************************************/

class AutocacheBehavior extends ModelBehavior {

	/**
	 * $runtime - stores runtime configuration parameters
	 * 
	 * @var array 
	 */
	public $runtime = array();

	/**
	 * $cached_results
	 * 
	 * @var array
	 */
	public $cached_results = null;

	/**
	 * $cachename_prefix
	 * 
	 * @var string 
	 */
	public $cachename_prefix = 'autocache';

	/**
	 * setup
	 * 
	 * @param Model $model
	 * @param array $config 
	 */
	public function setup(Model $model, $config = array()) {
		// > default - is the default cache name, which by default is the
		// string "default" - confused?  You just need to make sure you
		// have an appropriate Cache::config('default',array(...)) in your
		// bootstrap.php or core.php
		//
		// > check - determines if we bother checking if the supplied
		// cache configuration name is valid - prevents the developer
		// thinking they are caching when they are not - will throw a
		// cache expection if fails this check
		//
		// > dummy - name of the dummy data source in the database.php file
		// should look something like this:-
		// public $dummy = array('datasource' => 'DummySource');
		// be sure you have a Model/Datasource/DummySource.php

		$this->runtime = array_merge(array(
			'default_cache'     => 'default',   // default cache config name
			'check_cache'       => true,        // check if the named cache config is loaded
			'dummy_datasource'  => 'dummy',     // name of the dummy datasource config name
		), (array )$config);
	}

	/**
	 * beforeFind
	 * 
	 * @param Model $model
	 * @param array $query 
	 */
	public function beforeFind(Model $model, $query) {

		// Determine if we are even going to try using the cache
		if (!isset($query['cache']) || $query['cache'] === false) {
			return true; // return early as we have nothing to do
		}

		// Do the required cache query setup
		$this->_doCachingRuntimeSetup($model, $query);

		// Load cached results if they are available
		$this->_loadCachedResults();

		// Return the cached results if they exist
		if ($this->cached_results) {

			// Note the original useDbConfig
			$this->runtime['useDbConfig'] = $model->useDbConfig;

			// Use a dummy database connection to prevent any query
			$model->useDbConfig = $this->runtime['dummy_datasource'];
		}

		return true;
	}

	/**
	 * afterFind
	 * 
	 * @param Model $model
	 * @param array $results
	 */
	public function afterFind(Model $model, $results) {

		//debug($model);
		//debug($results);

		// Check if we set useDbConfig in beforeFind above
		if (isset($this->runtime['useDbConfig'])) {

			// reset the useDbConfig attribute back to what it was
			$model->useDbConfig = $this->runtime['useDbConfig'];

			// return the cached results
			return $this->cached_results;
		}

		// Cache the result if there is a config defined
		if (isset($this->runtime['config'])) {
			Cache::write($this->runtime['name'], $results, $this->runtime['config']);
		}

		return $results;
	}

	/**
	 * doCachingRuntimeSetup
	 * 
	 * @param Model $model
	 * @param array $query 
	 */
	protected function _doCachingRuntimeSetup(Model $model, &$query) {

		// Treat the cache config as a named cache config
		if (is_string($query['cache'])) {
			$this->runtime['config'] = $query['cache'];
			$this->runtime['name'] = $this->_generateCacheName($model, $query);

			// All other cache setups
		} else {

			// Manage the cache config
			if (isset($query['cache']['config']) && !empty($query['cache']['config'])) {
				$this->runtime['config'] = $query['cache']['config'];
			} else {
				$this->runtime['config'] = $this->runtime['default_cache'];
			}

			// Manage the cache name
			if (isset($query['cache']['name']) && !empty($query['cache']['name'])) {
				$this->runtime['name'] = $query['cache']['name'];
			} else {
				$this->runtime['name'] = $this->_generateCacheName($model, $query);
			}
		}

		// Check the cache config really exists, else no caching is going to happen
		if ($this->runtime['check_cache'] && !Configure::read('Cache.disable') && !Cache::config($this->runtime['config'])) {
			throw new CacheException('Attempting to use undefined cache configuration ' . $this->runtime['config']);
		}

		// Cache flush control
		if (isset($query['cache']['flush']) && $query['cache']['flush'] === true) {
			$this->runtime['flush'] = true;
		}
	}

	/**
	 * _generateCacheName
	 * 
	 * @param Model $model 
	 * @param array $query 
	 */
	protected function _generateCacheName(Model $model, $query) {

		if (isset($query['cache'])) {
			unset($query['cache']);
		}

		// NOTE #1: we include the SERVER_NAME as a part of the generated
		// name since it is possible to have more than one CahePHP site
		// running on the same webserver and thus it possible to have
		// the same query among them - learnt this the hard way - NdJ

		// NOTE #2: we use json_encode because it is faster than php serialize()

		return $this->cachename_prefix . '_' . $model->findQueryType . $model->alias . '_' . md5(env('SERVER_NAME') . json_encode($query));
	}

	/**
	 * _loadCachedResults
	 */
	protected function _loadCachedResults() {

		// Flush the cache if required
		if (isset($this->runtime['flush']) && true === $this->runtime['flush']) {
			Cache::delete($this->runtime['name'], $this->runtime['config']);
			$this->cached_results = false;
		}

		// Catch the cached result
		$this->cached_results = Cache::read($this->runtime['name'], $this->runtime['config']);
	}

}
