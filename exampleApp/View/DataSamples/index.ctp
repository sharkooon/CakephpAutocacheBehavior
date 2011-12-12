<?php

echo $this->Html->link('generate_random_test_data',array('action'=>'generate_random')).'<< returns immediately<br />';

echo $this->Html->link("no cache",array('action'=>'example_a')).'<br />';

echo $this->Html->link("cache = true",array('action'=>'example_b')).' << Autocache self arranges names and configurations, so model caching can be this easy if you like<br />';

echo $this->Html->link("cache = 'default'",array('action'=>'example_c')).' << the developer chooses the cache configuration to use<br />';

echo $this->Html->link("cache => array('config'=>'default')",array('action'=>'example_d')).' << same as example_c above<br />';

echo $this->Html->link("cache => array('config'=>'foobar')",array('action'=>'example_e')).'<< <span style="color:red">an expected error</span> because there is no cache defined by the name "foobar"<br />';

echo $this->Html->link("cache => array('name'=>'a_name_for_a_cache')",array('action'=>'example_f')).' << note the expected, but (probably) undesireable effect when using the same name for a cache on different queries - if you allow Autocache to choose a name for you it resolves the duplicate name problem since Autocache will pick a unique cache name per query<br />';

echo $this->Html->link("cache => array('config'=>'default','name'=>'a_name_for_a_cache')",array('action'=>'example_f2')).' << same as example_f above<br />';

echo $this->Html->link("cache => array('flush'=>true)",array('action'=>'example_g')).' << flush cache and and re-cache - useful when you are saving a new value and need to kill the old cached value<br />';

echo $this->Html->link("cache => array('config'=>'default','flush'=>true)",array('action'=>'example_h')).' << same as example_g above<br />';

echo $this->Html->link("example_perf",array('action'=>'example_perf')).' << performance comparision between cached and non-cached.<br />';

echo $this->Html->link("apc_admin",'/apc_admin').' << easy link to an APC admin console if you happen to be using it<br />';
