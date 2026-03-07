<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Starting database connection test...<br>";

// Set environment variable
$_SERVER['ALCHEMIS_ENV'] = 'aws';

try {
    echo "Loading registry...<br>";
    require_once('app/base/Registry.php');
    
    echo "Getting DSN...<br>";
    $dsn = app_base_ApplicationRegistry::getDSN();
    echo "DSN: $dsn<br>";
    
    echo "Loading EasySql...<br>";
    require_once('include/EasySql/EasySql.class.php');
    
    // Parse DSN
    $username = preg_replace('/^.+:\/\/|:.+@.+\/.+$/i', '', $dsn);
    $password = preg_replace('/^.+:\/\/.+:|@.+\/.+$/i', '', $dsn);
    $database = preg_replace('/^.+:\/\/.+:.+@.+\//i', '', $dsn);
    $hostname = preg_replace('/^.+:\/\/.+:.+@|\/.+$/i', '', $dsn);
    
    echo "Parsed - Host: $hostname, User: $username, DB: $database<br>";
    
    echo "Creating EasySql connection...<br>";
    $db = new EasySql($username, $password, $database, $hostname);
    
    echo "✅ Database connection successful!<br>";
    
    // Test query
    $result = $db->query("SELECT 1 as test");
    if ($result) {
        echo "✅ Test query successful!<br>";
    } else {
        echo "❌ Test query failed<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}
?>