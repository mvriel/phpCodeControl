<?php

/**
 * stOfc class.
 *
 * Provides functions to create charts based on PHP Ofc Object
 *
 * @package    stOfcPlugin
 * @author     RASHID Dawood <daud55@gmail.com>
 */

/**
 * Plugin
 */

//include_once sfConfig::get('st_ofc_object');
include_once ('ofc/open_flash_chart_object.php');

class stOfc
{
	/**
	 * Creates HTML String using given parameters.
	 *
	 * @author Dawood RASHID
	 * @since 25 mars 2009
	 *
	 * @param String $width
	 * @param String $height
	 * @param String $url
	 * @param Boolean $use_swfobject
	 * @param String $base
	 * @return String HTML as a string
	 */
	public static function createChartToString($width, $height, $url, $useSwfObject = true, $base = '')
	{
		$url = url_for();
		$base = self::getBaseDir();

		return _ofc($width, $height, $url, $useSwfObject, $base);
	}

	/**
	 * Creates chart using given parameters.
	 *
	 * @author Dawood RASHID
	 * @since 25 mars 2009
	 *
	 * @param String $width
	 * @param String $height
	 * @param String $url
	 * @param Boolean $use_swfobject
	 * @param String $base
	 * @return stream the HTML into the page
	 */
	public static function createChart($width, $height, $url, $useSwfObject = true, $base = '')
	{
		$url = url_for($url);
		$base = self::getBaseDir();
		echo _ofc( $width, $height, $url, $useSwfObject, $base );
	}

	/**
	 * Get base URL dir for plugin.
	 *
	 * @return String
	 */
	protected static function getBaseDir()
	{
		return public_path('stOfcPlugin/images/');
	}
}