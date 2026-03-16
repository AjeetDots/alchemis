<?php
/**
 * Clear Smarty compiled templates so updated .tpl files are recompiled on next request.
 * Use after deploying template changes to live (where compile_check is off).
 *
 * Run once via browser: https://your-site/clear_smarty_cache.php?do=clear
 * Or via CLI: php clear_smarty_cache.php
 * Remove or restrict this file on production after use if desired.
 */

$secret = 'clear_smarty_compile'; // change or remove query check for CLI-only use
$requested = isset($_GET['do']) ? $_GET['do'] : (PHP_SAPI === 'cli' ? $secret : null);
if ($requested !== $secret) {
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, "Run with: php clear_smarty_cache.php\n");
        exit(1);
    }
    header('HTTP/1.0 403 Forbidden');
    echo 'Forbidden';
    exit;
}

$base = dirname(__FILE__);
$compileDir = $base . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'templates_c';

if (!is_dir($compileDir)) {
    $msg = 'Compile dir not found: ' . $compileDir;
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, $msg . "\n");
        exit(1);
    }
    header('Content-Type: text/plain; charset=utf-8');
    echo $msg;
    exit(1);
}

$count = 0;
$iterator = new DirectoryIterator($compileDir);
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        if (@unlink($file->getPathname())) {
            $count++;
        }
    }
}

$msg = 'Smarty compiled templates cleared. Deleted ' . $count . ' file(s). New .tpl changes will apply on next page load.';
if (PHP_SAPI === 'cli') {
    echo $msg . "\n";
    exit(0);
}
header('Content-Type: text/plain; charset=utf-8');
echo $msg;
