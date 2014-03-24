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

namespace Rgsone;

/**
 * Class Config
 * @package Rgsone
 */
class Config
{
	## routes name

	const ROUTE_HOME 	 = 'route.home';
	const ROUTE_PROJECT = 'route.project';

	#### base conf

	/** @var string Environment 'dev' or 'prod' */
	const ENV = 'dev';
	/** @var bool Debug mode true or false */
	const DEBUG = true;
	/** @var string Extension of template file */
	const TEMPLATE_EXT = '.tmpl';
	/** @var string Data files extension */
	const DATA_EXT = 'tx';
} 
