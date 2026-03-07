<?php

// Record the time taken to run this script
$timeStart    = gettimeofday(); 
$timeStart_uS = $timeStart["usec"]; 
$timeStart_S  = $timeStart["sec"]; 

// Ensure the maximum execution time is at least 120 seconds
if (ini_get('max_execution_time') < 120)
{
	set_time_limit(120);
}

require_once('/var/www/html/include/EasySql/EasySql.class.php');

define('DB_HOST',     'alchemis-mysql.cswhqpuhwywg.eu-west-1.rds.amazonaws.com');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis_app');
define('DB_PASSWORD', 'rYT4maP7');

$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$db->debug_all = false;


// Aim will be to work this out dynamically and do an incremental update each night 
$start_date = date('Y-m-d') . ' 00:00:00';
$end_date   = date('Y-m-d') . ' 23:59:59';


//
// Record the batch process starting
//
echo "<h2>Record the batch process starting</h2>";
$start_timestamp = date('Y-m-d H:i:s');
$sql = "INSERT INTO tbl_batch_run (type, start) VALUES ('post_initiative_status_recalc', '$start_timestamp')";
$db->query($sql);

//
// moving from "very receptive: medium term" to "very receptive: near term"
//
echo '<h2>moving from "very receptive: medium term" to "very receptive: near term"<h2>';
// next_communication_date now within 30 days of current date and status = 4 (very receptive: medium term)
$sql = 'update tbl_post_initiatives set status_id = 5 ' .
		"where next_communication_date >= concat(CURDATE(), ' 00:00:00') " .
		"AND next_communication_date <= concat(CURDATE()+ INTERVAL 30 DAY, ' 23:59:59') " . 
		'AND status_id = 4';
$db->query($sql);
  
//
// moving from "receptive: long term" to "receptive: medium term"
//		
echo '<h2>moving from "receptive: long term" to "receptive: medium term"<h2>';
// next_communication_date now within 120 days of current date and status = 2 (receptive: long term)
$sql = 'update tbl_post_initiatives set status_id = 3 ' .
		"where next_communication_date >= concat(CURDATE(), ' 00:00:00') " .
		"AND next_communication_date <= concat(CURDATE()+ INTERVAL 120 DAY, ' 23:59:59') " . 
		'AND status_id = 2';  
$db->query($sql);

//
// Record the batch process finishing
//
echo "<h2>Record the batch process finishing</h2>";
$sql = "UPDATE tbl_batch_run SET end = NOW() WHERE start = '$start_timestamp'";
$db->query($sql);


//
// Finish up
//
echo "<p>Done.</p>";
$timeEnd    = gettimeofday(); 
$timeEnd_uS = $timeEnd["usec"]; 
$timeEnd_S  = $timeEnd["sec"]; 
$ExecTime_S = ($timeEnd_S + ($timeEnd_uS / 1000000)) - ($timeStart_S + ($timeStart_uS / 1000000)); 
echo '<div style="text-align: center; padding-bottom: 5px">Execution Time: ' . round($ExecTime_S, 3) . ' seconds</div>';


?>