<?php
// fake index.php to capture error locally
$_SERVER['ALCHEMIS_ENV'] = 'development';
// require composer dependencies - make it optional
if (file_exists('vendor/autoload.php')) {
	require_once('vendor/autoload.php');
}
function __autoload1($classname) {
	$path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
	if (file_exists($path . '.php')) {
		require_once($path . '.php');
	}
}
spl_autoload_register('__autoload1');
set_include_path('.' . DIRECTORY_SEPARATOR . 'include' .
':' . '.' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'pear' .
':' . '.' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'Zend' .
':' . get_include_path());

error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
try {
    require_once 'app/command/Command.php';
    require_once 'app/command/DashboardGraph1.php';
    $cmd = new app_command_DashboardGraph1();
    
    // Create a dummy request
    require_once 'app/controller/Request.php';
    $req = new app_controller_Request();
    
    // Instead of execute(), let's just cheat and call init directly via reflection to avoid Auth layer
    $ref = new ReflectionClass('app_command_DashboardGraph1');
    $method = $ref->getMethod('init');
    $method->setAccessible(true);
    $method->invokeArgs($cmd, [$req]);
} catch (Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}

$out = ob_get_clean();
$file = 'temp_graph_err.log';
file_put_contents($file, $out);
echo "Done. Wrote " . strlen($out) . " bytes to $file";
