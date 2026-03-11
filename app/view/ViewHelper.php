<?php

/**
 * Defines the ViewHelper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/base/Registry.php');

if (!defined('SMARTY_TEMPLATE_DIR'))
{
	define('SMARTY_TEMPLATE_DIR', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'templates');
}
if (!defined('SMARTY_COMPILE_DIR'))
{
	define('SMARTY_COMPILE_DIR', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'templates_c');
}
if (!defined('SMARTY_CACHE_DIR'))
{
	define('SMARTY_CACHE_DIR', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'cache');
}

if (!defined('SMARTY_CONFIG_DIR'))
{
	define('SMARTY_CONFIG_DIR', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'config');
}

/**
 * @package Framework
 */
class ViewHelper
{

	/**
	 * Return the request.
	 * @return app_base_RequestRegistry 
	 */
	public static function getRequest()
	{
		return app_base_RequestRegistry::getRequest();
	}
	
	/**
	 * Return an instantiated Smarty object.
	 * @return Smarty object
	 */
	public static function getSmarty()
	{
		require_once('Smarty/Smarty.class.php');
		$smarty = new Smarty();
		
		// This is the name of the default template directory. If you do not supply a resource type when including 
		// files, they will be found here.
		$smarty->template_dir = SMARTY_TEMPLATE_DIR;
		
		// This is the name of the directory where compiled templates are located.
		$smarty->compile_dir = SMARTY_COMPILE_DIR;
		
		// Upon each invocation of the PHP application, Smarty tests to see if the current template has changed
		// (different time stamp) since the last time it was compiled. If it has changed, it recompiles that template.
		// If compile_check is FALSE (e.g. production), after uploading new .tpl files either set compile_check to TRUE
		// temporarily or clear the compiled templates (delete app/view/templates_c/*) so changes are picked up.
		$smarty->compile_check = (bool) app_base_ApplicationRegistry::getItem('smarty_compile_check');
		
		// This forces Smarty to (re)compile templates on every invocation. This setting overrides $compile_check. By 
		// default this is FALSE. This is handy for development and debugging. It should never be used in a production 
		// environment. If $caching is enabled, the cache file(s) will be regenerated every time.
		$smarty->force_compile = (bool) app_base_ApplicationRegistry::getItem('smarty_force_compile');;

		// Debugging
//		$smarty->debugging = true;
//		$smarty->debug_tpl  = SMARTY_DIR . 'debug.tpl';

		// Config
		$smarty->config_dir = SMARTY_CONFIG_DIR;

		$smarty->assign('APP_URL',         app_base_ApplicationRegistry::getUrl());
		$smarty->assign('APP_NAME',        app_base_ApplicationRegistry::getName());
		$smarty->assign('APP_DESCRIPTION', app_base_ApplicationRegistry::getDescription());
		$smarty->assign('APP_DEVELOPER',   app_base_ApplicationRegistry::getDeveloper());
		$smarty->assign('APP_VERSION',     app_base_ApplicationRegistry::getVersion());
		$smarty->assign('LOCALE',          app_base_ApplicationRegistry::getLocale());
		$smarty->assign('ENVIRONMENT',     app_base_ApplicationRegistry::getEnvironment());

		// Legacy templates expect {$ROOT_PATH} to point at the
		// web root; mirror APP_URL so those references keep working.
		$smarty->assign('ROOT_PATH',       app_base_ApplicationRegistry::getUrl());

		return $smarty;
	}
	
}

?>