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

namespace Rgsone\Controller;

use Rgsone\Config;

/**
 * Class BaseController
 * @package Logs\Controller
 */
abstract class BaseController
{
	############################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////////
	############################################################################

	/** @var \Slim\Slim Instance of Slim Framework */
	protected $_slimApp;
	/** @var \Slim\Environment */
	protected $_environment;
	/** @var \Slim\Http\Request */
	protected $_request;
	/** @var \Slim\Http\Response */
	protected $_response;
	/** @var string Url domain > http://www.domain.com */
	protected $_domainUrl;
	/** @var string Base url > http://www.domain.com/base/ */
	protected $_baseUrl;

	############################################################################
	//// CONSTRUCTOR ///////////////////////////////////////////////////////////
	############################################################################

	/**
	 * @param \Slim\Slim $slim
	 */
	public function __construct( \Slim\Slim $slim )
	{
		if ( null == $slim )
		{
			throw new \InvalidArgumentException( 'Slim instance is required' );
		}

		$this->_slimApp = $slim;
		$this->_environment = $this->_slimApp->environment();
		$this->_request = $this->_slimApp->request();
		$this->_response = $this->_slimApp->response();

		$this->_domainUrl = $this->_request->getUrl();
		$this->_baseUrl = $this->_domainUrl . $this->_request->getRootUri();
	}

	############################################################################
	//// PRIVATE METHOD ////////////////////////////////////////////////////////
	############################################################################

	/**
	 * @param string $type
	 */
	protected function contentType( $type )
	{
		$this->_slimApp->contentType( $type );
	}

	/**
	 * Show 404 page
	 */
	protected function notFound()
	{
		$this->_slimApp->notFound();
	}

	/**
	 * Create url for a required route
	 * @param string $routeName
	 * @param array $params
	 * @return string
	 */
	protected function getUrlFor( $routeName, $params = array() )
	{
		return $this->_slimApp->urlFor( $routeName, $params );
	}

	/**
	 * Stop immediatly the slim application
	 * @param $status
	 * @param string $message
	 */
	protected function halt( $status, $message = '' )
	{
		$this->_slimApp->halt( $status, $message );
	}

	/**
	 * Perform a redirection
	 * @param string $url
	 * @param int $status
	 */
	protected function redirect( $url, $status = 302 )
	{
		$this->_slimApp->redirect( $url, $status );
	}

	/**
	 * Render a view
	 * @param string $template
	 * @param array $data
	 * @param null $status
	 */
	protected function render( $template, $data = array(), $status = null )
	{
		# var injection
		$this->_slimApp->view()->appendData(array(
			'slimApp' => $this->_slimApp,
			'resourcePath' => $this->_request->getRootUri(),
			'homeUrl' => $this->getUrlFor( Config::ROUTE_HOME )
		));

		# render
		$this->_slimApp->render( $template, $data, $status );
	}

	/**
	 * Set the HTTP response status code
	 * @param int $code
	 */
	protected function status( $code )
	{
		$this->_slimApp->status( $code );
	}
} 
