<?php

session_start();
function pr($data = [], $tag = "##############"){
	// echo '<pre>'.$tag;
	// print_r($data);
	// echo $tag.'<pre>';
}
/*
|--------------------------------------------------------------------------
| Environment Detection
|--------------------------------------------------------------------------
*/

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (trim($line) === '' || $line[0] === '#') {
            continue;
        }

        if (preg_match('/^([^=]+)=(.*)$/', $line, $match)) {

            $key = trim($match[1]);
            $value = trim($match[2], " \t\"'");

            if (!isset($_SERVER[$key])) {
                $_SERVER[$key] = $value;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Error Handling
|--------------------------------------------------------------------------
*/

$env = $_SERVER['ALCHEMIS_ENV'] ?? 'aws';
$_SERVER['ALCHEMIS_ENV'] = $env;

$isDevelopment = ($env === 'development');

if ($isDevelopment) {

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

} else {

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);

    error_reporting(E_ERROR);
}

/*
|--------------------------------------------------------------------------
| Debug Tools
|--------------------------------------------------------------------------
*/

if (isset($_GET['test'])) {
    require 'debug.php';
}

/*
|--------------------------------------------------------------------------
| Composer Autoload
|--------------------------------------------------------------------------
*/

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

/*
|--------------------------------------------------------------------------
| Legacy Autoloader
|--------------------------------------------------------------------------
*/

spl_autoload_register(function ($classname) {

    $path = str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
});

/*
|--------------------------------------------------------------------------
| Include Paths
|--------------------------------------------------------------------------
*/

set_include_path(
    '.' . DIRECTORY_SEPARATOR . 'include' .
    PATH_SEPARATOR . '.' . DIRECTORY_SEPARATOR . 'include/pear' .
    PATH_SEPARATOR . '.' . DIRECTORY_SEPARATOR . 'include/Zend' .
    PATH_SEPARATOR . get_include_path()
);

/*
|--------------------------------------------------------------------------
| Application Paths
|--------------------------------------------------------------------------
*/

$pathTranslated = $_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['PATH_TRANSLATED'];
$pathParts = pathinfo($pathTranslated);

define('APP_DIRECTORY', $pathParts['dirname'] . DIRECTORY_SEPARATOR);

define(
    'CONFIG_FILE',
    APP_DIRECTORY . 'data' . DIRECTORY_SEPARATOR . 'app_options.xml'
);

/*
|--------------------------------------------------------------------------
| Run Controller
|--------------------------------------------------------------------------
*/

try {

    app_controller_Controller::run();

} catch (Throwable $e) {

    if ($isDevelopment) {

        echo '<pre>';
        echo $e;
        echo '</pre>';

    } else {

        echo '<h2>Application Error</h2>';
        echo '<p>Please contact administrator.</p>';
    }
}