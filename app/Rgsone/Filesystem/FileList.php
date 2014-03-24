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

namespace Rgsone\Filesystem;

/**
 * Class FileList
 * @package Rgsone\Filesystem
 */
class FileList
{
	########################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////
	########################################################################

	/** @var string Dir path */
	protected $_path;
	/** @var string File extension needed */
	protected $_fileExt;
	/** @var array File list */
	protected $_list = array();
	/** @var int List length */
	protected $_count;

	########################################################################
	//// CONSTRUCTOR ///////////////////////////////////////////////////////
	########################################################################

	/**
	 * @param string $path Directory path
	 * @param string|null $fileExtension Filter file extension if not null
	 */
	public function __construct( $path, $fileExtension = null )
	{
		$this->_path = $path;
		$this->_fileExt = $fileExtension;
	}

	########################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////
	########################################################################

	/**
	 * @throw \Exception
	 */
	public function parse()
	{
		try {
			$directoryIterator = new \RecursiveDirectoryIterator( $this->_path );
			$iterator = new \RecursiveIteratorIterator( $directoryIterator );
		}
		catch ( \Exception $e )
		{
			$e->getMessage();
		}

		foreach( $iterator as $item )
		{
			if ( null !== $this->_fileExt )
			{
				if ( $item->getExtension() === $this->_fileExt )
				{
					$this->_list[] = $item->getPath() . DS . $item->getFilename();
				}
			}
			else
			{
				$this->_list[] = $item->getPath() . DS . $item->getFilename();
			}
		}

		unset( $item, $iterator, $directoryIterator );

		$this->_count = count( $this->_list );
	}

	/**
	 * @return array File list
	 */
	public function getFiles()
	{
		return $this->_list;
	}

	/**
	 * @return int Number of files in list
	 */
	public function getCount()
	{
		return $this->_count;
	}
}
