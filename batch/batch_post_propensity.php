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

define('DB_HOST',     'localhost');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis');
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

//current/previous clients - all meetings ever - meets less than 6 months old
$sql = 	'DROP TABLE IF EXISTS tbl_meets_all_less_than_6_mths;';
echo $sql . '<br /><br />';			

$sql = 'CREATE TEMPORARY TABLE `tbl_meets_all_less_than_6_mths` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`meeting_count` int(11) NOT NULL, ' .
		'PRIMARY KEY  (`post_id`));'; 
echo $sql . '<br /><br />';			
		
$sql = 'insert into tbl_meets_all_less_than_6_mths (post_id, meeting_count) ' .
		'select pi.post_id, count(m.id) as meeting_count ' .
		'from ' .
		'tbl_meetings m ' .
		'join tbl_post_initiatives pi on m.post_initiative_id = pi.id ' .
		"where m.date > concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		"AND m.date <= concat(CURDATE(), ' 00:00:00') " .
		'GROUP BY pi.post_id;';
echo $sql . '<br /><br />';			


//current clients - all meetings ever - meets more than 6 months old
$sql = 	'DROP TABLE IF EXISTS tbl_meets_current_all_more_than_6_mths;';
echo $sql . '<br /><br />';	

$sql = 	'CREATE TEMPORARY TABLE `tbl_meets_current_all_more_than_6_mths` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`meeting_count` int(11) NOT NULL, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			

$sql = 'insert into tbl_meets_current_all_more_than_6_mths (post_id, meeting_count) ' .	
		'select pi.post_id, count(m.id) as meeting_count ' .
		'from ' .
		'tbl_meetings m ' .
		'join tbl_post_initiatives pi on m.post_initiative_id = pi.id ' .
		'join tbl_initiatives i on i.id = pi.initiative_id ' .
		'join tbl_campaigns cam on cam.id = i.campaign_id ' .
		'join tbl_clients cl on cl.id = cam.client_id ' .
		"where m.date < concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'AND cl.is_current = 1 ' .
		'GROUP BY pi.post_id;';
echo $sql . '<br /><br />';			
		
//previous clients - all meetings in last 2 years - more than than 6 months old
$sql = 	'DROP TABLE IF EXISTS tbl_meets_previous_last_two_years_more_than_6_mths;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_meets_previous_last_two_years_more_than_6_mths` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`meeting_count` int(11) NOT NULL, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			
		
$sql = 'insert into tbl_meets_previous_last_two_years_more_than_6_mths (post_id, meeting_count) ' .	
		'select pi.post_id, count(m.id) as meeting_count ' .
		'from ' .
		'tbl_meetings m ' .
		'join tbl_post_initiatives pi on m.post_initiative_id = pi.id ' .
		'join tbl_initiatives i on i.id = pi.initiative_id ' .
		'join tbl_campaigns cam on cam.id = i.campaign_id ' .
		'join tbl_clients cl on cl.id = cam.client_id ' .
		"where m.date >= concat(CURDATE(), ' 00:00:00') - INTERVAL 2 YEAR " .
		"and m.date < concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'AND cl.is_current = 0 ' .
		'GROUP BY pi.post_id;';
echo $sql . '<br /><br />';			
		
//current/previous clients - all meetings ever - attended less than 6 months old
$sql = 	'DROP TABLE IF EXISTS tbl_meets_all_attended_less_than_6_mths;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_meets_all_attended_less_than_6_mths` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`meeting_count` int(11) NOT NULL, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			

$sql = 'insert into tbl_meets_all_attended_less_than_6_mths (post_id, meeting_count) ' .	
		'select pi.post_id, count(m.id) as meeting_count ' .
		'from ' .
		'tbl_meetings m ' .
		'join tbl_post_initiatives pi on m.post_initiative_id = pi.id ' .
		"where m.date >= concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		"AND m.date <= concat(CURDATE(), ' 00:00:00') " .
		'AND m.status_id >= 24 ' .
		'GROUP BY pi.post_id;';
echo $sql . '<br /><br />';			
	
//current clients - all meetings ever - attended more than 6 months
$sql = 	'DROP TABLE IF EXISTS tbl_meets_current_all_attended_more_than_6_mths;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_meets_current_all_attended_more_than_6_mths` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`meeting_count` int(11) NOT NULL, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			
		
$sql = 'insert into tbl_meets_current_all_attended_more_than_6_mths (post_id, meeting_count) ' .	
		'select pi.post_id, count(m.id) as meeting_count ' .
		'from ' .
		'tbl_meetings m ' .
		'join tbl_post_initiatives pi on m.post_initiative_id = pi.id ' .
		'join tbl_initiatives i on i.id = pi.initiative_id ' .
		'join tbl_campaigns cam on cam.id = i.campaign_id ' .
		'join tbl_clients cl on cl.id = cam.client_id ' .
		"where m.date < concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'AND m.status_id >= 24 ' .
		'AND cl.is_current = 1 ' .
		'GROUP BY pi.post_id;';
echo $sql . '<br /><br />';			

//previous clients - all meetings in last 2 years - attended more than 6 months old
$sql = 	'DROP TABLE IF EXISTS tbl_meets_previous_last_two_years_attended_more_than_6_mths;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_meets_previous_last_two_years_attended_more_than_6_mths` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`meeting_count` int(11) NOT NULL, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			
		
