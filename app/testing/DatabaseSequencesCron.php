<?php

// Crude authentication
if (!isset($_GET['key']) || '08a402316db047e0319018b53098b0e7' != $_GET['key'])
{
    exit;
}

// Setup file log
require_once('../../include/pear/Log.php');
$conf = array('mode' => 0755, 'timeFormat' => '%Y-%m-%d %a %H:%M:%S');
$logger = &Log::singleton('file', '../../logs/DatabaseSequences.log', '', $conf);

$logger->log('----- Start -----');

// DB settings
//$env = ( isset( $_SERVER['APPLICATION_ENV'] ) ) ? $_SERVER['APPLICATION_ENV'] : 'development';
//if ('development' == $env && '192.168.0.97' != $_SERVER['SERVER_ADDR'])
//{
//    $dbhost = 'localhost';
//    $dbname = 'dev-alchemis-webapp';
//    $dbuser = 'dev';
//    $dbpass = 'd3v3nv';
//}
//else
//{
    $dbhost = 'localhost';
    $dbname = 'alchemis';
    $dbuser = 'alchemis';
    $dbpass = 'rYT4maP7';
//}

// Connect
if (!mysql_connect($dbhost, $dbuser, $dbpass))
{
    $logger->log('Could not connect to mysql');
    exit;
}

// Use database
if (!mysql_select_db($dbname))
{
    $logger->log('Could not select database ' . $dbname);
    $logger->log('MySQL Error: ' . mysql_error());
    exit;
}

// Get the tables in the database
$sql = "SHOW TABLES FROM `$dbname`";
$result = mysql_query($sql);

if (!$result)
{
    $logger->log('DB Error, could not list tables');
    $logger->log('MySQL Error: ' . mysql_error());
    exit;
}

// Record tables in an array
$tables = array();
while ($row = mysql_fetch_row($result))
{
    $tables[] = $row[0];
}
mysql_free_result($result);


// Arrays for collecting data
$affectedTables = array();
$sqlFixes = array();


// Loop through each table in the database
foreach ($tables as $table)
{
    // Ignore sequence and shadow tabless
    if ('_seq' != substr($table, -strlen('_seq')) && '_shadow' != substr($table, -strlen('_shadow')))
    {
        if (in_array($table . '_seq', $tables))
        {
            // Get max id
            $sql = 'SELECT MAX(id) AS id FROM ' . $table;
            $result = mysql_query($sql);
            if (!$result) {
                $logger->log('Invalid query: ' . mysql_error());
                exit;
            }
            $row = mysql_fetch_array($result);
            $maxId = $row['id'];
            mysql_free_result($result);

            // Get sequence id
            $sql = 'SELECT sequence AS id FROM ' . $table . '_seq';
            $result = mysql_query($sql);
            if (!$result) {
                $logger->log('Invalid query: ' . mysql_error());
                exit;
            }
            $row = mysql_fetch_array($result);
            $seqId = $row['id'];
            mysql_free_result($result);
        
            // Get number of rows in sequence table
            $sql = 'SELECT COUNT(*) AS row_count FROM ' . $table . '_seq';
            $result = mysql_query($sql);
            if (!$result) {
                $logger->log('Invalid query: ' . mysql_error());
                exit;
            }
            $row = mysql_fetch_array($result);
            $seqTableRowCount = $row['row_count'];
            mysql_free_result($result);

            if ($maxId > $seqId)
            {
                $affectedTables[] = $table;
                $sqlFixes[] = 'ALTER TABLE `' . $table . '_seq` AUTO_INCREMENT = ' . $maxId;
                
                if ($seqTableRowCount > 1)
                {
                    $sqlFixes[] = 'DELETE FROM `' . $table . '_seq`';
                    $sqlFixes[] = 'INSERT INTO `' . $table . '_seq` (sequence) VALUES (' . $maxId . ')';
                }
                elseif ($seqTableRowCount < 1)
                {
                    $sqlFixes[] = 'INSERT INTO `' . $table . '_seq` (sequence) VALUES (' . $maxId . ')';
                }
                else
                {
                    $sqlFixes[] = 'UPDATE `' . $table . '_seq` SET `sequence` = ' . $maxId;
                }
            }
        }
    }
}



if ($affectedTables)
{
    $logger->log(count($affectedTables) . ' database table(s) found to have problems:');

    // Output affected tables
    foreach ($affectedTables as $affectedTable)
    {
        $logger->log("\t" . $affectedTable);
    }
}
else
{
    $logger->log('No problems were found.');
}

if ($sqlFixes)
{
    $logger->log('The following SQL statement were run to correct:');

    // Output SQL statements to correct
    foreach ($sqlFixes as $sql)
    {
        $logger->log("\t" . $sql . ';');

        $result = mysql_query($sql);
        if (!$result) {
            $logger->log('QUERY FAILED: ' . $sql);
            $logger->log(mysql_error());
        }
    }
}

$logger->log('----- End -----');