<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Starting application...<br>";

try {
    if(isset($_GET['test']))
        require 'debug.php';
    
    echo "Starting session...<br>";
    session_start();
    
    function pr($data = [], $tag = "##############"){
        // echo '<pre>'.$tag;
        // print_r($data);
        // echo $tag.'<pre>';
    }
    
    if(isset($_GET['errors'])){
        ini_set('display_errors', 'On');
        error_reporting(E_ERROR);
    }

    // require composer dependencies - make it optional
    if (file_exists('vendor/autoload.php')) {
        require_once('vendor/autoload.php');
    }

    echo "Setting up autoloader...<br>";
    // autoloader
    function __autoload1($classname)
    {
        $path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
        if (file_exists($path . '.php'))
        {
            require_once($path . '.php');
        }
    }

    spl_autoload_register('__autoload1');

    echo "Setting include path...<br>";
    // In order to make include libraries work well in our code, it is desirable to decouple the
    // invoking code from the library to that they can be referenced anywhere on the system.
    set_include_path(	'.' . DIRECTORY_SEPARATOR . 'include' .
                        ':.' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'pear' .
                        ':.' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'Zend' .
                        ':' . get_include_path());
    ini_set('error_reporting', E_ERROR & ~E_NOTICE & ~E_WARNING);

    echo "Setting environment...<br>";
    // sentry
    $env = 'aws'; // Force aws environment
    $_SERVER['ALCHEMIS_ENV'] = $env;
    
    echo "Environment: $env<br>";
    
    if ($env == 'production' || $env == 'aws') {
        // $client = new Raven_Client('http://c7e04ba96ab04bc188dcb287d3b1a47e:d0f83b3d5bc84c5c935defc6bcc6fab0@sentry.settcloud.com/7');
        // $error_handler = new Raven_ErrorHandler($client);
        // $error_handler->registerExceptionHandler();
        // $error_handler->registerErrorHandler(true, E_ERROR & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
        // $error_handler->registerShutdownFunction();
    }

    echo "Defining constants...<br>";
    // Define APP_DIRECTORY
    $path_translated = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : $_SERVER['PATH_TRANSLATED'];
    $path_parts = pathinfo($path_translated);
    define('APP_DIRECTORY', $path_parts['dirname'] . DIRECTORY_SEPARATOR);

    // Define location of the config file.
    // NB - Remember to remove the applicationRegistry.txt file so that changes to the config file will take effect.
    define('CONFIG_FILE', APP_DIRECTORY . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'app_options.xml');

    echo "Loading controller dependencies...<br>";
    echo "About to run controller...<br>";
    app_controller_Controller::run();
    echo "Controller completed successfully<br>";
    
} catch (Exception $e) {
    echo "Exception caught: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}

echo "Script completed<br>";
?>