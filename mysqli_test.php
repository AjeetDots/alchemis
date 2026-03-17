<?php
$host = 'alchemis-mysql-upgrade.cswhqpuhwywg.eu-west-1.rds.amazonaws.com';
$user = 'alchemis';
$pass = 'rYT4maP7';
$db   = 'alchemis';
$port = 3306;

// Test 1: mysqli_connect
$c = @mysqli_connect($host, $user, $pass, $db, $port);
echo "mysqli_connect: " . ($c ? "OK - server: " . mysqli_get_server_info($c) : "FAILED - " . mysqli_connect_error()) . "<br>";
if ($c) mysqli_close($c);

// Test 2: mysqli_real_connect (same as MDB2)
$c2 = @mysqli_init();
$ok = @mysqli_real_connect($c2, $host, $user, $pass, $db, $port, null);
echo "mysqli_real_connect: " . ($ok ? "OK" : "FAILED - " . mysqli_connect_error()) . "<br>";
if ($ok) mysqli_close($c2);

// Test 3: mysqli_real_connect with SSL flag
$c3 = @mysqli_init();
$ok3 = @mysqli_real_connect($c3, $host, $user, $pass, $db, $port, null, MYSQLI_CLIENT_SSL);
echo "mysqli_real_connect+SSL: " . ($ok3 ? "OK" : "FAILED - " . mysqli_connect_error()) . "<br>";
if ($ok3) mysqli_close($c3);
