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

namespace Rgsone\Model;

use Rgsone\Model\Project;

/**
 * Class ProjectList
 * @package Rgsone\Model
 */
class ProjectList
{
	########################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////
	########################################################################

	/** @var \Rgsone\Filesystem\FileList */
	private $_fileList;
	/** @var array */
	private $_projects = array();
	/** @var int */
	private $_count = 0;

	########################################################################
	//// CONSTRUCTOR ///////////////////////////////////////////////////////
	########################################################################

	/**
	 * @param \Rgsone\Filesystem\FileList $list
	 */
	public function __construct( \Rgsone\Filesystem\FileList $list )
	{
		$this->_fileList = $list;
		$this->parse();
	}

	########################################################################
	//// PRIVATE METHOD ////////////////////////////////////////////////////
	########################################################################

	private function parse()
	{
		$files = $this->_fileList->getFiles();

		foreach( $files as $file )
		{
			$this->_projects[] = new Project( $file );
		}

		$this->_count = $this->_fileList->getCount();
	}

	########################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////
	########################################################################

	public function sortByDateDesc()
	{
		usort( $this->_projects, function( $a, $b ) {

			if ( $a->getTimestamp() == $b->getTimestamp() )
			{
				return 0;
			}

			return ( $a->getTimestamp() < $b->getTimestamp() ) ? 1 : -1;
		});
	}

	/**
	 * Return a Project from index
	 * @param int $index
	 * @return \Rgsone\Model\Project A Project object
	 * @throws \Exception
	 */
	public function getProjectByIndex( $index = 0 )
	{
		if ( $index < 0 || $index >= $this->_count )
		{
			throw new \Exception( 'out of array index' );
		}
		else
		{
			return $this->_projects[ $index ];
		}
	}

	/**
	 * Search and return the first Proejct from his url name.
	 * If search don't match, return false.
	 * @param string $urlName
	 * @return \Rgsone\Model\Project|bool
	 */
	public function searchProjectByUrlName( $urlName )
	{
		foreach ( $this->_projects as $project )
		{
			if( $project->getUrl() === $urlName )
			{
				return $project;
			}
		}

		return false;
	}

	/**
	 * @return array Project list
	 */
	public function getProjects()
	{
		return $this->_projects;
	}

	/**
	 * @return int Total projects number
	 */
	public function getCount()
	{
		return $this->_count;
	}
} 
