<?php
// Simple database connection test bypassing registry
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Testing direct database connection...<br>";

// Direct connection parameters
$host = "staging-alchemis-mysql.cswhqpuhwywg.eu-west-1.rds.amazonaws.com";
$username = "alchemis";
$password = "rYT4maP7";
$database = "alchemis";

try {
    echo "Loading EasySql...<br>";
    require_once('include/EasySql/EasySql.class.php');
    
    echo "Creating EasySql connection...<br>";
    $db = new EasySql($username, $password, $database, $host);
    
    echo "✅ Database connection successful!<br>";
    
    // Test query
    echo "Testing query...<br>";
    $result = $db->query("SELECT 1 as test");
    if ($result) {
        echo "✅ Test query successful! Database is working.<br>";
    } else {
        echo "❌ Test query failed<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>