<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Login Debug</h2>";

// Test if we can access the login command
try {
    require_once('app/controller/ApplicationHelper.php');
    require_once('app/controller/Request.php');
    require_once('app/command/CommandResolver.php');
    require_once('app/controller/AppController.php');
    
    $applicationHelper = app_controller_ApplicationHelper::instance();
    $applicationHelper->init();
    
    $request = new app_controller_Request();
    $request->setProperty('cmd', 'Login');
    
    $app_controller = $applicationHelper->appController();
    $cmd = $app_controller->getCommand($request);
    
    echo "✅ Login command loaded: " . get_class($cmd) . "<br>";
    
    // Try to get the view
    $view = $app_controller->getView($request);
    echo "✅ Login view: " . $view . "<br>";
    
    // Try to resolve the view
    $viewObj = $app_controller->resolveView($view);
    echo "✅ View object: " . get_class($viewObj) . "<br>";
    
    // Try to execute the view
    echo "<h3>Executing view...</h3>";
    $viewObj->execute();
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>