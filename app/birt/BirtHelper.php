<?php

/**
 * Defines the app_birt_BirtHelper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   BIRT
 * @version   SVN: $Id$
 */

if (!defined('BIRT_VIEWER'))
{
	define('BIRT_VIEWER', 'http://'. $_SERVER['SERVER_NAME'] . ':8080/birt-viewer');
}

if (!defined('BIRT_TEMPLATE_DIR'))
{
	define('BIRT_TEMPLATE_DIR', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'birt' . DIRECTORY_SEPARATOR . 'reports');
}

if (!defined('BIRT_COMPILE_DIR'))
{
	define('BIRT_COMPILE_DIR', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'birt' . DIRECTORY_SEPARATOR . 'reports_c');
}

if (!defined('BIRT_TEMP_DIR'))
{
	define('BIRT_TEMP_DIR', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'birt' . DIRECTORY_SEPARATOR . 'reports_tmp');
}

/**
 * @package BIRT
 */
class app_birt_BirtHelper
{

	/**
	 * Return an instantiated Smarty object.
	 * @return Smarty object
	 */
	public static function getSmarty()
	{
		require_once('Smarty/Smarty.class.php');
		$smarty = new Smarty();
		$smarty->compile_check = true;
		$smarty->template_dir  = BIRT_TEMPLATE_DIR;
		$smarty->compile_dir   = BIRT_COMPILE_DIR;
		
		// Caching
//		$smarty->cache_dir = SMARTY_CACHE_DIR;
//		$smarty->caching = true;
		$smarty->force_compile = true;
		
		// Debugging
//		$smarty->debugging = true;
//		$smarty->debug_tpl  = SMARTY_DIR . 'debug.tpl';
		
		return $smarty;
	}
	
	/**
	 * Return the temporary location.
	 * @return string
	 */
	public static function getTemporaryLocation()
	{
		return BIRT_TEMP_DIR;
	}

	/**
	 * Return the BIRT viewer location.
	 * @return string
	 */
	public static function getBirtViewerLocation()
	{
		return BIRT_VIEWER;
	}

}

?>