<?php

// Record the time taken to run this script

// Ensure the maximum execution time is at least 300 seconds
if (ini_get('max_execution_time') < 300)
{
	set_time_limit(300);
}

//require_once('/Users/david/Sites/alchemis/include/EasySql/EasySql.class.php');
//require_once('/Users/david/Sites/alchemis/include/Utils/Utils.class.php');

require_once('/var/www/html/alchemis/include/EasySql/EasySql.class.php');
require_once('/var/www/html/alchemis/include/Utils/Utils.class.php');

define('DB_HOST',     'localhost');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis');
define('DB_PASSWORD', 'rYT4maP7');
//define('DB_HOST',     'localhost');
//define('DB_NAME',     'alchemis');
//define('DB_USER',     'alchemis');
//define('DB_PASSWORD', 'rYT4maP7');

$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$db->debug_all = false;

// Aim will be to work this out dynamically and do an incremental update each night
$start_date = '2007-01-01 00:00:00';
//$end_date = date('Y-m-d H:i:s');

echo "<hr /><h2>Meeting repair started at $start_date</h2>";

$sq = "update tbl_communications set status_id = 12 where old_status = '0.Meet Set'";
$db->query($sql);
echo "<p>$sql</p>";

// NOTE: we can only repair for the last meeting per post initiative id as its impossible to isolate all the cals made PER MEETING.
// But we can identify the calls made only for the last meeting (or later)
$sql = 'DROP TABLE IF EXISTS `tbl_repair_meetings_shadow_max_id`';
$db->query($sql);
echo "<p>$sql</p>";

$sql =	'CREATE TABLE `tbl_repair_meetings_shadow_max_id` ( ' .
		"`meeting_id` int(11), " .
		'PRIMARY KEY  (`meeting_id`) ' .
		') ENGINE=InnoDB DEFAULT CHARSET=latin1;';
$db->query($sql);
echo "<p>$sql</p>";

$sql = 'INSERT INTO tbl_repair_meetings_shadow_max_id (meeting_id) ' .
		'select max(m.id) ' .
		'from tbl_meetings m ' .
		'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
//		'where pi.id = 10033 ' .
		'where pi.initiative_id  = 1 ' .
		"and m.date >= '2007-01-31 00:00:00' " .
		'group by m.post_initiative_id';
echo "<p>$sql</p>";
$db->query($sql);
//exit();

$sql = 'DROP TABLE IF EXISTS `tbl_repair_meetings_ids`';
$db->query($sql);
echo "<p>$sql</p>";

$sql =	'CREATE TABLE `tbl_repair_meetings_ids` ( ' .
		"`meeting_id` int(11), " .
		"`communication_id` int(11), " .
		"`post_initiative_id` int(11), " .
		"`status_id` int(11), " .
		'PRIMARY KEY  (`meeting_id`), ' .
		'INDEX ix_communication_id (communication_id), ' .
		'INDEX ix_post_initiative_id (post_initiative_id), ' .
		'INDEX ix_status_id (status_id) ' .
		') ENGINE=InnoDB DEFAULT CHARSET=latin1;';
$db->query($sql);
echo "<p>$sql</p>";

$sql = 'INSERT INTO tbl_repair_meetings_ids (meeting_id, communication_id, post_initiative_id, status_id) ' .
		'select m.id, m.communication_id, m.post_initiative_id, m.status_id ' .
		'from tbl_meetings m ' .
		'join tbl_repair_meetings_shadow_max_id max_id on max_id.meeting_id = m.id ' .
		'order by m.id';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'select * from tbl_repair_meetings_ids order by meeting_id';

$meetings = $db->getResults($sql);

$x = 1;

