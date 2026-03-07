<?php
/**
 * Check MySQL version of the live AWS RDS instance.
 * Uses data/app_options.xml -> environment "aws" database config.
 * Run: php check_aws_mysql_version.php
 *
 * Use this before upgrading local Docker to mysql:8.4 to ensure compatibility
 * with the live RDS engine version.
 */

$configFile = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'app_options.xml';
if (!is_readable($configFile)) {
    fwrite(STDERR, "Error: data/app_options.xml not found or not readable.\n");
    exit(1);
}

$options = @simplexml_load_file($configFile);
if (!$options || !isset($options->environment->aws)) {
    fwrite(STDERR, "Error: app_options.xml missing <aws> environment.\n");
    exit(1);
}

$db = (array) $options->environment->aws->database;
$host = trim((string) ($db['host'] ?? ''));
$database = trim((string) ($db['database'] ?? ''));
$username = trim((string) ($db['username'] ?? ''));
$password = trim((string) ($db['password'] ?? ''));

if ($host === '' || $database === '' || $username === '') {
    fwrite(STDERR, "Error: aws database host/database/username missing in app_options.xml.\n");
    exit(1);
}

try {
    $dsn = 'mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8mb4';
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $row = $pdo->query('SELECT VERSION() AS v')->fetch(PDO::FETCH_ASSOC);
    $version = $row['v'] ?? 'unknown';
    echo "AWS RDS MySQL version: " . $version . "\n";
    exit(0);
} catch (PDOException $e) {
    fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
    fwrite(STDERR, "Ensure the RDS host is reachable (VPN/security group) and credentials are correct.\n");
    exit(1);
}
