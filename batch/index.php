<?php

// In order to make include libraries work well in our code, it is desirable to decouple the 
// invoking code from the library to that they can be referenced anywhere on the system.
set_include_path(	'.' . DIRECTORY_SEPARATOR . 'include' . 
					':.' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'pear' .
					':.' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'Zend' .
					':' . get_include_path());
//ini_set('error_reporting', E_ERROR & ~E_NOTICE);

// Define APP_DIRECTORY
$path_translated = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : $_SERVER['PATH_TRANSLATED']; 
$path_parts = pathinfo($path_translated);
define('APP_DIRECTORY', $path_parts['dirname'] . DIRECTORY_SEPARATOR);

// Define location of the config file.
// NB - Remember to remove the applicationRegistry.txt file so that changes to the config file will take effect.
define('CONFIG_FILE', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'app_options.xml');

try
{
	require_once('app/controller/Controller.php');
	app_controller_Controller::run();
}
catch (Exception $e)
{
	$html = '<html>';
	$html .= '<head>';
	$html .= '<title>' . get_class($e) . '</title>';
	$html .= '<link rel="stylesheet" type="text/css" href="app/view/styles/main.css" />';
	$html .= '</head>';
	$html .= '<body style="background-color: #EDEDED">';
	$html .= '<div id="content">';
	$html .= '<div class="msgError">';
	$html .= '<span class="title">' . get_class($e) . ':</span>';
	$html .= '<p><strong>' . $e->getMessage() . ' (' . $e->getCode() . ')</strong></p>';
	$html .= '<p>' . $e->getFile() . ' (line ' . $e->getLine() . ')</p>';
	$html .= '<pre>' . $e->getTraceAsString() . '</pre>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</body>';
	$html .= '</html>';
	echo $html;
}

/**
 * Automatically called in case you are trying to use a class which hasn't been 
 * defined yet. By calling this function the scripting engine is given a last 
 * chance to load the class before PHP fails with an error.
 * @see http://www.php.net/__autoload
 * @param string $classname name of the class trying to load
 */
function __autoload($classname)
{
	$path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
	if (file_exists($path . '.php'))
	{
		require_once($path . '.php');
	}
}

?>