$sql = 'insert into tbl_meets_previous_last_two_years_attended_more_than_6_mths (post_id, meeting_count) ' .	
		'select pi.post_id, count(m.id) as meeting_count ' .
		'from ' .
		'tbl_meetings m ' .
		'join tbl_post_initiatives pi on m.post_initiative_id = pi.id ' .
		'join tbl_initiatives i on i.id = pi.initiative_id ' .
		'join tbl_campaigns cam on cam.id = i.campaign_id ' .
		'join tbl_clients cl on cl.id = cam.client_id ' .
		"where m.date >= concat(CURDATE(), ' 00:00:00') - INTERVAL 2 YEAR " .
		"and m.date < concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'AND m.status_id >= 24 ' .
		'AND cl.is_current = 0 ' .
		'GROUP BY pi.post_id;';
echo $sql . '<br /><br />';			
	
//OTE less than 6 months
$sql = 	'DROP TABLE IF EXISTS tbl_ote_less_than_6_months;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_ote_less_than_6_months` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`ote_count` int(11) default 0, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			
		
$sql = 'insert into tbl_ote_less_than_6_months (post_id, ote_count) ' .	
		'select post_id, 1 as ote_count ' .
		'from ' .
		'tbl_communications com ' .
		'join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
		"where com.communication_date >= concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'AND com.ote = 1 ' .
		'GROUP BY post_id;';
echo $sql . '<br /><br />';			

//OTE more than 6 months
$sql = 	'DROP TABLE IF EXISTS tbl_ote_more_than_6_months;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_ote_more_than_6_months` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`ote_count` int(11) default 0, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';		
		
$sql = 'insert into tbl_ote_more_than_6_months (post_id, ote_count) ' .	
		'select post_id, 1 as ote_count ' .
		'from ' .
		'tbl_communications com ' .
		'join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
		"AND com.communication_date < concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'AND com.ote = 1 '.
		'GROUP BY post_id;';
echo $sql . '<br /><br />';			

//Agency user less than 6 months
$sql = 	'DROP TABLE IF EXISTS tbl_agency_user_less_than_6_months;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_agency_user_less_than_6_months` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`agency_count` int(11) default 0, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			
		
$sql = 'insert into tbl_agency_user_less_than_6_months (post_id, agency_count) ' .
		'select post_id, 1 ' .
		'from ' .
		'tbl_post_agency_users ' .
		"where last_updated_at >= concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'and type_id != 2 '.
		'GROUP BY post_id;';
echo $sql . '<br /><br />';			

//Agency user more than 6 months
$sql = 	'DROP TABLE IF EXISTS tbl_agency_user_more_than_6_months;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_agency_user_more_than_6_months` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		'`agency_count` int(11) default 0, ' .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			
		
$sql = 'insert into tbl_agency_user_more_than_6_months (post_id, agency_count) ' .
		'select post_id, 1 ' .
		'from ' .
		'tbl_post_agency_users ' .
		"where last_updated_at < concat(CURDATE(), ' 00:00:00') - INTERVAL 6 MONTH " .
		'and type_id != 2 '.
		'GROUP BY post_id;';
echo $sql . '<br /><br />';			

// make temp table with all the posts in - and columns for each of the calculation elements
$sql = 	'DROP TABLE IF EXISTS tbl_propensity_calcs;';
echo $sql . '<br /><br />';	
$sql = 	'CREATE TEMPORARY TABLE `tbl_propensity_calcs` ( ' .
		'`post_id` int(11) NOT NULL, ' .
		"`meeting_count` int(11) default '0', " .
		"`attended_count` int(11) default '0', " .
		'PRIMARY KEY  (`post_id`));';
echo $sql . '<br /><br />';			


$sql = 'insert into tbl_propensity_calcs (post_id) ' .
		'select id ' .
		'from ' .
		'tbl_posts ' .
		'where deleted = 0;';
echo $sql . '<br /><br />';			

$sql = 'update tbl_propensity_calcs pc ' .
		'left join tbl_meets_all_less_than_6_mths m1 on pc.post_id = m1.post_id ' .
		'left join tbl_meets_current_all_more_than_6_mths m2 on pc.post_id = m2.post_id ' .
		'left join tbl_meets_previous_last_two_years_more_than_6_mths m3 on pc.post_id = m3.post_id ' .
		'set pc.meeting_count = IFNULL(m1.meeting_count, 0) + IFNULL(m2.meeting_count, 0) + IFNULL(m3.meeting_count, 0);';
echo $sql . '<br /><br />';			

$sql = 'update tbl_propensity_calcs pc ' .
		'left join tbl_meets_all_attended_less_than_6_mths m1 on pc.post_id = m1.post_id ' .
		'left join tbl_meets_current_all_attended_more_than_6_mths m2 on pc.post_id = m2.post_id ' .
		'left join tbl_meets_previous_last_two_years_attended_more_than_6_mths m3 on pc.post_id = m3.post_id ' .
		'set pc.attended_count = IFNULL(m1.meeting_count, 0) + IFNULL(m2.meeting_count, 0) + IFNULL(m3.meeting_count, 0);';
echo $sql . '<br /><br />';		
		
// varMeetingLessThan6MonthsCount = 0
//        If Not IsNull(varMeetingLessThan6Months) Then
//          varMeetingLessThan6MonthsCount = varMeetingLessThan6Months
//        End If
//        
//        varMeetingMoreThan6MonthsCount = 0
//        If Not IsNull(varMeetingMoreThan6Months) Then
//          varMeetingMoreThan6MonthsCount = varMeetingLessThan6Months
//        End If
//        
//        If varMeetingLessThan6MonthsCount + varMeetingMoreThan6MonthsCount >= 2 Then
//          intPropensity = intPropensity + 17
//        Else
//          If varMeetingLessThan6MonthsCount > 0 Then
//            intPropensity = intPropensity + 15
//          ElseIf varMeetingMoreThan6MonthsCount > 0 Then
//            intPropensity = intPropensity + 10
//          End If
//        End If
        
        
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