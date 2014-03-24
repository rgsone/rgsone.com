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

namespace Rgsone\View;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Rgsone\Filter\Sanitize;
use Slim\View;

/**
 * Class SlimMustacheWrapper
 * @package Rgsone\View
 */
class SlimMustacheWrapper extends View
{
	############################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////////
	############################################################################

	/** @var \Mustache_Engine */
	private $_mustache;

	############################################################################
	//// CONSTRUCTOR ///////////////////////////////////////////////////////////
	############################################################################

	/**
	 * @param string $templatePath
	 * @param null|string $templatePartialsPath
	 * @param null|string $cachePath
	 * @param string $templateExt
	 */
	public function __construct( $templatePath, $templatePartialsPath = null, $cachePath = null, $templateExt = '.mustache' )
	{
		parent::__construct();

		$fsLoaderOptions = array(
			'extension' => $templateExt
		);

		$mustacheOptions = array(
			'loader' => new Mustache_Loader_FilesystemLoader( $templatePath, $fsLoaderOptions ),
			'escape' => function( $value ) { return Sanitize::escape( $value ); },
			'charset' => 'UTF-8'
		);

		if ( null !== $templatePartialsPath )
		{
			$mustacheOptions['partials_loader'] = new Mustache_Loader_FilesystemLoader( $templatePartialsPath, $fsLoaderOptions );
		}

		if ( null !== $cachePath )
		{
			$mustacheOptions['cache'] = $cachePath;
			$mustacheOptions['cache_file_mode'] = 0666;
		}

		$this->_mustache = new Mustache_Engine( $mustacheOptions );
	}

	############################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////////
	############################################################################

	/**
	 * @param string $template
	 * @return string
	 */
	public function render( $template, $data = null )
	{
		return $this->_mustache->render( $template, $this->data );
	}
} 
