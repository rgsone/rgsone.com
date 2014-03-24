<?php

###########################################################
## ===================================================== ##
## |
## |	RGSONE / SITE
## |
## |	@author	rudy marc
## |	@email	rgs@rgsone.com
## |	@web	http://rgsone.com
## |
## ===================================================== ##
###########################################################

#### const ####

define( 'DS', DIRECTORY_SEPARATOR );

define( 'PATH_ROOT', dirname( __DIR__ ) . DS );
define( 'PATH_DATA', PATH_ROOT . 'data' . DS );
define( 'PATH_PUBLIC', PATH_ROOT . 'public' . DS );
define( 'PATH_APP', PATH_ROOT . 'app' . DS );
define( 'PATH_CACHE', PATH_APP . 'cache' . DS );
define( 'PATH_TPL', PATH_APP . 'template' . DS );

#### autoload ####

require_once( PATH_APP . 'libs' . DS . 'autoload.php' );

#### let's go !! ####

$app = new \Rgsone\Engine();
$app->run();
