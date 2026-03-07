#!/usr/bin/php
<?php

/**
 * Backup all MySQL databases.
 * PEAR needs to be installed with the Mail and Mail_Mime packages.  
 * Read more about PEAR here: http://pear.php.net
 * @author    $Author$
 * @copyright 2006 Illumen Ltd
 * @package   Backup
 * @version   SVN: $Id$
 */

// Record start time
$start = microtime(true);

// Include path
set_include_path(get_include_path() . ':' . '/root/backup/scripts/includes/');

require_once('includes/Log/Log.php');
require_once('includes/Database/EasySql.class.php');

// Define named constants
define('MYSQL_USERNAME', 'root');           /* Username */
define('MYSQL_PASSWORD', 'fl1tcr0ft114');   /* Password */
define('MYSQL_DATABASE', 'test');           /* Database */
define('MYSQL_HOST',     'localhost');      /* Hostname */

define('SQL_DIR', '/root/backup/tmp/');                     /* Directory in which to dump temporary SQL files */
define('TAR_DIR', '/tmp/mysql_backup/');                    /* Directory in which to store tar backup file */
define('SECONDARY_BACKUP_DIR', '/mnt/staten/DatabaseBackup/SQL/');  /* Directory to copy the tar backup file to */
 
define('EMAIL_TO',   'david.carter@illumen.co.uk');//,robertanning@alchemis.co.uk');  /* Email address(es) to send to (comma-seperated list) */
define('EMAIL_FROM', 'root@mail.alchemis.co.uk');    /* Email address to send from */

