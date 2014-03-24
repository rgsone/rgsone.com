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

use Rgsone\Config;
use Rgsone\Routing\ControllerConnector;
use Rgsone\View\SlimMustacheWrapper;
use Slim\Slim;

/**
 * Class Engine
 * @package Rgsone
 */
class Engine
{
	############################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////////
	############################################################################

	/** @var \Slim\Slim Instance of Slim framework */
	protected $_slimApp;
	/** @var \Rgsone\Routing\ControllerConnector */
	protected $_connector;

	############################################################################
	//// CONSTRUCTOR ///////////////////////////////////////////////////////////
	############################################################################

	public function __construct()
	{
		$this->_slimApp = new Slim();
		$this->_connector = new ControllerConnector( $this->_slimApp );
	}

	############################################################################
	//// PRIVATE METHOD ////////////////////////////////////////////////////////
	############################################################################

	protected function init()
	{
		$this->setConf();
		$this->setRoutes();
	}

	protected function setConf()
	{
		setlocale( LC_ALL, 'fr_FR.UTF-8', 'fr_FR', 'french', 'fr', 'fra_fra' );
		ini_set( 'default_charset', 'UTF-8' );
		date_default_timezone_set( 'Europe/Paris' );

		if ( !Config::DEBUG )
		{
			error_reporting( 0 );
		}

		$this->_connector->setNamespacePrefix( '\\Rgsone\\Controller' );

		# slim conf
		$this->_slimApp->config(array(
			'mode' => Config::ENV,
			'debug' => Config::DEBUG,
			'templates.path' => PATH_TPL,
			'view' => new SlimMustacheWrapper( PATH_TPL, null, PATH_CACHE, Config::TEMPLATE_EXT )
		));
	}

	protected function setRoutes()
	{
		## show home

		$this->_connector
			->connect( 'GET', '/', 'HomeController:showHome' )
			->name( Config::ROUTE_HOME );

		## show project

		$this->_connector
			->connect( 'GET', '/projet/:name/', 'ProjectController:showProject' )
			->conditions(array(
				'name' => '[0-9a-z_-]+'
			))
			->name( Config::ROUTE_PROJECT );

		## show 404

		$this->_slimApp->notFound( function() {
			$this->_connector->register( 'NotFoundController' );
			$this->_connector->callAction( 'NotFoundController', 'show404' );
		});
	}

	############################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////////
	############################################################################

	/** Run engine */
	public function run()
	{
		$this->init();
		$this->_slimApp->run();
	}
} 
