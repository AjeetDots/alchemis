<?php
echo "Testing database connection...<br>";

// Test the application's database connection
require_once('include/Auth/Session.php');

try {
    $session = Auth_Session::singleton();
    echo "✅ Session created successfully<br>";
    
    // Try to get database connection
    $reflection = new ReflectionClass('Auth_Session');
    $method = $reflection->getMethod('getDbConnection');
    $method->setAccessible(true);
    $db = $method->invoke(null);
    
    if ($db) {
        echo "✅ Database connection object created<br>";
        
        // Test a simple query
        $result = $db->query("SELECT 1 as test");
        if ($result) {
            echo "✅ Database query successful<br>";
            echo "Database connection is working!";
        } else {
            echo "❌ Database query failed<br>";
        }
    } else {
        echo "❌ Database connection is null<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>