define('LOG_FILE', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mysql_backup.log');  /* Log file */


// Determine whether to use verbose output. Use verbose if running from command line. Verbose outout is not
// included in the email notification 
$verbose = in_array('-v', $argv);

if ($verbose)
{
	echo "MySQL backup started at " . date('H:i:s') . " on " . date('d/m/Y') . "\n";
}


// Create logger
$conf = array('mode' => 0600, 'timeFormat' => '%Y-%m-%d %H:%M:%S');
$logger = &Log::singleton('file', LOG_FILE, '', $conf);
$logger->log('Backup started.');

$exlusion_dates = array();
$exclusion_dates[] = ('20071222');
$exclusion_dates[] = ('20071223');
$exclusion_dates[] = ('20071225');
$exclusion_dates[] = ('20071226');
$exclusion_dates[] = ('20071227');
$exclusion_dates[] = ('20071228');
$exclusion_dates[] = ('20071229');
$exclusion_dates[] = ('20071230');
$exclusion_dates[] = ('20071231');
//$exclusion_dates[] = ('20080101');

// Process options
if (in_array('-t', $argv))
{
	// Include time
	if ($verbose)
	{
		echo "Include time in backup filename\n";
	}
	$today = date('Ymd-Hi');
	$yesterday = date('Ymd-Hi', mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y')));
}
else
{
	// Exclude time
	if ($verbose)
	{
		echo "Exclude time in backup filename\n";
	}
	$today = date('Ymd');
	if (in_array($today, $exclusion_dates))
	{
		if ($verbose)
		{
			echo "'Backup aborted. Date is in exclusion list\n";
		}
		$logger->log('Backup aborted. Date is in exclusion list.');
		exit("Done.\n\n");
	}
	else
	{
		$yesterday = date('Ymd', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
	}
}


try
{
	$tmpDir = SQL_DIR . $today;

	// Create timestamped directory
	if ($verbose)
	{
		echo "1. Create timestamped directory:\n";
		echo "\t" . $tmpDir . "\n";
	}
	if (!file_exists($tmpDir))
	{
		mkdir($tmpDir);
	}
	else
	{
		$logger->log('Directory ' . $tmpDir . ' already exists');
	}
	
	// Connect to database to retrieve database list
	if ($verbose)
	{
		echo "2. Connect to database to retrieve database list\n";
	}
	$db = new Database_EasySql(MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, MYSQL_HOST);
	$databases = $db->getColumn('SHOW DATABASES');
	
	// Use mysqldump to dump each database to the temporary directory
	if ($verbose)
	{
		echo "3. Use mysqldump to dump each database to the temporary directory\n";
	}
	foreach ($databases as $database)
	{
 		if ($database == 'alchemis_client') { 
    		echo "Backing up " . $database . ": \n";
    		$sqlFile = $tmpDir . DIRECTORY_SEPARATOR . $database . '.sql';
    	   	$createBackup = 'mysqldump -u ' . MYSQL_USERNAME . ' --password=' . MYSQL_PASSWORD . ' --opt -all ' . $database . ' > ' . $sqlFile;
    		exec($createBackup);

		} else {
		      echo "Omitting back up of " . $database . ": \n";
		}

	}
	
	// Create a single *.tar.gz file containing the SQL files just dumped
	if ($verbose)
	{
		echo "4. Create a single *.tar.gz file containing the SQL files just dumped: \n";
	}
	$fileName  = 'mysql-backup-' . $today . '.tar.gz';
	
	if (!file_exists(TAR_DIR))
        {
                mkdir(TAR_DIR);
        }

	$tarFile   = TAR_DIR . $fileName;
	$createTar = 'tar czf ' . $tarFile . ' ' . $tmpDir . DIRECTORY_SEPARATOR;

	if ($verbose)
	{
		echo "\t$tarFile\n";
	}

	exec($createTar);
//	$logger->log('Created file ' . $tarFile);

	// Remove the temporary SQL files created for the backup
	if ($verbose)
	{
		echo "5. Remove the temporary SQL files created for the backup\n";
	}
	if (file_exists($tmpDir))
	{
		exec('rm -Rf ' . $tmpDir);
	}
	else
	{
		throw new Exception('tmp backup files location (' . $tmpDir . ') does not exist.');
	}
	
	// Copy the tar backup to the seconday directory
	if ($verbose)
	{
		echo "6. Copy the tar backup to the seconday directory\n";
	}
	$copy = 'cp ' . $tarFile . ' ' . SECONDARY_BACKUP_DIR;
	if ($verbose)
	{
		echo "\t" . $copy . "\n";
	}

	$execOutput = array();
	$execStatus = null;

	exec($copy, $execOutput, $execStatus);
/*
	print_r($execOutput);
	echo ('$execStatus: ' . $execStatus ."\n");
*/

/*	// Record the action
	if ($verbose)
	{
		echo "7. Record the action\n";
	}
*/
	
	$secondaryTarFile = SECONDARY_BACKUP_DIR . $fileName;
	

	if (!$execStatus) {
		$logger->log('Copied file ' . $tarFile . ' to ' . SECONDARY_BACKUP_DIR);
		$logger->log('Backup finished (' . ceil(elapsed()) . ' seconds). ' . count($databases) . ' databases backed up to ' .
					$tarFile . ' (' . filesize($tarFile) . ' bytes) and ' . $secondaryTarFile . ' (' . filesize($secondaryTarFile) . ' bytes).');
	} else {
		$logger->log('WARNING!! Backup did not complete. Failed to copy ' . $tarFile . ' to ' . SECONDARY_BACKUP_DIR . '.');
	}

	// Delete the newly created tar file from localhost
	if ($verbose)
	{
		echo "7a. Delete the tar file from localhost\n";
	}
	exec('rm -Rf ' . $tarFile);

	// Delete yesterday's file if requested
	if (in_array('-d', $argv))
	{
		if ($verbose)
		{
			echo "8. Delete yesterday's file if requested\n";
		}
		deleteFile();
	}
	
	// Email if requested
	if (in_array('-e', $argv))
	{
		if ($verbose)
		{
			echo "9. Email if requested\n";
		}
		emailFile($tarFile);
	}
}
catch (Exception $e)
{
	$conf = array('from' => EMAIL_FROM , 'subject' => 'Important Log Events');
	$mailLogger = &Log::singleton('mail', EMAIL_TO, 'ident', $conf);
	$mailLogger->log($e->getMessage(), PEAR_LOG_ERR);
	$logger->log($e->getMessage(), PEAR_LOG_ERR);
}

exit("Done.\n\n");


function elapsed()
{
	global $start;
	$time = microtime(true);
	$elapsed = $time - $start;
	return $elapsed;
}


function deleteFile()
{
	global $yesterday, $logger, $verbose;
	
	try
	{
		$tarFile = TAR_DIR . 'mysql-backup-' . $yesterday . '.tar.gz';
		if (file_exists($tarFile))
		{
			unlink($tarFile);
			$logger->log('Deleted old file: ' . $tarFile);
			if ($verbose)
			{
				echo "8a. Deleted old file: " . $tarFile . "\n";
			}
		}
		else
		{
			$logger->log('No old file to delete: ' . $tarFile);
			if ($verbose)
			{
				echo "8a. No old file to delete: " . $tarFile . "\n";
			}
		}
		
//		$secondaryTarFile = SECONDARY_BACKUP_DIR . 'mysql-backup-' . $yesterday . '.tar.gz';
//		if (file_exists($secondaryTarFile))
//		{
//			unlink($secondaryTarFile);
//			$logger->log('Deleted old file: ' . $secondaryTarFile);
//			if ($verbose)
//			{
//				echo "8a. Deleted old file: " . $secondaryTarFile . "\n";
//			}
//		}
//		else
//		{
//			$logger->log('No old file to delete: ' . $secondaryTarFile);
//			if ($verbose)
//			{
//				echo "8a. No old file to delete: " . $secondaryTarFile . "\n";
//			}
//		}
	}
	catch (Exception $e)
	{
		handleException($e);
	}
}


/**
 * Email the tar.gz backup file.
 */
function emailFile($tarFile)
{
	require_once('includes/Mail.php');
	require_once('includes/Mail/mime.php');
	
	$headers = array('From' => EMAIL_FROM, 'Subject' => 'Backup: ' . $tarFile);
//	$textMessage = $tarFile;
//	$textMessage = LOG_FILE;
//	$textMessage = extractFromLog();
	$textMessage = '';
	$lines = extractFromLog();
	foreach ($lines as $line)
	{
		$textMessage .= $line . "\n";
	}
	
	$htmlMessage = '';
	$mime = new Mail_Mime("\n");
	$mime->setTxtBody($textMessage);
	$mime->setHtmlBody($htmlMessage);
//	$mime->addAttachment($tarFile, 'text/plain');
//	$mime->addAttachment(LOG_FILE, 'text/plain');
	$body = $mime->get();
	$hdrs = $mime->headers($headers);
	$mail = &Mail::factory('mail');
	$mail->send(EMAIL_TO, $hdrs, $body);
}


/**
 * Record exceptions in the log file as well as emailing a copy.
 * @param Exception
 */
function handleException(Exception $e)
{
	global $to, $from, $logger;
	$conf = array('from' => $from, 'subject' => 'Important Log Events');
	$mailLogger = &Log::singleton('mail', $to, 'ident', $conf);
	$mailLogger->log($e->getMessage(), PEAR_LOG_ERR);
	$logger->log($e->getMessage(), PEAR_LOG_ERR);
}


/**
 * Extract the last 24 hours history from the log file.
 */
function extractFromLog()
{
	// load log file into array by line
	$loglines = file(LOG_FILE);
	
	if (empty($loglines))
	{
		return "Empty or missing log file at " . LOG_FILE;
	}
	
	// initialize tracking arrays
	$results = array();
	
	$yesterday = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y')));
	
	// start processing log
	foreach ($loglines as $num => $line)
	{
		// strip newline
		$line = trim($line);
		
		// ignore blank lines
		if (empty($line))
		{
			continue;
		}
		
		if (substr($line, 0, 19) >= $yesterday)
		{
			$results[] = $line;
		}
	}
		
	return $results;
}


?>

