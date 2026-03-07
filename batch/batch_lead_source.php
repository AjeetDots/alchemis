<?php

require_once('/var/www/html/include/EasySql/EasySql.class.php');

define('DB_HOST',     'localhost');
define('DB_NAME',     'alchemis');
define('DB_USER',     'alchemis');
define('DB_PASSWORD', 'rYT4maP7');

$db = new EasySql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$db->debug_all = false;



$start_date = '2007-06-01 00:00:00';
$end_date = '2007-09-30 23:59:59';


$sql = 'DROP TABLE IF EXISTS `tbl_data_statistics_lead_source`';
$db->query($sql);

$sql =	'CREATE TABLE `tbl_data_statistics_lead_source` ( ' .
		'`id` int(11) NOT NULL auto_increment, ' .
		'`campaign_id` int(11) NOT NULL default \'0\', ' .
		'`user_id` int(11) NOT NULL default \'0\', ' .
		'`year_month` char(6) NOT NULL default \'\', ' .
		'`lead_source_id` int(11) default \'0\', ' .
		'`campaign_current_month` int(11) NOT NULL default \'0\', ' .
		'`campaign_monthly_fee` int(11) NOT NULL default \'0\', ' .
		'`campaign_meeting_set_target` int(11) NOT NULL default \'0\', ' .
		'`campaign_meeting_set_target_to_date` int(11) NOT NULL default \'0\', ' .
		'`campaign_meeting_set_count_to_date` int(11) NOT NULL default \'0\', ' .
		'`campaign_meeting_attended_target` int(11) NOT NULL default \'0\', ' .
		'`campaign_meeting_attended_target_to_date` int(11) NOT NULL default \'0\', ' .
		'`campaign_meeting_attended_count_to_date` int(11) NOT NULL default \'0\', ' .
		'`call_count` int(11) NOT NULL default \'0\', ' .
		'`call_effective_count` int(11) NOT NULL default \'0\', ' .
		'`call_ote_count` int(11) NOT NULL default \'0\', ' .
		'`call_access_rate` decimal(10,5) NOT NULL default \'0\', ' .
		'`meeting_set_count` int(11) NOT NULL default \'0\', ' .
		'`meeting_time_lag_0_3` int(11) NOT NULL default \'0\', ' .
		'`meeting_time_lag_3_5` int(11) NOT NULL default \'0\', ' .
		'`meeting_time_lag_5_7` int(11) NOT NULL default \'0\', ' .
		'`meeting_time_lag_7_` int(11) NOT NULL default \'0\', ' .
		'`meeting_in_diary_this_month_count` int(11) NOT NULL default \'0\', ' .		
		'`meeting_attended_count` int(11) NOT NULL default \'0\', ' .
		'`meeting_attended_rate` decimal(10,4) NOT NULL default \'0\', ' .
		'`win_count` int(11) NOT NULL default \'0\', ' .
		'PRIMARY KEY  (`id`), ' .
		'KEY `ix_tbl_data_statistics_campaign_id` (`campaign_id`), ' . 
		'KEY `ix_tbl_data_statistics_user_id` (`user_id`), ' .
		'KEY `ix_tbl_data_statistics_year_month` (`year_month`), ' .
		'KEY `ix_tbl_data_statistics_lead_source_id` (`lead_source_id`) ' .
		') ENGINE=InnoDB DEFAULT CHARSET=latin1;';
$db->query($sql);
echo "<p>$sql/p>";

$sql = 'INSERT INTO tbl_data_statistics_lead_source (campaign_id, user_id, `year_month`, lead_source_id) ' .
		'select ct.campaign_id, cn.user_id, ct.`year_month`, lkp_ls.id ' .
		'from tbl_lkp_lead_source lkp_ls, tbl_campaign_targets ct ' .
		'left join tbl_campaign_nbms cn on cn.campaign_id = ct.campaign_id';  
echo "<p>$sql</p>";
$db->query($sql);


// -- Telephone call count
makeTemporaryTable($db);

$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, lead_source_id, `value`) ' .
		'SELECT i.campaign_id, comm.user_id, ' .
		'EXTRACT(YEAR_MONTH FROM communication_date), ' .
		'comm.lead_source_id, ' . 
		'COUNT(comm.id) ' .
		'from tbl_communications comm ' .
		'join tbl_post_initiatives pi on pi.id = comm.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where communication_date >= \'' . $start_date . '\' ' .
		'and communication_date <= \'' . $end_date . '\' ' .
		'and type_id = 1 ' .
		'group by i.campaign_id, ' .
		'comm.user_id, ' .
		'EXTRACT(YEAR_MONTH FROM communication_date), ' .
		'comm.lead_source_id;';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.`year_month` = ds_temp.`year_month` and ds.lead_source_id = ds_temp.lead_source_id ' .
		'SET call_count = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Telephone effectives count
