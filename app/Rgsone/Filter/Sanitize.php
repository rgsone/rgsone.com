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

namespace Rgsone\Filter;

/**
 * Class Sanitize
 * @package Rgsone\Filter
 */
class Sanitize
{
	############################################################################
	//// PRIVATE METHOD ////////////////////////////////////////////////////////
	############################################################################

	/**
	 * @param string $string
	 * @return string
	 */
	private static function transliterate( $string )
	{
		# chars list and substitution
		$chars = array(
			'/Æ|Ǽ/' 							=> 'AE',
			'/æ|ǽ/' 							=> 'ae',
			'/Œ/' 								=> 'OE',
			'/œ/' 								=> 'oe',
			'/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ|Ä|А/' 		=> 'A',
			'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|а|ä/' 		=> 'a',
			'/@/' 								=> 'at',
			'/Ç|Ć|Ĉ|Ċ|Č/' 						=> 'C',
			'/ç|ć|ĉ|ċ|č/' 						=> 'c',
			'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Е|Ё/' 			=> 'E',
			'/è|é|ê|ë|ē|ĕ|ė|ę|ě|е|ё/' 			=> 'e',
			'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ/' 			=> 'I',
			'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į/' 				=> 'i',
			'/Ñ|Ń|Ņ|Ň/' 						=> 'N',
			'/ñ|ń|ņ|ň/' 						=> 'n',
			'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|О/' 		=> 'O',
			'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|о|ö/' 		=> 'o',
			'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ü/'	=> 'U',
			'/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|ü/'	=> 'u'
		);

		$string = preg_replace( array_keys( $chars ), array_values( $chars ), $string );

		return $string;
	}

	############################################################################
	//// PUBLIC METHOD /////////////////////////////////////////////////////////
	############################################################################

	/**
	 * Escape a string
	 * @param string $string
	 * @param boolean $convertNL Convert new line to <br> ?
	 * @return string
	 */
	public static function escape( $string, $convertNL = false )
	{
		$string = htmlspecialchars( $string, ENT_COMPAT|ENT_HTML5, 'UTF-8' );
		$string = ( $convertNL ) ? nl2br( $string, false ) : $string;
		return $string;
	}

	/**
	 * Trim a string
	 * @param string $string
	 * @return string
	 */
	public static function trim( $string )
	{
		return trim( $string );
	}

	/**
	 * Trim and escape a string
	 * @param string $string
	 * @param boolean $convertNL Convert new line to <br> ?
	 * @return string
	 */
	public static function trimAndEscape( $string, $convertNL = false )
	{
		return self::escape( self::trim( $string ), $convertNL );
	}

	/**
	 * Slugify a string
	 * @param string $string
	 * @param string $separator
	 * @return string
	 */
	public static function slugify( $string, $separator = '-' )
	{
		# transliterate chars in ascii basic (ex: é > e, à > a, ç > c)
		$string = self::transliterate( $string );
		# lowercase
		$string = ( function_exists( 'mb_strtolower' ) ) ? mb_strtolower( $string, 'utf-8' ) : strtolower( $string );
		# replace none letters/digits by separator
		$string = preg_replace( '/[^\p{L}\d-]+/u', $separator, $string);
		# last cleanup
		$string = preg_replace( '/[^\w-]+/', '', $string );
		# trim separator
		$string = trim( $string, $separator );

		# if string is empty
		if ( '' === $string )
		{
			return 'na';
		}

		return $string;
	}
} 
