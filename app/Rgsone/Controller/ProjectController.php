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

use Netcarver\Textile\Parser;
use Rgsone\Config;
use Rgsone\Controller\BaseController;
use Rgsone\Filesystem\FileList;
use Rgsone\Filter\Sanitize;
use Rgsone\Model\ProjectList;

/**
 * Class ProjectController
 * @package Rgsone\Controller
 */
class ProjectController extends BaseController
{
	############################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////////
	############################################################################

	/** @var \Rgsone\Model\ProjectList */
	protected $_projects;

	############################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////////
	############################################################################

	public function showProject( $urlName )
	{
		$fileList = new FileList( PATH_DATA, Config::DATA_EXT );
		$fileList->parse();

		$this->_projects = new ProjectList( $fileList );

		$project = $this->_projects->searchProjectByUrlName( $urlName );

		# project doesn't exist
		if ( !$project )
		{
			$this->notFound();
		}

		$textileParser = new Parser( 'html5' );
		$textileParser->setRelativeImagePrefix( $this->_baseUrl . '/' );
		$textileParser->setDimensionlessImages();

		$this->render( 'project', array(
			'project' => array(
				'title' => Sanitize::escape( $project->getTitle() ),
				'role' => Sanitize::escape( $project->getRole() ),
				'year' => $project->getYear(),
				'content' => $project->getTextileContent( $textileParser )
			)
		));
	}
} 