makeTemporaryTable($db);
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, lead_source_id, `value`) ' .
		'select i.campaign_id, comm.user_id, ' .
		'EXTRACT(YEAR_MONTH FROM communication_date), ' .
		'comm.lead_source_id, ' . 
		'count(comm.id) ' .
		'from tbl_communications comm ' .
		'join tbl_post_initiatives pi on pi.id = comm.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where communication_date >= \'' . $start_date . '\' ' .
		'and communication_date <= \'' . $end_date . '\' ' .
		'and type_id = 1 ' .
		'and is_effective = 1 ' .
		'group by i.campaign_id, ' .
		'comm.user_id, ' .
		'EXTRACT(YEAR_MONTH FROM communication_date), ' .
		'comm.lead_source_id;';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.`year_month` = ds_temp.`year_month` and ds.lead_source_id = ds_temp.lead_source_id ' .
		'SET call_effective_count = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Telephone On target effectives count
makeTemporaryTable($db);

$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, lead_source_id, `value`) ' .
		'select i.campaign_id, comm.user_id, ' .
		'EXTRACT(YEAR_MONTH FROM communication_date), ' .
		'comm.lead_source_id, ' .
		'count(comm.id) ' .
		'from tbl_communications comm ' .
		'join tbl_post_initiatives pi on pi.id = comm.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where communication_date >= \'' . $start_date . '\' ' .
		'and communication_date <= \'' . $end_date . '\' ' .
		'and type_id = 1 ' .
		'and ote = 1 ' .
		'group by i.campaign_id, ' .
		'comm.user_id, ' .
		'EXTRACT(YEAR_MONTH FROM communication_date), ' .
		'comm.lead_source_id;';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.`year_month` = ds_temp.`year_month` and ds.lead_source_id = ds_temp.lead_source_id ' .
		'SET call_ote_count = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Telephone access rate
$sql = 'UPDATE tbl_data_statistics_lead_source ds ' .
		'SET call_access_rate = ROUND((ds.call_effective_count/ds.call_count)*100, 4);'; 
echo "<p>$sql</p>";
$db->query($sql);
exit();

// -- Meeting set count (meetings set in month)
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select i.campaign_id, m_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.created_at), ' .
		'count(m_sh.id) ' .
		'from tbl_meetings_shadow m_sh ' .
		'join tbl_post_initiatives pi on pi.id = m_sh.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where m_sh.created_at >= \'' . $start_date . '\' ' .
		'and m_sh.created_at <= \'' . $end_date . '\' ' .
		'and m_sh.shadow_type = \'i\' ' .
		'group by i.campaign_id, ' .
		'm_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.created_at);';
echo "<p>$sql</p>";

$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET meeting_set_count = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Diary meetings (meetings in diary for this month)
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select i.campaign_id, m_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.date), ' .
		'count(m_sh.id) ' .
		'from tbl_meetings_shadow m_sh ' .
		'join tbl_post_initiatives pi on pi.id = m_sh.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where m_sh.created_at >= \'' . $start_date . '\' ' .
		'and m_sh.created_at <= \'' . $end_date . '\' ' .
		'and m_sh.shadow_type in (\'i\', \'u\') ' .
		'and m_sh.status_id in (10,11,14,15) ' .
		'group by i.campaign_id, ' .
		'm_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.date);';
echo "<p>$sql</p>";

$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET meeting_in_diary_this_month_count = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Meeting attended count (meetings attended in month)
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select i.campaign_id, m_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.date), ' .
		'count(m_sh.id) ' .
		'from tbl_meetings_shadow m_sh ' .
		'join tbl_post_initiatives pi on pi.id = m_sh.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where m_sh.created_at >= \'' . $start_date . '\' ' .
		'and m_sh.created_at <= \'' . $end_date . '\' ' .
		'and m_sh.shadow_type = \'u\' ' .
		'and m_sh.status_id in (18, 19, 20, 21) ' .
		'group by i.campaign_id, ' .
		'm_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.date);';
echo "<p>$sql</p>";

