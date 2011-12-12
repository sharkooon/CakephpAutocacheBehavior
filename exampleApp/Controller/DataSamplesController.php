<?php

App::uses('AppController', 'Controller');

/**
 * DataSamples Controller
 *
 * @property DataSample $DataSample
 */
class DataSamplesController extends AppController {
        
        /**
         * beforeFilter
         */
        public function beforeFilter() {
                
                parent::beforeFilter();
                
                /**
                 * We are attaching the Autocache Behavior via the controller,
                 * however it could just as easily be loaded within the model
                 * or globally in the AppModel.php - take your pick!
                 */
                
                $this->DataSample->Behaviors->attach('Autocache');
                
                /**
                $this->DataSample->Behaviors->attach('Autocache',array(
                    'default_cache'     => 'default',   // default cache config name
                    'check_cache'       => true,        // check if the named cache config is loaded
                    'dummy_datasource'  => 'autocache', // name of the autocache dummy datasource config name
                ));
                **/
                
        }

        /**
         * index method
         */
        public function index() {
        }
        
        /**
         * example_a
         */
        public function example_a() {
                
                $cache = null;
                //$cache = false;
                $this->_example($cache);
        }
        
        /**
         * example_b
         */
        public function example_b() {
                
                $cache = true;
                $this->_example($cache);
        }
        
        /**
         * example_c
         */
        public function example_c() {
                
                $cache = 'default';
                $this->_example($cache);
        }
        
        /**
         * example_d
         */
        public function example_d() {
                
                $cache = array('config'=>'default');
                $this->_example($cache);
        }
        
        /**
         * example_e
         */
        public function example_e() {
                
                $cache = array('config'=>'foobar');
                $this->_example($cache);
        }
        
        /**
         * example_f
         */
        public function example_f() {
                
                $cache = array('name'=>'a_name_for_a_cache');
                $this->_example($cache);
        }
        
        /**
         * example_f2
         */
        public function example_f2() {
                
                $cache = array('config'=>'default','name'=>'a_name_for_a_cache');
                $this->_example($cache);
        }
        
        /**
         * example_g
         */
        public function example_g() {
                
                $cache = array('flush'=>true);
                $this->_example($cache);
        }
        
        /**
         * example_h
         */
        public function example_h() {
                
                $cache = array('config'=>'default','flush'=>true);
                $this->_example($cache);
        }
        
        /**
         * example_perf
         */
        public function example_perf($iterations=1000) {
                
                // Disable CakePHP in-memory model caching.
                $this->DataSample->cacheQueries = false;
                
                // Don't get confused by the CakePHP cacheQueries model attribute
                // as it only relates to query caching per single web-request 
                // whereas Autocache relates to caching *between* multiple web requests!
                
                $time = array();
                
                // query without cache
                $time['no_cache']['start'] = microtime(true);
                for($count=0;$count<$iterations;$count++) {
                        $no_cache = $this->DataSample->find('all');
                }
                $time['no_cache']['finish'] = microtime(true);
                $time['no_cache']['diff'] = $time['no_cache']['finish'] - $time['no_cache']['start'];
                
                // preload the Autocache
                $pre_cache = $this->DataSample->find('all',array('cache'=>true));
                
                // request using the Autocache
                $time['with_cache']['start'] = microtime(true);
                for($count=0;$count<$iterations;$count++) {
                        $with_cache = $this->DataSample->find('all',array('cache'=>true));
                }
                $time['with_cache']['finish'] = microtime(true);
                $time['with_cache']['diff'] = $time['with_cache']['finish'] - $time['with_cache']['start'];
                
                // speedup_factor
                $time['speedup_factor'] = $time['no_cache']['diff'] / $time['with_cache']['diff'];
                
                $this->set('time',$time);
        }
        
        /**
         * _example
         * 
         * @param type $cache 
         */
        protected function _example($cache=null) {
                
                $data = array(
                        'count'         => $this->DataSample->find('count',array('cache'=>$cache)),
                        'first'         => $this->DataSample->find('first',array('cache'=>$cache)),
                        'all_limit_2'   => $this->DataSample->find('all',array('limit'=>2,'cache'=>$cache)),
                );
                
                $this->set('cache',$cache);
                $this->set('data',$data);
                $this->render('example');
        }
        
        
        /**
         * generate_random
         * 
         * @param int $count 
         */
        public function generate_random($count=10) {
                
                $total = 0;
                while($total<$count) {
                        $this->DataSample->createSample();
                        $total++;
                }
                
                $this->redirect(array('action'=>'index'));
        }

}
