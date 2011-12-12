<?php
/**
 * Test Case by Mark Scherer
 * testing ndejong's Behavior
 */

App::uses('Model', 'Model');
App::uses('ModelBehavior', 'Model');

/**
 * AutocacheTestCase
 *
 * @package search
 * @subpackage search.tests.cases.behaviors
 */
class AutocacheTestCase extends CakeTestCase { 

	/**
	 * Fixtures used in the SessionTest
	 *
	 * @var array
	 */
	var $fixtures = array('core.Article', 'core.User'); 
        
        /**
         * $cache_path - path location of the cache files
         * 
         * @var string
         */
        var $cache_path = null;

	/**
	 * startTest
	 *
	 * @return void
	 */
	public function startTest() {
                
                $this->cache_path = CACHE . 'models' . DS;
                
		Cache::config('default', array(
                    'prefix' => 'cake_',
                    'engine'=>'File',
                    'path' => $this->cache_path,
                    'duration'=>'+1 hour'
                ));
                
		$this->Article = ClassRegistry::init('Article');
		$this->User = ClassRegistry::init('User');
	}

	/**
	 * endTest
	 *
	 * @return void
	 */
	public function endTest() {
		unset($this->Article);
	}
	
	/**
	 * testGetCachedTrue
	 *
	 * @return void
	 */ 
	public function testGetCachedTrue() {
                $cache = true;
                $this->_cacheTest($cache);
	}
        
	/**
	 * testGetCachedNamedConfigString
	 *
	 * @return void
	 */ 
	public function testGetCachedNamedConfigString() {
                $cache = 'default';
                $this->_cacheTest($cache);
	}
        
	/**
	 * testGetCachedNamedConfig
	 *
	 * @return void
	 */ 
	public function testGetCachedNamedConfig() {
                $cache = array('config'=>'default');
                $this->_cacheTest($cache);
	}
        
	/**
	 * testGetCachedNamedConfigName
	 *
	 * @return void
	 */ 
	public function testGetCachedNamedConfigName() {
                $cache = array('name'=>'a_name_for_a_cache');
                $this->_cacheTest($cache);
	}
        
	/**
	 * testGetCachedNamedConfigNameAndConfig
	 *
	 * @return void
	 */ 
	public function testGetCachedNamedConfigNameAndConfig() {
                $cache = array('config'=>'default','name'=>'a_name_for_a_cache');
                $this->_cacheTest($cache);
	}
        
	/**
	 * testFlushedCache
	 *
	 * @return void
	 */ 
	public function testFlushedCache() {
                $cache = array('flush'=>true);
                $this->_cacheTest($cache);
	}
        
	/**
	 * testFlushedCache
	 *
	 * @return void
	 */ 
	public function testFlushedWithConfig() {
                $cache = array('config'=>'default','flush'=>true);
                $this->_cacheTest($cache);
	}
        
        /**
         * _cacheTest
         * 
         * @param mixed $cache 
         * @return void
         */
        protected function _cacheTest($cache) {
                
		Cache::clear();
                
		// First query gets cached
                $result_1 = $this->Article->find('first', array('cache'=>$cache));
		
		//debug($result_1);
		//ob_flush();
                
		$this->assertTrue(!empty($result_1));
		$this->assertFalse($this->Article->is_from_autocache);
		
		# check if filename starting with "cake_autocache_first_article_" exists
                $files = glob($this->cache_path.'cake_autocache_first_article_*');
                if(is_array($cache) && isset($cache['name'])) {
                        $files = glob($this->cache_path.'cake_'.$cache['name'].'*');
                }
                $this->assertTrue((1===count($files))); // always 1 because Cache::clear(); is used above
		
		# Second query result should equal first query
		$result_2 = $this->Article->find('first', array('cache'=>$cache));
		$this->assertTrue(!empty($result_2));
                
                // Test result does not come from cache if flushed
                if(isset($cache['flush']) && true === $cache['flush']) {
                        $this->assertFalse($this->Article->is_from_autocache);
                } else {
                        $this->assertTrue($this->Article->is_from_autocache);
                }
                
                // Check the first query result is the same as the second
		$this->assertSame($result_1,$result_2);
        }
        
	/**
	 * testGetCachedWithContainable
	 * - Article JOIN User
	 *
	 * @return void
	 */
	public function testGetCachedWithContainable() {
                
		Cache::clear();
                
                $this->User->Behaviors->attach('Containable');
                
                $conditions = array(
                    'contain' => array('Article'),
                    'cache'=>true
                );
                
                $result_1 = $this->User->find('first',$conditions);
                
		$this->assertTrue(!empty($result_1));
		$this->assertFalse($this->User->is_from_autocache);
                
		# check if filename starting with "cake_autocache_first_article_" exists
                $files = glob($this->cache_path.'cake_autocache_first_user_*');
                $this->assertTrue((1===count($files))); // always 1 because Cache::clear(); is used above
                
		# Second query result should equal first query
		$result_2 = $this->User->find('first',$conditions);
		$this->assertTrue(!empty($result_2));
                
                $this->assertTrue($this->User->is_from_autocache);
                
                // Check the first query result is the same as the second
		$this->assertSame($result_1,$result_2);
	}
}


/**
 * User model
 *
 */
class User extends CakeTestModel {

	/**
	 * Behaviors
	 *
	 * @var array
	 */
	public $actsAs = array('Autocache');
        
        /**
         * hasMany associations
         *
         * @var array
         */
        public $hasMany = array(
            'Article' => array(
                'className' => 'Article',
                'foreignKey' => 'user_id',
            )
        );
}


/**
 * Article model
 *
 */
class Article extends CakeTestModel {

	/**
	 * Behaviors
	 *
	 * @var array
	 */
	public $actsAs = array('Autocache');

}