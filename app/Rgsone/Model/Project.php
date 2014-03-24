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

/**
 * Class Project
 * @package Rgsone\Model
 */
class Project
{
	########################################################################
	//// PRIVATE VAR ///////////////////////////////////////////////////////
	########################################################################

	/** @var int Line number for content start */
	private $_contentStartLine = 7;

	/** @var string */
	private $_fullpath = '';
	/** @var string Path without filename */
	private $_basepath = '';
	/** @var string */
	private $_filename = '';

	/** @var string Project title */
	private $_title = '';
	/** @var string Project role */
	private $_role = '';
	/** @var string Project URL */
	private $_url = '';
	/** @var string Project thumbnail */
	private $_thumbnail = '';
	/** @var bool Project showcase */
	private $_isShowcase;

	/** @var \DateTime Project date */
	private $_date;
	/** @var int Project timestamp */
	private $_timestamp;
	/** @var string Project year */
	private $_year;

	/** @var \SplFileObject */
	private $_fileObject;

	/** @var array Regex pattern for info parsing */
	private $_projectInfoPatterns = array(
		'title' => '/^title: (?P<title>.*)/',
		'role' => '/^role: (?P<role>.*)/',
		'date' => '/^date: (?P<date>[0-9]{2}-[0-9]{2}-[0-9]{4})/',
		'url' => '/^url: (?P<url>[a-zA-Z-0-9_-]+)/',
		'thumbnail' => '/^thumbnail: (?P<thumbnail>.*)/',
		'showcase' => '/^showcase: (?P<showcase>true|false)/'
	);

	########################################################################
	//// CONSTRUCTOR ///////////////////////////////////////////////////////
	########################################################################

	/**
	 * @param string $path Log full path
	 */
	public function __construct( $path = '' )
	{
		if ( empty( $path ) )
		{
			throw new \Exception( 'path is empty' );
		}

		$this->_fullpath = $path;
		$this->parse();
	}

	########################################################################
	//// PRIVATE METHOD ////////////////////////////////////////////////////
	########################################################################

	/**
	 * @throws \Exception
	 */
	private function parse()
	{
		try
		{
			$this->_fileObject = new \SplFileObject( $this->_fullpath );
		}
		catch( \RuntimeException $e )
		{
			throw new \Exception( 'File cannot be read' );
		}

		$this->_basepath = $this->_fileObject->getPath() . DS;
		$this->_filename = $this->_fileObject->getFilename();

		# 1st line of file > title
		$this->_fileObject->seek( 0 );

		if ( 1 !== preg_match( $this->_projectInfoPatterns['title'], trim( $this->_fileObject->current() ), $match ) )
		{
			throw new \Exception( 'Title is missing in ' . $this->_fullpath );
		}

		$this->_title = trim( $match['title'] );

		# next line > role
		$this->_fileObject->next();

		if ( 1 !== preg_match( $this->_projectInfoPatterns['role'], trim( $this->_fileObject->current() ), $match ) )
		{
			throw new \Exception( 'Role is missing in ' . $this->_fullpath );
		}

		$this->_role = trim( $match['role'] );

		# next line > date
		$this->_fileObject->next();

		if ( 1 !== preg_match( $this->_projectInfoPatterns['date'], trim( $this->_fileObject->current() ), $match ) )
		{
			throw new \Exception( 'Date is missing in ' . $this->_fullpath );
		}

		$this->_date = new  \DateTime( trim( $match['date'] ) );
		$this->_timestamp = $this->_date->getTimestamp();
		$this->_year = $this->_date->format( 'Y' );

		# next line > url
		$this->_fileObject->next();

		if ( 1 !== preg_match( $this->_projectInfoPatterns['url'], trim( $this->_fileObject->current() ), $match ) )
		{
			throw new \Exception( 'URL is missing in ' . $this->_fullpath );
		}

		$this->_url = trim( $match['url'] );

		# next line > thumbnail
		$this->_fileObject->next();

		if ( 1 !== preg_match( $this->_projectInfoPatterns['thumbnail'], trim( $this->_fileObject->current() ), $match ) )
		{
			throw new \Exception( 'Thumbnail is missing in ' . $this->_fullpath );
		}

		$this->_thumbnail = trim( $match['thumbnail'] );

		# next line > showcase
		$this->_fileObject->next();

		if ( 1 !== preg_match( $this->_projectInfoPatterns['showcase'], trim( $this->_fileObject->current() ), $match ) )
		{
			$this->_isShowcase = false;
		}
		else
		{
			$this->_isShowcase = ( trim( $match['showcase'] ) === 'true' ) ? true : false;
		}

		unset( $match );
	}

	########################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////
	########################################################################

	/**
	 * @return string Raw project content
	 */
	public function getContent()
	{
		$content = '';

		$this->_fileObject->rewind();
		$this->_fileObject->seek( $this->_contentStartLine );

		while( !$this->_fileObject->eof() )
		{
			$content .= $this->_fileObject->current();
			$this->_fileObject->next();
		}

		return $content;
	}

	/**
	 * @param \Netcarver\Textile\Parser $parser
	 * @return string Return formated textile content
	 */
	public function getTextileContent( \Netcarver\Textile\Parser $parser )
	{
		return $parser->textileThis( $this->getContent() );
	}

	########################################################################
	//// GETTER/SETTER /////////////////////////////////////////////////////
	########################################################################

	/** @return \DateTime */
	public function getDate() { return $this->_date; }

	/** @return string */
	public function getYear() { return $this->_year; }

	/** @return int */
	public function getTimestamp() { return $this->_timestamp; }

	/** @return string */
	public function getTitle() { return $this->_title; }

	/** @return string */
	public function getRole() { return $this->_role; }

	/** @return string */
	public function getUrl() { return $this->_url; }

	/** @return string */
	public function getThumbnail() { return $this->_thumbnail; }

	/** @return bool */
	public function getIsShowcase() { return $this->_isShowcase; }
} 
