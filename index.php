<?php

session_start();

// Fix browser blocking scripts so Administration / Campaign View toggles work.
if (!headers_sent()) {
    header('Permissions-Policy: unload=(self)');
    // Allow eval/inline so jQuery, Angular, Prototype and moofx work (fixes CSP blocking toggle).
    header("Content-Security-Policy: script-src 'self' 'unsafe-eval' 'unsafe-inline'");
}

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

$isDevelopment = ($env === 'aws');

if ($isDevelopment) {

    // Show all useful errors in development, but suppress noisy deprecation
    // notices from legacy libraries (Carbon, JpGraph, etc.) on PHP 8+.
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

} else {

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);

    error_reporting(E_ERROR);
}

// Graph commands output binary PNG; prevent any PHP notice/warning from corrupting the image
$graphCmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';
if ($graphCmd === 'DashboardGraph1' || $graphCmd === 'DashboardGraph2') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
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

    // Always log the full exception so production issues can be diagnosed.
    $logMessage = sprintf(
        "[%s] %s in %s on line %s\nStack trace:\n%s\n\n",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );

    // Prefer a local log file in the app directory; fall back to PHP's error_log.
    $localLogFile = APP_DIRECTORY . 'php-exception.log';
    if (@is_writable(APP_DIRECTORY) || (!file_exists($localLogFile) && @is_writable(APP_DIRECTORY))) {
        @file_put_contents($localLogFile, $logMessage, FILE_APPEND);
    } else {
        error_log($logMessage);
    }

    // Show detailed error output in development or when explicitly requested with ?debug=1.
    if ($isDevelopment || (isset($_GET['debug']) && $_GET['debug'] == '1')) {

        echo '<pre>';
        echo $e;
        echo '</pre>';

    } else {

        echo '<h2>Application Error</h2>';
        echo '<p>Please contact administrator.</p>';
    }
}