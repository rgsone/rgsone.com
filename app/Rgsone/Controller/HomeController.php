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
use Rgsone\Controller\BaseController;
use Rgsone\Filesystem\FileList;
use Rgsone\Filter\Sanitize;
use Rgsone\Model\ProjectList;

/**
 * Class HomeController
 * @package Rgsone\Controller
 */
class HomeController extends BaseController
{
	############################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////////
	############################################################################

	/** @var \Rgsone\Model\ProjectList */
	protected $_projects;

	############################################################################
	//// PRIVATE METHOD ////////////////////////////////////////////////////////
	############################################################################

	/**
	 * @return array
	 */
	protected function parseProjects()
	{
		$projects = array(
			'showcase' => array(),
			'list' => array()
		);

		for ( $i = 0, $len = $this->_projects->getCount(); $i < $len; $i++ )
		{
			$project = $this->_projects->getProjectByIndex( $i );

			$projectData = array(
				'title' => Sanitize::escape( $project->getTitle() ),
				'year' => $project->getYear(),
				'thumbnail' => '/' . Sanitize::escape( $project->getThumbnail() ),
				'url' => $this->getUrlFor( Config::ROUTE_PROJECT, array( 'name' => $project->getUrl() ))
			);


			if ( $project->getIsShowcase() )
			{
				$projects['showcase'][] = $projectData;
			}
			else
			{
				$projects['list'][] = $projectData;
			}
		}

		return $projects;
	}

	############################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////////
	############################################################################

	public function showHome()
	{
		$fileList = new FileList( PATH_DATA, Config::DATA_EXT );
		$fileList->parse();

		$this->_projects = new ProjectList( $fileList );
		$this->_projects->sortByDateDesc();

		$this->render( 'home', array(
			'projects' => $this->parseProjects()
		));
	}
} 
