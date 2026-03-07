<?php
/**
 * Import a live DB dump (.sql) into local Docker MySQL.
 * Run: php import_live_to_local.php [dump_file.sql]
 * If no file given, uses the latest backup_live_*.sql in project root.
 *
 * Requires: Docker running and container "legacy_mysql" (local DB) running.
 */

$projectRoot = __DIR__;

$dumpFile = $argv[1] ?? null;
if ($dumpFile === null || $dumpFile === '') {
    $glob = $projectRoot . DIRECTORY_SEPARATOR . 'backup_live_*.sql';
    $files = glob($glob);
    if (empty($files)) {
        fwrite(STDERR, "Error: No backup_live_*.sql found. Run dump_live_db.php first.\n");
        exit(1);
    }
    rsort($files);
    $dumpFile = $files[0];
} else {
    if (!str_contains($dumpFile, DIRECTORY_SEPARATOR) && !str_contains($dumpFile, '/')) {
        $dumpFile = $projectRoot . DIRECTORY_SEPARATOR . $dumpFile;
    }
    if (!is_readable($dumpFile)) {
        fwrite(STDERR, "Error: File not found or not readable: {$dumpFile}\n");
        exit(1);
    }
}

$dumpFile = realpath($dumpFile);
echo "Importing: " . basename($dumpFile) . " into local Docker MySQL (legacy_mysql / legacy_db) ...\n";

$cmd = 'docker exec -i legacy_mysql mysql -u legacy_user -psecret legacy_db';
$proc = proc_open(
    $cmd,
    [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ],
    $pipes,
    $projectRoot
);

if (!is_resource($proc)) {
    fwrite(STDERR, "Error: could not run docker exec. Is Docker running and container legacy_mysql up?\n");
    exit(1);
}

$fh = fopen($dumpFile, 'rb');
if (!$fh) {
    fwrite(STDERR, "Error: could not open dump file.\n");
    exit(1);
}
// Live dump is for DB "alchemis"; local uses "legacy_db" – rewrite only CREATE DATABASE / USE so data goes into legacy_db (don't replace inside INSERT data)
$targetDb = 'legacy_db';
while (!feof($fh)) {
    $chunk = fread($fh, 65536);
    $chunk = preg_replace('/CREATE DATABASE IF NOT EXISTS `alchemis`/i', 'CREATE DATABASE IF NOT EXISTS `' . $targetDb . '`', $chunk);
    $chunk = preg_replace('/\bUSE\s+`alchemis`\s*;/i', 'USE `' . $targetDb . '`;', $chunk);
    fwrite($pipes[0], $chunk);
}
fclose($fh);
fclose($pipes[0]);

$stdout = stream_get_contents($pipes[1]);
$stderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$code = proc_close($proc);

if ($stderr !== '') {
    fwrite(STDERR, "mysql stderr: " . $stderr . "\n");
}
if ($code !== 0) {
    fwrite(STDERR, "Error: import exited with code {$code}.\n");
    exit(1);
}

echo "Done. Local DB (legacy_db) now has the imported data.\n";
echo "Set ALCHEMIS_ENV=development and use the app against local.\n";
