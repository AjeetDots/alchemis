<?php

// Crude authentication
if (!isset($_GET['key']) || '08a402316db047e0319018b53098b0e7' != $_GET['key'])
{
    exit;
}

$env = ( isset( $_SERVER['APPLICATION_ENV'] ) ) ? $_SERVER['APPLICATION_ENV'] : 'development';

if ('development' == $env)
{
    $dbhost = 'localhost';
    $dbname = 'dev-alchemis-webapp';
    $dbuser = 'dev';
    $dbpass = 'd3v3nv';
}
else
{
    $dbhost = 'localhost';
    $dbname = 'alchemis';
    $dbuser = 'alchemis';
    $dbpass = 'rYT4maP7';
}

// Connect
if (!mysql_connect($dbhost, $dbuser, $dbpass))
{
    echo 'Could not connect to mysql';
    exit;
}

// Use database
if (!mysql_select_db($dbname))
{
    echo 'Could not select database ' . $dbname;
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

// Get the tables in the database
$sql = "SHOW TABLES FROM `$dbname`";
$result = mysql_query($sql);

if (!$result)
{
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

// Record tables in an array
$tables = array();
while ($row = mysql_fetch_row($result))
{
    $tables[] = $row[0];
}
mysql_free_result($result);


// Array for collecting SQL statements for correcting
$sqlFixes = array();

// Output table header
echo '<table border="1" cellspacing="0" cellpadding="5">' . PHP_EOL;
echo '<tr>' . PHP_EOL;
echo '<td>Table</td>' . PHP_EOL;
echo '<td>Max ID</td>' . PHP_EOL;
echo '<td></td>' . PHP_EOL;
echo '<td>Sequence ID</td>' . PHP_EOL;
echo '<td>Sequence Table</td>' . PHP_EOL;
echo '<td>Sequence Table Records</td>' . PHP_EOL;
echo '</tr>' . PHP_EOL;

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
                die('Invalid query: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $maxId = $row['id'];
            mysql_free_result($result);

            // Get sequence id
            $sql = 'SELECT sequence AS id FROM ' . $table . '_seq';
            $result = mysql_query($sql);
            if (!$result) {
                die('Invalid query: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $seqId = $row['id'];
            mysql_free_result($result);
        
            // Get number of rows in sequence table
            $sql = 'SELECT COUNT(*) AS row_count FROM ' . $table . '_seq';
            $result = mysql_query($sql);
            if (!$result) {
                die('Invalid query: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $seqTableRowCount = $row['row_count'];
            mysql_free_result($result);

            if ($maxId == $seqId && $seqTableRowCount == 1)
            {
                $css = 'style="color: green"';
                $operand = '=';
            }
            elseif ($maxId > $seqId)
            {
                $css = 'style="color: red"';
                $operand = '>';
                $sqlFixes[] = 'ALTER TABLE ' . $table . '_seq AUTO_INCREMENT = ' . $maxId;
                
                if ($seqTableRowCount > 1)
                {
                    $sqlFixes[] = 'DELETE FROM ' . $table . '_seq';
                    $sqlFixes[] = 'INSERT INTO ' . $table . '_seq (sequence) VALUES (' . $maxId . ')';
                }
                elseif ($seqTableRowCount < 1)
                {
                    $sqlFixes[] = 'INSERT INTO ' . $table . '_seq (sequence) VALUES (' . $maxId . ')';
                }
                else
                {
                    $sqlFixes[] = 'UPDATE ' . $table . '_seq SET sequence = ' . $maxId;
                }
            }
            else
            {
                $css = 'style="color: red"';
                $operand = '<';
            }
            
            if ($seqTableRowCount != 1)
            {
                $css = 'style="color: red"';
            }
            
            echo "<tr $css>" . PHP_EOL;
            echo '<td>' . $table . '</td>' . PHP_EOL;
            echo '<td>' . $maxId . '</td>' . PHP_EOL;
            echo '<td>' . $operand . '</td>' . PHP_EOL;
            echo '<td>' . $seqId . '</td>' . PHP_EOL;
            echo '<td>' . $table . '_seq</td>' . PHP_EOL;
            echo '<td>' . $seqTableRowCount . '</td>' . PHP_EOL;
            echo '</tr>' . PHP_EOL;
        }
    }
}

echo "</table>";

// Output SQL statements to correct
echo '<pre>' . PHP_EOL;
foreach ($sqlFixes as $sqlFix)
{
    echo $sqlFix . ';' . PHP_EOL;
}
echo '</pre>' . PHP_EOL;