<?php
/**
 * Dump live (AWS RDS) database to a .sql file for import into local.
 * Run from command line: php dump_live_db.php
 *
 * Uses Docker (mysql:8.4) to run mysqldump if mysqldump is not in PATH – no need to install MySQL on Windows.
 * Requires: Docker running. If mysqldump is installed locally, it will be used instead.
 *
 * Output: backup_live_YYYYMMDD_HHMMSS.sql in project root.
 * Import to local: php import_live_to_local.php [filename]
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

$outputFile = __DIR__ . DIRECTORY_SEPARATOR . 'backup_live_' . date('Ymd_His') . '.sql';

echo "Dumping live DB: {$host} / {$database} ...\n";
echo "Output file: {$outputFile}\n";

$stdout = null;
$stderr = '';
$code = -1;

// Try Docker first (works on Windows without MySQL installed)
// No --single-transaction: RDS user often lacks RELOAD/LOCK TABLES, so FLUSH TABLES WITH READ LOCK fails. --skip-lock-tables only.
$dockerCmd = sprintf(
    'docker run --rm -e MYSQL_PWD=%s mysql:8.4 mysqldump -h %s -u %s --skip-lock-tables --routines --triggers --set-charset --default-character-set=utf8mb4 %s',
    escapeshellarg($password),
    escapeshellarg($host),
    escapeshellarg($username),
    escapeshellarg($database)
);
$descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
$proc = @proc_open($dockerCmd, $descriptors, $pipes, __DIR__);
if (!is_resource($proc)) {
    fwrite(STDERR, "Error: could not run Docker. Is Docker running?\n");
    exit(1);
}

fclose($pipes[0]);
$outFh = fopen($outputFile, 'wb');
if (!$outFh) {
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($proc);
    fwrite(STDERR, "Error: could not write {$outputFile}\n");
    exit(1);
}
// Stream dump to file in chunks to avoid exhausting memory on large DBs
while (!feof($pipes[1])) {
    $chunk = fread($pipes[1], 65536);
    if ($chunk !== false && $chunk !== '') {
        fwrite($outFh, $chunk);
    }
}
fclose($outFh);
fclose($pipes[1]);
$stderr = stream_get_contents($pipes[2]);
fclose($pipes[2]);
$code = proc_close($proc);

if ($stderr !== '') {
    fwrite(STDERR, "Docker mysqldump stderr: " . trim($stderr) . "\n");
}
if ($code !== 0) {
    @unlink($outputFile);
    fwrite(STDERR, "Error: dump failed (exit code {$code}). Ensure Docker is running and the RDS host is reachable (VPN/security group).\n");
    exit(1);
}

echo "Done. Dump saved to: " . basename($outputFile) . "\n";
echo "\nTo import into local Docker MySQL, run:\n";
echo "  php import_live_to_local.php " . basename($outputFile) . "\n";
echo "Or manually:\n";
echo "  docker exec -i legacy_mysql mysql -u legacy_user -psecret legacy_db < " . basename($outputFile) . "\n";
