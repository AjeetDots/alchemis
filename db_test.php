<?php
/**
 * DB connection test for Local Docker / Live AWS
 * Auto-detects environment, forces Local DB when ALCHEMIS_ENV=development
 * Shows PHP & MySQL info, resets prepared statements for local DB safely
 */

header('Content-Type: text/html; charset=utf-8');

// ------------------------
// 1. Load environment
// ------------------------
$envFile = __DIR__ . '/.env';
if (file_exists($envFile) && is_readable($envFile)) {
    $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (!isset($_SERVER['ALCHEMIS_ENV']) && preg_match('/^ALCHEMIS_ENV\s*=\s*(.+)$/i', $line, $m)) {
            $_SERVER['ALCHEMIS_ENV'] = trim($m[1], " \t\"'");
        }
        if (!isset($_SERVER['ALCHEMIS_DB_HOST']) && preg_match('/^ALCHEMIS_DB_HOST\s*=\s*(.+)$/i', $line, $m)) {
            $_SERVER['ALCHEMIS_DB_HOST'] = trim($m[1], " \t\"'");
        }
        if (!isset($_SERVER['ALCHEMIS_DB_PORT']) && preg_match('/^ALCHEMIS_DB_PORT\s*=\s*(.+)$/i', $line, $m)) {
            $_SERVER['ALCHEMIS_DB_PORT'] = trim($m[1], " \t\"'");
        }
    }
}

// Default environment = development (local)
$env = $_SERVER['ALCHEMIS_ENV'] ?? 'development';

// ------------------------
// 2. Load XML config
// ------------------------
$configFile = __DIR__ . '/data/app_options.xml';
if (!file_exists($configFile)) die('❌ Config file not found: data/app_options.xml');

$options = @simplexml_load_file($configFile);
if (!$options || !isset($options->environment->$env)) {
    die('❌ Invalid config or missing environment: ' . htmlspecialchars($env));
}

// ------------------------
// 3. Read DB settings
// ------------------------
$db = (array) $options->environment->$env->database;

// In Docker use "db" (service name). On host use ALCHEMIS_DB_HOST/ALCHEMIS_DB_PORT from .env (e.g. 127.0.0.1:3307).
$isInDocker = file_exists('/.dockerenv');
if ($env === 'development') {
    $overrideHost = getenv('ALCHEMIS_DB_HOST') ?: ($_SERVER['ALCHEMIS_DB_HOST'] ?? null);
    if (!$isInDocker && $overrideHost !== null && $overrideHost !== '') {
        $host = trim((string) $overrideHost);
        $port = (int) (getenv('ALCHEMIS_DB_PORT') ?: ($_SERVER['ALCHEMIS_DB_PORT'] ?? 3307));
    } else {
        $host = 'db';
        $port = 3306;
    }
} else {
    $host = trim((string)$db['host']);
    $port = (isset($db['port']) && $db['port'] !== '') ? (int) $db['port'] : 3306;
}

$database = trim((string)$db['database']);
$username = trim((string)$db['username']);
$password = trim((string)$db['password']);

// Detect live AWS
$isLiveAws = (stripos($host, 'rds.amazonaws.com') !== false || stripos($host, 'amazonaws') !== false);

// ------------------------
// 4. Connect to MySQL
// ------------------------
try {
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // ------------------------
    // 5. Output DB info
    // ------------------------
    if ($isLiveAws) {
        echo '<p style="color:#c00;font-weight:bold;">🔴 Live AWS DB</p>';
        echo '<p>Connected to <strong>Live / AWS RDS</strong>. Environment: <code>' . htmlspecialchars($env) . '</code></p>';
    } else {
        echo '<p style="color:#080;font-weight:bold;">🟢 Local Docker DB</p>';
        echo '<p>Connected to <strong>Local Docker</strong>. Environment: <code>' . htmlspecialchars($env) . '</code></p>';
        echo '<p><strong>DB image:</strong> mysql:8.4</p>';
    }

    echo '<p><strong>Host:</strong> ' . htmlspecialchars($host) . ' | <strong>Database:</strong> ' . htmlspecialchars($database) . '</p>';
    echo '<p><strong>PHP:</strong> ' . htmlspecialchars(PHP_VERSION) . ' | <strong>MySQL (server):</strong> ';
    $ver = $pdo->query('SELECT VERSION() AS v')->fetch();
    echo htmlspecialchars($ver['v'] ?? '—') . '</p>';

    // Test query
    $stmt = $pdo->query('SELECT 1 as ok');
    $stmt->fetch();
    echo '<p>✅ Connection and test query OK.</p>';

    // Prepared statement info
    $stmt = $pdo->query("SHOW VARIABLES LIKE 'max_prepared_stmt_count'");
    $max = $stmt->fetch();
    echo '<p><strong>max_prepared_stmt_count</strong>: ' . htmlspecialchars($max['Value'] ?? '') . '</p>';

    $stmt = $pdo->query("SHOW GLOBAL STATUS LIKE 'Prepared_stmt_count'");
    $current = $stmt->fetch();
    echo '<p><strong>Current Prepared_stmt_count</strong>: ' . htmlspecialchars($current['Value'] ?? '') . '</p>';

    // Reset prepared statements for local dev (requires RELOAD/FLUSH_TABLES; skip if not granted)
    if (!$isLiveAws) {
        try {
            $pdo->query("FLUSH TABLES");
            echo '<p>🔄 Local prepared statements cleared.</p>';
        } catch (Exception $e) {
            echo '<p>ℹ️ Flush skipped (user lacks RELOAD privilege).</p>';
        }
    }

    $pdo = null;

} catch (Exception $e) {
    echo '❌ DB Error: ' . htmlspecialchars($e->getMessage());
}