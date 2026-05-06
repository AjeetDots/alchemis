<?php

ob_start();

spl_autoload_register(function ($classname) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// Ensure a consistent timezone across environments so all date('...')
// calls (used in dashboard reports, campaign progress and planners)
// resolve to the same calendar day/month whether running on legacy
// PHP/MySQL or the upgraded stack. Live is UK-based, so use London.
if (!ini_get('date.timezone')) {
    date_default_timezone_set('Europe/London');
}

// Fix browser blocking scripts so Administration / Campaign View toggles work.
if (!headers_sent()) {
    header('Permissions-Policy: unload=(self)');
    // Allow eval/inline so jQuery, Angular, Prototype and moofx work (fixes CSP blocking toggle).
    header("Content-Security-Policy: script-src 'self' 'unsafe-eval' 'unsafe-inline'");
}

function pr($data = [], $tag = "##############"){
	// echo '<pre>'.$tag;
	// print_r($data);
	// echo $tag.'<pre>';
}
/*
|--------------------------------------------------------------------------
| Environment Detection
|--------------------------------------------------------------------------
*/

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (trim($line) === '' || $line[0] === '#') {
            continue;
        }

        if (preg_match('/^([^=]+)=(.*)$/', $line, $match)) {

            $key = trim($match[1]);
            $value = trim($match[2], " \t\"'");

            if (!isset($_SERVER[$key])) {
                $_SERVER[$key] = $value;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Error Handling
|--------------------------------------------------------------------------
*/

$env = $_SERVER['ALCHEMIS_ENV'] ?? 'aws';
$_SERVER['ALCHEMIS_ENV'] = $env;

$isDevelopment = ($env === 'aws');

if ($isDevelopment) {

    // Show all useful errors in development, but suppress noisy deprecation
    // notices from legacy libraries (Carbon, JpGraph, etc.) on PHP 8+.
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

} else {

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);

    error_reporting(E_ERROR);
}

// Commands that stream binary/files (PNG/PDF/XLS) must never print warnings/notices,
// otherwise browsers show "Failed to load PDF"/corrupt output in index.php?cmd=...
$cmd = isset($_GET['cmd']) ? (string) $_GET['cmd'] : '';
$isBinaryOutputCmd = (
    $cmd === 'DashboardGraph1' ||
    $cmd === 'DashboardGraph2' ||
    strpos($cmd, 'ReportGraph') === 0 ||
    preg_match('/^Report[0-9_]+$/', $cmd)
);
if ($isBinaryOutputCmd) {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('html_errors', 0);
}

/*
|--------------------------------------------------------------------------
| Debug Tools
|--------------------------------------------------------------------------
*/

if (isset($_GET['test'])) {
    require 'debug.php';
}

/*
|--------------------------------------------------------------------------
| Composer Autoload
|--------------------------------------------------------------------------
*/

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

/*
|--------------------------------------------------------------------------
| Include Paths
|--------------------------------------------------------------------------
*/

set_include_path(
    '.' . DIRECTORY_SEPARATOR . 'include' .
    PATH_SEPARATOR . '.' . DIRECTORY_SEPARATOR . 'include/pear' .
    PATH_SEPARATOR . '.' . DIRECTORY_SEPARATOR . 'include/Zend' .
    PATH_SEPARATOR . get_include_path()
);

// session_start() is placed here — after include paths are configured — so
// that when PHP deserializes session objects (e.g. app_domain_Action stored
// in post_initiative_actions), the autoloader can resolve all class files and
// their internal require_once calls without "incomplete object" errors.
session_start();

// Detect a corrupted session: if a previous request stored objects whose class
// was not yet loaded at deserialise time, PHP marks them as __PHP_Incomplete_Class.
// Calling any method on such an object causes a fatal error. Destroy the session
// and send the user back to the login page with a clear explanation.
// Skip serialize() when the session is empty — it is costly on large logged-in sessions
// and unnecessary for first visits.
if (!empty($_SESSION) && strpos(serialize($_SESSION), '__PHP_Incomplete_Class') !== false) {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
    ob_clean();
    echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Session Expired</title>
<style>
  body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
  .box { background: #fff; border-radius: 8px; padding: 36px 44px; box-shadow: 0 2px 12px rgba(0,0,0,.15); text-align: center; max-width: 420px; }
  h2 { color: #c0392b; margin-bottom: 12px; }
  p  { color: #555; line-height: 1.6; }
  a  { display: inline-block; margin-top: 20px; padding: 10px 28px; background: #2c3e7a; color: #fff; border-radius: 5px; text-decoration: none; font-weight: bold; }
  a:hover { background: #1a2757; }
</style>
</head>
<body>
<div class="box">
  <h2>Session Expired</h2>
  <p>Your session has expired due to a system update or deployment.<br>
     Please log in again &mdash; your data is safe.</p>
  <a href="index.php?cmd=Login">Log In Again</a>
</div>
<script>
  // Auto-redirect after 4 seconds so the user sees the message first.
  setTimeout(function(){ window.location.href = "index.php?cmd=Login"; }, 4000);
</script>
</body>
</html>';
    exit;
}

/*
|--------------------------------------------------------------------------
| Application Paths
|--------------------------------------------------------------------------
*/

$pathTranslated = $_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['PATH_TRANSLATED'];
$pathParts = pathinfo($pathTranslated);

define('APP_DIRECTORY', $pathParts['dirname'] . DIRECTORY_SEPARATOR);

define(
    'CONFIG_FILE',
    APP_DIRECTORY . 'data' . DIRECTORY_SEPARATOR . 'app_options.xml'
);

/*
|--------------------------------------------------------------------------
| Run Controller
|--------------------------------------------------------------------------
*/

try {

    app_controller_Controller::run();

} catch (Throwable $e) {

    // Always log the full exception/error so production issues can be diagnosed.
    $logMessage = sprintf(
        "[%s] %s in %s on line %s\nStack trace:\n%s\n\n",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );

    // Prefer a local log file in the app directory; fall back to PHP's error_log.
    $localLogFile = APP_DIRECTORY . 'php-exception.log';
    if (@is_writable(APP_DIRECTORY) || (!file_exists($localLogFile) && @is_writable(APP_DIRECTORY))) {
        @file_put_contents($localLogFile, $logMessage, FILE_APPEND);
    } else {
        error_log($logMessage);
    }

    $isAjax = (
        !empty($_GET['ajaxRequest']) ||
        !empty($_POST['ajaxRequest']) ||
        (isset($_GET['cmd']) && substr($_GET['cmd'], 0, 4) === 'Ajax')
    );

    if ($isAjax) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['error' => true, 'message' => 'A server error occurred. Please refresh and try again.']);
        exit;
    }

    // Show detailed error output in development or when explicitly requested with ?debug=1.
    if ($isDevelopment || (isset($_GET['debug']) && $_GET['debug'] == '1')) {

        echo '<pre>';
        echo $e;
        echo '</pre>';

    } else {

        echo '<h2>Application Error</h2>';
        echo '<p>Please contact administrator.</p>';
    }
}