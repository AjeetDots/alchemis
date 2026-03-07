<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
	// Record the time taken to run this script
	$timeStart    = gettimeofday();
	$timeStart_uS = $timeStart["usec"];
	$timeStart_S  = $timeStart["sec"];

	// Ensure the maximum execution time is at least 300 seconds
	// if (ini_get('max_execution_time') < 300)
	// {
	// 	set_time_limit(300);
	// }

	set_time_limit(0);

	//require_once('/applications/MAMP/htdocs/alchemis-trunk/include/EasySql/EasySql.class.php');
	require_once('/var/www/html/include/EasySql/EasySql.class.php');
	//require_once('/var/www/html/include/EasySql/EasySql.class.php');

	define('DB_HOST',     'alchemis-mysql.cswhqpuhwywg.eu-west-1.rds.amazonaws.com');
	define('DB_NAME',     'alchemis');
	define('DB_USER',     'alchemis');
	define('DB_PASSWORD', 'rYT4maP7');

	$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
	$db->debug_all = false;

	echo '<hr /><h2>Campaign meeting attended target to date</h2>';
	makeTemporaryTable($db);

	$sql = 'INSERT INTO tbl_data_statistics_temp_a (campaign_id, user_id, `year_month`, `value`) ' .
		'select x1.campaign_id, 0, x1.`year_month`, sum(x2.meet_category_attended_target) as running_total ' .
		'from ' .
		'( ' .
		'select sum(meetings_attended) as meet_category_attended_target, `year_month`, campaign_id ' .
		'from tbl_campaign_targets ' .
		'group by campaign_id, `year_month`) as x1 ' .
		'inner join ' .
		'( ' .
		'select sum(meetings_attended) as meet_category_attended_target, `year_month`, campaign_id ' .
		'from tbl_campaign_targets ' .
		'group by campaign_id, `year_month`) as x2 ' .
		'on ' .
		'x1.campaign_id = x2.campaign_id and x1.`year_month` >= x2.`year_month` ' .
		'group by x1.`year_month`, x1.campaign_id';

	echo "<p>$sql</p>";

	$sql = 'UPDATE tbl_data_statistics ds JOIN tbl_data_statistics_temp_a ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.year_month = ds_temp.year_month ' .
		'SET campaign_meeting_category_attended_target_to_date = ds_temp.`value`;';
	echo "<p>$sql</p>";
	$db->query($sql);

} catch (Exception $e) {

	echo '</br> <b> Exception Message: ' . $e->getMessage() . '</b>';
}


function makeTemporaryTable($db)
{
	$sql =	'DROP TABLE IF EXISTS `tbl_data_statistics_temp_a`; ';
	echo "<p>$sql</p>";
	$db->query($sql);

	$sql = 	'CREATE TABLE tbl_data_statistics_temp_a ( ' .
		'`id` int(11) NOT NULL auto_increment, ' .
		'`campaign_id` int(11) NOT NULL default \'0\', ' .
		'`user_id` int(11) NOT NULL default \'0\', ' .
		'`year_month` char(6) NOT NULL default \'\', ' .
		'`value` int(11), ' .
		'PRIMARY KEY  (`id`), ' .
		'KEY `campaign_id` (`campaign_id`), ' .
		'KEY `user_id` (`user_id`), ' .
		'KEY `year_month` (`year_month`) ' .
		');';
	echo "<p>$sql</p>";
	$db->query($sql);
}

function makeTemporaryTable_1($db)
{
	$sql =	'DROP TABLE IF EXISTS `tbl_data_statistics_temp_a_1`; ';
	echo "<p>$sql</p>";
	$db->query($sql);

	$sql = 	'CREATE TABLE `tbl_data_statistics_temp_a_1` ( ' .
		'`id` int(11) NOT NULL auto_increment, ' .
		'`value` int(11), ' .
		'PRIMARY KEY  (`id`) ' .
		');';
	echo "<p>$sql</p>";
	$db->query($sql);
}