$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET meeting_attended_count = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Meeting attended rate
$sql = 'UPDATE tbl_data_statistics_lead_source ds ' .
		'SET meeting_attended_rate = if((ds.meeting_attended_count >0 and ds.meeting_set_count >0), round((ds.meeting_attended_count/ds.meeting_set_count)*100, 4), 0);'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Win count (wins in month)
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select i.campaign_id, m_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.shadow_timestamp), ' .
		'count(m_sh.id) ' .
		'from tbl_meetings_shadow m_sh ' .
		'join tbl_post_initiatives pi on pi.id = m_sh.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where m_sh.created_at >= \'' . $start_date . '\' ' .
		'and m_sh.created_at <= \'' . $end_date . '\' ' .
		'and m_sh.shadow_type = \'u\' ' .
		'and m_sh.status_id = 23 ' .
		'group by i.campaign_id, ' .
		'm_sh.created_by, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.shadow_timestamp);';
echo "<p>$sql</p>";

$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET win_count = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);


// -- Campaign current month (how many months into the campaign are we?)
$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds ' .
		'join tbl_campaigns c on ds.campaign_id = c.id ' .
		'SET campaign_current_month = PERIOD_DIFF(ds.`year_month`,c. start_year_month)+1;';
echo "<p>$sql</p>";
$db->query($sql);

// -- Campaign meetings set target
// -- Campaign meetings attended target
// -- Campaign monthly fee
$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds ' .
		'join tbl_campaign_targets ct on ds.campaign_id = ct.campaign_id and ds.`year_month` = ct.`year_month` ' .
		'SET ' .
		'campaign_meeting_set_target = ct.meetings_set, ' .
		'campaign_meeting_attended_target = ct.meetings_attended, ' .
		'campaign_monthly_fee = ct.fee';
echo "<p>$sql</p>";
$db->query($sql);

// -- Campaign meeting set target to date
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select x1.campaign_id, 0, x1.`year_month`, sum(x2.meet_set_target) as running_total ' .
		'from ' .
		'( ' .
		'select sum(meetings_set) as meet_set_target, `year_month`, campaign_id ' .
		'from tbl_campaign_targets ' .
		'group by campaign_id, `year_month`) as x1 ' .
		'inner join ' .
		'( ' .
		'select sum(meetings_set) as meet_set_target, `year_month`, campaign_id ' .
		'from tbl_campaign_targets ' .
		'group by campaign_id, `year_month`) as x2 ' .
		'on ' .
		'x1.campaign_id = x2.campaign_id and x1.`year_month` >= x2.`year_month` ' .
		'group by x1.`year_month`, x1.campaign_id';

echo "<p>$sql</p>";
$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.year_month = ds_temp.year_month ' .
		'SET campaign_meeting_set_target_to_date = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Campaign meeting set to date
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select x1.campaign_id, 0, x1.`year_month`, sum(x2.meet_set_count) as running_total ' .
		'from ' .
		'( ' .
		'select sum(meeting_set_count) as meet_set_count, `year_month`, campaign_id ' .
		'from tbl_data_statistics_lead_source ' .
		'group by campaign_id, `year_month`) as x1 ' .
		'inner join ' .
		'( ' .
		'select sum(meeting_set_count) as meet_set_count, `year_month`, campaign_id ' .
		'from tbl_data_statistics_lead_source ' .
		'group by campaign_id, `year_month`) as x2 ' .
		'on ' .
		'x1.campaign_id = x2.campaign_id and x1.`year_month` >= x2.`year_month` ' .
		'group by x1.`year_month`, x1.campaign_id';

echo "<p>$sql</p>";
$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.year_month = ds_temp.year_month ' .
		'SET campaign_meeting_set_count_to_date = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Campaign meeting attended target to date
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select x1.campaign_id, 0, x1.`year_month`, sum(x2.meet_attended_target) as running_total ' .
		'from ' .
		'( ' .
		'select sum(meetings_attended) as meet_attended_target, `year_month`, campaign_id ' .
		'from tbl_campaign_targets ' .
		'group by campaign_id, `year_month`) as x1 ' .
		'inner join ' .
		'( ' .
		'select sum(meetings_attended) as meet_attended_target, `year_month`, campaign_id ' .
		'from tbl_campaign_targets ' .
		'group by campaign_id, `year_month`) as x2 ' .
		'on ' .
		'x1.campaign_id = x2.campaign_id and x1.`year_month` >= x2.`year_month` ' .
		'group by x1.`year_month`, x1.campaign_id';

echo "<p>$sql</p>";
$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.year_month = ds_temp.year_month ' .
		'SET campaign_meeting_attended_target_to_date = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Campaign meeting attended to date
makeTemporaryTable($db);		
	
