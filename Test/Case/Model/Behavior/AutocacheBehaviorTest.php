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
	var $fixtures = array('core.article', 'core.user'); 

	/**
	 * startTest
	 *
	 * @return void
	 */
	public function startTest() {
		ConnectionManager::create('autocache', array('datasource' => 'Autocache.AutocacheSource'));
		Cache::config('default', array('engine'=>'File', 'path'=>TMP, 'duration'=>'+1 hour'));
		Cache::clear();
		//$this->DatabaseDb = ConnectionManager::getDataSource('default');
		$this->Article = ClassRegistry::init('Article');
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
	 * testGetCached
	 *
	 * @return void
	 */ 
	public function testGetCached() {
		$result = $this->Article->find('first', array('cache'=>true));
		
		debug($result);
		ob_flush();
		$this->assertTrue(!empty($result));
		$this->assertSame(1, $this->_queryCount());
		
		# check if filename starting with "cake_autocache_first_article_" exists
		//TODO
		
		# get cached result - no additional db query
		$result = $this->Article->find('first', array('cache'=>true));
		$this->assertTrue(!empty($result));
		$this->assertSame(1, $this->_queryCount());
		
	}
	
	/**
	 * testGetCachedWithContainable
	 * - Article JOIN User
	 *
	 * @return void
	 */
	public function testGetCachedWithContainable() {
		//TODO
	}




	/**
	 * return all queries
	 *
	 * @return array
	 * @access protected
	 */
	protected function _queries() {
		$res = $this->db->getLog(false, false);
		$queries = $res['log'];
		$return = array();
		foreach ($queries as $row) {
			if (strpos($row['query'], 'DESCRIBE') === 0) {
				continue;
			}
			$return[] = $row['query'];
		}
		return $return;
	}

	/**
	 * return number of queries executed
	 *
	 * @return int
	 * @access protected
	 */
	protected function _queryCount() {
		return count($this->_queries());
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
	public $actsAs = array('Autocache.Autocache');
	
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
	public $actsAs = array('Autocache.Autocache');

}