foreach($meetings as $meeting)
{
	echo "<p>$x -------------------- meeting_id: $meeting->meeting_id --------------------</p>";
	$x++;

	// delete any entries in tbl_meetings_shadow
	$sql = 'delete from tbl_meetings_shadow where id = ' . $meeting->meeting_id;
	echo "<p>$sql</p>";
	$db->query($sql);

	$sql = 'select * from tbl_communications com where post_initiative_id = ' .
			$meeting->post_initiative_id . ' and status_id >= 12 and id >= ' . $meeting->communication_id .
			' order by communication_date';
	echo "<p>$sql</p>";
	$db->query($sql);

	$meeting_comms = $db->getResults($sql);
	$status_id = 0;
	$meet_count = 1;
	foreach ($meeting_comms as $meeting_comm)
	{
		if ($status_id != $meeting_comm->status_id)
		{
			if ($meet_count == 1)
			{
				$type_id = 'i';
			}
			else
			{
				$type_id = 'u';
			}
			$new_shadow_id = insertShadowEntry($db, $meeting->meeting_id, $meeting_comm->communication_date, $meeting_comm->user_id, $type_id, $meeting_comm->status_id);
			$status_id = $meeting_comm->status_id;
			$meet_count++;
		}
	}
	echo '<p>Audit queries</p>';
	echo '<br />select * from tbl_communications where post_initiative_id = ' . $meeting->post_initiative_id . ';';
	echo '<br />select * from tbl_meetings_shadow where id = ' . $meeting->meeting_id . ';';
	echo '<br />select * from tbl_meetings where id = ' . $meeting->meeting_id . ';';

	if ($status_id != $meeting->status_id)
	{
		// In this case, we need to copy the entry in tbl_meetings into tbl_meetings_shadow.
		// We will set the shadow_timestamp to be one second after the previous tbl_meetings_shadow entry,
		// and shadow_updated_by will be the same as the previous tbl_meetings_shadow entry.
		// shadow_type will be 'u'.
		$sql = "select shadow_timestamp, shadow_updated_by, shadow_type from tbl_meetings_shadow where id = $meeting->meeting_id order by shadow_id desc limit 1";
		$row = $db->getRow($sql);

		$shadow_timestamp = Utils::dateAdd($row->shadow_timestamp, 'seconds', 1, 'Y-m-d H:i:s');
		echo "<p>Current max shadow_timestamp: " . $row->shadow_timestamp . '<br />';
		echo "New shadow_timestamp: " . $shadow_timestamp . '<br />';
		echo "New shadow_updated_by: " . $row->shadow_updated_by . '</p>';

		$new_shadow_id = insertShadowEntry($db, $meeting->meeting_id, $shadow_timestamp, $row->shadow_updated_by, 'u', $meeting->status_id);
		echo '<p>New entry inserted into tbl_meetings_shadow</p>';

		$sql = "select status_id from tbl_meetings_shadow where id = $meeting->meeting_id order by shadow_id desc limit 1";
		$status_id = $db->get_var($sql);

	}

	echo '<p>checks</p>';
	if ($status_id != $meeting->status_id)
	{
		echo '<p style="color:red">Mis-matched status in tbl_meetings and last entry in tbl_meetings_status</p>';

	}
	else
	{
		echo '<p style="color:green">OK</p>';
	}
	echo '<p>----------------------------------------------------------------------------------</p>';
}



//
// Finish up
//
$sql = 'drop table if exists tbl_repair_meetings_ids';
$db->query($sql);
echo "<p>$sql</p>";
$sql = 'drop table if exists tbl_repair_meetings_shadow_max_id';
$db->query($sql);
echo "<p>$sql</p>";

echo "<p>Done.</p>";
$timeEnd    = gettimeofday();
$timeEnd_uS = $timeEnd["usec"];
$timeEnd_S  = $timeEnd["sec"];
$ExecTime_S = ($timeEnd_S + ($timeEnd_uS / 1000000)) - ($timeStart_S + ($timeStart_uS / 1000000));
echo '<div style="text-align: center; padding-bottom: 5px">Execution Time: ' . round($ExecTime_S, 3) . ' seconds</div>';

function insertShadowEntry($db, $meeting_id, $shadow_timestamp, $shadow_updated_by, $type_id, $status_id)
{

	$sql = 'insert into tbl_meetings_shadow (' .
		'`shadow_timestamp` , ' .
		'`shadow_updated_by` , ' .
		'`shadow_type` , ' .
		'`id` , ' .
		'`post_initiative_id` , ' .
		'`communication_id` , ' .
		'`is_current` , ' .
		'`status_id` , ' .
		'`type_id` , ' .
		'`date` , ' .
		'`reminder_date` , ' .
		'`notes` , ' .
		'`created_at` , ' .
		'`created_by` , ' .
		'`location_id` , ' .
		'`nbm_predicted_rating` , ' .
		'`feedback_rating` , ' .
		'`feedback_decision_maker` , ' .
		'`feedback_agency_user` , ' .
		'`feedback_budget_available`, ' .
		'`feedback_receptive` , ' .
		'`feedback_targeting` , ' .
		'`feedback_meeting_length` , ' .
		'`feedback_comments` , ' .
		'`feedback_next_steps`) ' .
	  	'select ' .
		"'$shadow_timestamp', " .
		$shadow_updated_by . ', ' .
		"'$type_id', " .
	  	'`id`, ' .
		'`post_initiative_id` , ' .
		'`communication_id` , ' .
		'`is_current` , ' .
		$status_id .', ' .
		'`type_id` , ' .
		'`date` , ' .
		'`reminder_date` , ' .
		'`notes` , ' .
		'`created_at` , ' .
		'`created_by` , ' .
		'`location_id` , ' .
		'`nbm_predicted_rating` , ' .
		'`feedback_rating` , ' .
		'`feedback_decision_maker` , ' .
		'`feedback_agency_user` , ' .
		'`feedback_budget_available`, ' .
		'`feedback_receptive` , ' .
		'`feedback_targeting` , ' .
		'`feedback_meeting_length` , ' .
		'`feedback_comments` , ' .
		'`feedback_next_steps` ' .
		'from tbl_meetings ' .
		'where id = ' . $meeting_id;

		echo "<p>Insert shadow table entry: $sql</p>";
		$db->query($sql);

		$sql = 'select max(shadow_id) from tbl_meetings_shadow';
		return $db->get_var($sql);
}

?>