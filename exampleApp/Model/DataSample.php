<?php

App::uses('AppModel', 'Model');

/**
 * DataSample Model
 *
 */
class DataSample extends AppModel {

        // Enable Autocache for this Model by un-commenting $actsAs below
        // public $actsAs = array('Autocache');
        
        /**
         * createSample - just a quick fat-model style method to generate some random sample data
         */
        function createSample() {
                
                $string = rand().rand().rand().rand().rand().rand().rand().rand().rand().rand().rand().rand();
                $string = $string.$string.$string.$string.$string.$string.$string.$string.$string.$string;

                $data = array(
                    'DataSample' => array(
                        'key' => md5(microtime(true)),
                        'value' => $string,
                    )
                );

                $this->id = null;
                $this->save($data);
        }

}
