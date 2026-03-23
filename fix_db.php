<?php
/**
 * Applies missing database indexes to speed up Calendar/Actions queries.
 * Visit once, then delete this file.
 */

// Find app_options.xml - search common locations
$candidates = [
    __DIR__ . '/data/app_options.xml',
    __DIR__ . '/upgrade/alchemis/data/app_options.xml',
    __DIR__ . '/alchemis/data/app_options.xml',
    '/var/www/html/upgrade/alchemis/data/app_options.xml',
    '/var/www/html/alchemis/data/app_options.xml',
];

$xmlFile = null;
foreach ($candidates as $c) {
    if (file_exists($c)) { $xmlFile = $c; break; }
}

$host = $port = $name = $user = $pass = null;

if ($xmlFile) {
    $xml = simplexml_load_file($xmlFile);
    $env = $_SERVER['ALCHEMIS_ENV'] ?? 'aws';
    // Try the configured env first, then fall back to other envs
    foreach ([$env, 'aws', 'development', 'production'] as $try) {
        if (isset($xml->environments->$try->database)) {
            $db   = $xml->environments->$try->database;
            $host = (string)($db->host ?? '');
            $port = (string)($db->port ?? '3306');
            $name = (string)($db->database ?? '');
            $user = (string)($db->username ?? '');
            $pass = (string)($db->password ?? '');
            if ($host && $name && $user) { break; }
        }
    }
}

// Fallback: read from .env if XML failed
if (!$host) {
    $envFile = $xmlFile ? dirname(dirname($xmlFile)) . '/.env' : __DIR__ . '/.env';
    if (file_exists($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if ($line[0] === '#') { continue; }
            if (preg_match('/^([^=]+)=(.*)$/', $line, $m)) {
                $k = trim($m[1]); $v = trim($m[2], " \t\"'");
                if ($k === 'DB_HOST')     { $host = $v; }
                if ($k === 'DB_PORT')     { $port = $v; }
                if ($k === 'DB_DATABASE') { $name = $v; }
                if ($k === 'DB_USERNAME') { $user = $v; }
                if ($k === 'DB_PASSWORD') { $pass = $v; }
            }
        }
    }
}

header('Content-Type: text/plain; charset=utf-8');

if (!$host || !$name || !$user) {
    echo "ERROR: Could not find database credentials.\n";
    echo "Tried XML: " . ($xmlFile ?? 'not found') . "\n";
    echo "ALCHEMIS_ENV=" . ($_SERVER['ALCHEMIS_ENV'] ?? '(not set)') . "\n";
    exit(1);
}

// On Linux 'localhost' uses a socket — force TCP by using 127.0.0.1
$dsn_host = ($host === 'localhost') ? '127.0.0.1' : $host;

try {
    $pdo = new PDO("mysql:host=$dsn_host;port=$port;dbname=$name;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10,
    ]);

    echo "Connected to: $host:$port / $name\n\n";

    $fixes = [
        'ix_tbl_actions_due_date' => [
            'check' => "SELECT COUNT(*) FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'tbl_actions' AND index_name = 'ix_tbl_actions_due_date'",
            'sql'   => 'ALTER TABLE `tbl_actions` ADD INDEX `ix_tbl_actions_due_date` (`due_date`)',
            'desc'  => 'Index on tbl_actions.due_date — speeds up Calendar/CalendarDay UNION query',
        ],
        'ix_tbl_actions_completed_date' => [
            'check' => "SELECT COUNT(*) FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'tbl_actions' AND index_name = 'ix_tbl_actions_completed_date'",
            'sql'   => 'ALTER TABLE `tbl_actions` ADD INDEX `ix_tbl_actions_completed_date` (`completed_date`)',
            'desc'  => 'Index on tbl_actions.completed_date',
        ],
    ];

    foreach ($fixes as $idxName => $fix) {
        $exists = (int)$pdo->query($fix['check'])->fetchColumn();
        if ($exists) {
            echo "[SKIP]  $idxName — already exists\n";
        } else {
            $pdo->exec($fix['sql']);
            echo "[DONE]  $idxName — {$fix['desc']}\n";
        }
    }

    echo "\nAll done. Delete fix_db.php from the server now.\n";

} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo "Host used: $dsn_host  Port: $port  DB: $name  User: $user\n";
}
