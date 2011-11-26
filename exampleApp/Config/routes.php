<?php

Router::connect('/', array('controller' => 'data_samples', 'action' => 'index'));

CakePlugin::routes();
require CAKE . 'Config' . DS . 'routes.php';