$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		'select x1.campaign_id, 0, x1.`year_month`, sum(x2.meet_attended_count) as running_total ' .
		'from ' .
		'( ' .
		'select sum(meeting_attended_count) as meet_attended_count, `year_month`, campaign_id ' .
		'from tbl_data_statistics_lead_source ' .
		'group by campaign_id, `year_month`) as x1 ' .
		'inner join ' .
		'( ' .
		'select sum(meeting_attended_count) as meet_attended_count, `year_month`, campaign_id ' .
		'from tbl_data_statistics_lead_source ' .
		'group by campaign_id, `year_month`) as x2 ' .
		'on ' .
		'x1.campaign_id = x2.campaign_id and x1.`year_month` >= x2.`year_month` ' .
		'group by x1.`year_month`, x1.campaign_id';

echo "<p>$sql</p>";
$db->query($sql);
$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.year_month = ds_temp.year_month ' .
		'SET campaign_meeting_attended_count_to_date = ds_temp.`value`;'; 
echo "<p>$sql</p>";
$db->query($sql);

// -- Meeting timelag 
$sql = 'drop table if exists t_meetings;';
echo "<p>$sql</p>";
$sql = 'create temporary table t_meetings ' .
		'select i.campaign_id, m_sh.created_by as user_id, m_sh.id as id, ' .
		'EXTRACT(YEAR_MONTH FROM m_sh.created_at) as `year_month`, ' .
		'CEILING(DATEDIFF(m_sh.date, m_sh.created_at)/7) as time_lag ' .
		'from tbl_meetings_shadow m_sh ' .
		'join tbl_post_initiatives pi on pi.id = m_sh.post_initiative_id ' .
		'join tbl_initiatives i on pi.initiative_id = i.id ' .
		'where m_sh.created_at >= \'' . $start_date . '\' ' .
		'and m_sh.created_at <= \'' . $end_date . '\' ' .
		'and m_sh.shadow_type = \'i\';';
echo "<p>$sql</p>";
$db->query($sql);

// -- Meeting timelag (0-3 weeks)
makeTemporaryTable($db);

$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		' select campaign_id, user_id, `year_month`,  count(id) from t_meetings where time_lag <= 3 group by campaign_id, `year_month`, user_id;';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET meeting_time_lag_0_3 = ds_temp.`value`';
echo "<p>$sql</p>";
$db->query($sql);

// -- Meeting timelag (3-5 weeks)
makeTemporaryTable($db);

$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		' select campaign_id, user_id, `year_month`,  count(id) from t_meetings where time_lag <= 5 and time_lag > 3 group by campaign_id, `year_month`, user_id;';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET meeting_time_lag_3_5 = ds_temp.`value`';
echo "<p>$sql</p>";
$db->query($sql);

// -- Meeting timelag (5-7 weeks)
makeTemporaryTable($db);

$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		' select campaign_id, user_id, `year_month`,  count(id) from t_meetings where time_lag <= 7 and time_lag > 5 group by campaign_id, `year_month`, user_id;';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET meeting_time_lag_5_7 = ds_temp.`value`';
echo "<p>$sql</p>";
$db->query($sql);

// -- Meeting timelag (7+ weeks)
makeTemporaryTable($db);

$sql = 'INSERT INTO tbl_data_statistics_temp (campaign_id, user_id, `year_month`, `value`) ' .
		' select campaign_id, user_id, `year_month`,  count(id) from t_meetings where time_lag > 7 group by campaign_id, `year_month`, user_id;';
echo "<p>$sql</p>";
$db->query($sql);

$sql = 'UPDATE tbl_data_statistics_lead_source ds JOIN tbl_data_statistics_temp ds_temp on ds.campaign_id = ds_temp.campaign_id and ds.user_id = ds_temp.user_id and ds.year_month = ds_temp.year_month ' .
		'SET meeting_time_lag_7_ = ds_temp.`value`';
echo "<p>$sql</p>";
$db->query($sql);


function makeTemporaryTable($db)
{
	$sql =	'DROP TABLE IF EXISTS `tbl_data_statistics_temp`; ';
	echo "<p>$sql</p>";
	$db->query($sql);
	
	$sql = 	'CREATE TEMPORARY TABLE `tbl_data_statistics_temp` ( ' .
			'`id` int(11) NOT NULL auto_increment, ' .
			'`campaign_id` int(11) NOT NULL default \'0\', ' .
			'`user_id` int(11) NOT NULL default \'0\', ' .
			'`year_month` char(6) NOT NULL default \'\', ' .
			'`lead_source_id` int(11) default \'0\', ' .
			'`value` int(11), ' .
			'PRIMARY KEY  (`id`), ' .
			'KEY `campaign_id` (`campaign_id`), ' . 
			'KEY `user_id` (`user_id`), ' .
			'KEY `year_month` (`year_month`), ' .
			'KEY `lead_source_id` (`lead_source_id`) ' .
			');'; 
	echo "<p>$sql</p>";
	$db->query($sql);

}

?>