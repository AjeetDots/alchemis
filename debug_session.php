<?php
session_start();

// Test database connection
$host = "staging-alchemis-mysql.cswhqpuhwywg.eu-west-1.rds.amazonaws.com";
$username = "alchemis";
$password = "rYT4maP7";
$database = "alchemis";

echo "<h2>Database Connection Test</h2>";
try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
    echo "✅ Database connection successful!<br>";
    
    // Test user table
    $stmt = $conn->query("SELECT COUNT(*) as count FROM tbl_rbac_users WHERE is_active = 1");
    $result = $stmt->fetch();
    echo "✅ Found {$result['count']} active users in database<br>";
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "<br>";
}

echo "<h2>Session Information</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Status: " . session_status() . "<br>";

if (isset($_SESSION['auth_session'])) {
    echo "Auth session exists:<br>";
    echo "<pre>";
    print_r($_SESSION['auth_session']);
    echo "</pre>";
} else {
    echo "❌ No auth session found<br>";
}

echo "<h2>Environment Check</h2>";
echo "Environment: aws<br>";

// Test the Auth_Session class
echo "<h2>Auth Session Test</h2>";
require_once('include/Auth/Session.php');
$session = Auth_Session::singleton();
$authenticated = $session->authenticate();
echo "Authentication result: " . ($authenticated ? "✅ SUCCESS" : "❌ FAILED") . "<br>";

?>