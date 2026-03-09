<?php

/**
 * Defines the app_mapper_ReportReaderMapper class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ReaderMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_ReportReaderMapper extends app_mapper_ReaderMapper
{
	protected $select_stmt;
	protected $select_all_stmt;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select
		$query = 'SELECT * FROM tbl_lkp_reports WHERE id = ?';
		$types = array('integer');
		$this->select_stmt = self::$DB->prepare($query, $types);

		// Select all
		$this->select_all_stmt = self::$DB->prepare('SELECT * FROM tbl_lkp_reports WHERE active = 1 ORDER BY name');
	}

	/**
	 * Returns the details for a given report.
	 * @param integer $id
	 * @return array assoicative array mapping to a given report record
	 */
	public function find($id)
	{
		$data = array($id);
		$result = $this->doStatement($this->select_stmt, $data);
		return $result->fetchRow(MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Find all owners.
	 * @return app_mapper_OwnerCollection collection of app_domain_Owner objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->select_all_stmt, array());
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Returns the timestamp of when the data statistics where last compiled.
	 * @return string timestamp
	 */
	public static function getDataStatisticsLastRun()
	{
		$query = 'SELECT start, end, UNIX_TIMESTAMP(end) - UNIX_TIMESTAMP(start) AS execution_time FROM tbl_data_statistics_run ORDER BY start DESC LIMIT 1';
		$result = self::$DB->query($query);
		return $result->fetchRow(MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Returns the data for report 1.
	 * @param string $year_month in the format 'YYYYMM'
	 * @param integer $user_id restrict to a given user
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 * @return array associative array mapping to a given report record
	 */
	public function getReport1MainData($year_month, $user_id, $nbm_exclusions = null, $client_id = null)
	{
//		print_r($nbm_exclusions);

		// Work out previous and next month
		$year  = substr($year_month, 0, 4);
		$month = substr($year_month, 4, 2);
		$prev_year_month = date('Ym', mktime(0, 0, 0, $month-1, 1, $year));
		$next_year_month = date('Ym', mktime(0, 0, 0, $month+1, 1, $year));

		// Get the data for the previous month
		$sql = 'CREATE TEMPORARY TABLE t_prev ' .
				'SELECT ' .
				'ds.campaign_id, ds.user_id, ' .
//				'ds.call_effective_count AS effectives, ' .
//				'ds.call_effective_target AS effectives_target, ' .
//				'ds.campaign_meeting_set_target_to_date - ds.campaign_meeting_set_count_to_date AS target, ' .

				'tar.meetings_set_imperative, ' .
				'ds.meeting_set_count AS meetings_set, ' .
				'ds.meeting_category_attended_count AS meetings_attended ' .

				'FROM tbl_data_statistics AS ds ' .
				'LEFT JOIN tbl_campaign_nbm_targets AS tar ON ds.user_id = tar.user_id AND ds.campaign_id = tar.campaign_id AND ds.`year_month` = tar.`year_month` ' .
				'JOIN tbl_rbac_users u ON u.id = ds.user_id ' .
				'WHERE ds.`year_month` = ' . self::$DB->quote($prev_year_month, 'text') . ' ';
		// Optionally filter by user ID
		if ($user_id > 0)
		{
			$sql .= ' AND ds.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ';
		}
		// Optionally exclude NBMs
		if (!empty($nbm_exclusions))
		{
			$sql .= ' AND ds.user_id NOT IN (' . self::$DB->escape(implode(',', $nbm_exclusions)) . ')';
		}

		if (!empty($client_id)) {
			$sql .= ' AND u.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

//		echo "<p>$sql</p>";
		self::$DB->query($sql);


		// Get data for current month
		$sql = 'CREATE TEMPORARY TABLE t_curr ' .
				'SELECT ' .
				'cli.name AS client, ' .
				'u.name AS user, ' .
				'tar.`year_month`, ' .
				'tar.user_id, ' .
				'tar.campaign_id, ' .

				'tar.planned_days, ' .                                                                 // Days
				'tar.effectives AS effectives_target, ' .                                              // Effect
				'ds.campaign_meeting_set_target_to_date AS cumulative_meet_set_target, ' .             // Campaign
				'tar.meetings_set AS meetings_set_target, ' .                                          // Target
				'tar.meetings_set_imperative, ' .                                                      // Impv
				'ds.meeting_set_count AS meetings_set, ' .                                             // Set act

				'ds.campaign_meeting_category_attended_target_to_date AS cumulative_meet_attended_target, ' .   // Camp Atd
				'tar.meetings_attended AS meetings_attended_target, ' .                                // Atd tgt
				'ds.meeting_category_attended_count AS meetings_attended, ' .                                   // Atd act

				'ds.meeting_in_diary_this_month_count AS diary_meets ' .                               // Diary

				'FROM tbl_campaign_nbm_targets AS tar ' .
				'INNER JOIN tbl_campaigns AS cam ON tar.campaign_id = cam.id ' .
				'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
				'INNER JOIN tbl_rbac_users AS u ON tar.user_id = u.id ' .
				'INNER JOIN tbl_data_statistics AS ds ON tar.user_id = ds.user_id AND tar.campaign_id = ds.campaign_id AND tar.`year_month` = ds.`year_month` ' .
				'WHERE tar.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
				'AND u.is_active = 1 ';
		// Optionally filter by user ID
		if ($user_id > 0)
		{
			$sql .= 'AND u.id = ' . self::$DB->quote($user_id, 'integer') . ' ';
		}

		if (!empty($client_id)) {
			$sql .= ' AND u.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		// Optionally exclude NBMs
		if (!empty($nbm_exclusions))
		{
			$sql .= 'AND tar.user_id NOT IN (' . self::$DB->escape(implode(',', $nbm_exclusions)) . ') ';
		}

		// Finish query string
		$sql .= 'ORDER BY u.name, cli.name';
//		echo "<p>$sql</p>";

//		$sql = "CREATE TEMPORARY TABLE t1 " .
//				"SELECT tar.*, cli.name AS client, u.name AS user " .
//				"FROM tbl_campaign_nbm_targets AS tar " .
//				"INNER JOIN tbl_campaigns AS cam ON tar.campaign_id = cam.id " .
//				"INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id " .
//				"INNER JOIN tbl_rbac_users AS u ON tar.user_id = u.id " .
//				"WHERE tar.`year_month` = '" . $this->params['year_month'] . "' " .
//				"AND u.is_active = 1 " .
//				"ORDER BY u.name, cli.name";
//		echo "<p>$sql</p>";
		self::$DB->query($sql);


		// Get the data for the next month
		$sql = 'CREATE TEMPORARY TABLE t_next ' .
				'SELECT ' .
				'campaign_id, ' .
				'user_id, ' .
//				'call_effective_count AS effectives, ' .
//				'call_effective_target AS effectives_target, ' .

				// Next month effectives
				'call_effective_target_to_date AS effectives_target, ' .
				'call_effective_count_to_date AS effectives, ' .
				'call_effective_target_to_date - call_effective_count_to_date AS next_effective_target, ' .

				// Next month meetings
				'campaign_meeting_set_target_to_date, ' .
				'campaign_meeting_set_count_to_date, ' .
				'campaign_meeting_set_target_to_date - campaign_meeting_set_count_to_date AS meeting_target, ' .

				// Next month imperatives
				'campaign_meeting_set_imperative_to_date, ' .
				'campaign_meeting_set_imperative_to_date - campaign_meeting_set_count_to_date AS meeting_imperative ' .

				'FROM tbl_data_statistics s ' .
				'JOIN tbl_rbac_users u ON s.user_id = u.id ' .
				'WHERE `year_month` = ' . self::$DB->quote($next_year_month, 'text');
		// Optionally filter by user ID
		if ($user_id > 0)
		{
			$sql .= ' AND user_id = ' . self::$DB->quote($user_id, 'integer');
		}
		// Optionally exclude NBMs
		if (!empty($nbm_exclusions))
		{
			$sql .= ' AND user_id NOT IN (' . self::$DB->escape(implode(',', $nbm_exclusions)) . ')';
		}

		if (!empty($client_id)) {
			$sql .= ' AND u.client_id = ' . self::$DB->quote($client_id, 'integer');
		}

//		echo "<p>$sql</p>";
		self::$DB->query($sql);


		// Bring it all together
		$sql = 'SELECT ' .

		't_curr.year_month, ' .
				't_curr.client, ' .
				't_curr.user_id, ' .
				't_curr.user, ' .
//				'IFNULL(t_prev.effectives, 0) AS prev_effectives, ' .
//				'IFNULL(t_prev.effectives_target, 0) AS prev_effectives_target, ' .
//				'IFNULL(t_prev.target, 0) AS prev_target, ' .

				// Previous month
				'IFNULL(t_prev.meetings_set_imperative, 0) AS prev_meetings_set_imperative, ' .
				'IFNULL(t_prev.meetings_set, 0) AS prev_meetings_set, ' .
				'IFNULL(t_prev.meetings_attended, 0) AS prev_meetings_attended, ' .

				// Current month
				't_curr.planned_days, ' .                                               // Days
				't_curr.effectives_target, ' .                                          // Effect
				't_curr.cumulative_meet_set_target AS cumulative_meet_set_target,  ' .  // Campaign
				't_curr.meetings_set_target, ' .                                        // Target
				't_curr.meetings_set_imperative, ' .                                    // Impv
				't_curr.meetings_set, ' .                                               // Set act
				't_curr.cumulative_meet_attended_target,  ' .                           // Campaign Atd
				't_curr.meetings_attended_target, ' .                                   // Atd tgt
				't_curr.meetings_attended, ' .                                          // Atd act
				't_curr.diary_meets, ' .                                                // Diary

				// Next month effectives
				'IFNULL(t_next.effectives, 0) AS call_effective_count_to_date, ' .
				'IFNULL(t_next.effectives_target, 0) AS call_effective_target_to_date, ' .
				'IFNULL(t_next.next_effective_target, 0) AS next_effective_target, ' .

				// Next month meetings
				'IFNULL(t_next.campaign_meeting_set_target_to_date, 0) AS next_campaign_meeting_set_target_to_date, ' .
				'IFNULL(t_next.campaign_meeting_set_count_to_date, 0) AS next_campaign_meeting_set_count_to_date, ' .
				'IFNULL(t_next.meeting_target, 0) AS next_meeting_target, ' .

				// Next month imperatives
				'IFNULL(t_next.campaign_meeting_set_imperative_to_date, 0) AS next_campaign_meeting_set_imperative_to_date, ' .
				'IFNULL(t_next.meeting_imperative, 0) AS next_meeting_imperative ' .

				'FROM t_curr ' .
				'LEFT JOIN t_prev ON t_curr.campaign_id = t_prev.campaign_id AND t_curr.user_id = t_prev.user_id ' .
				'LEFT JOIN t_next ON t_curr.campaign_id = t_next.campaign_id AND t_curr.user_id = t_next.user_id ' .
				'ORDER BY t_curr.user, t_curr.client';
//		echo "<p>$sql</p>";
		$results = self::$DB->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);

		// Drop temporary tables
		self::$DB->query('DROP TEMPORARY TABLE t_prev');
		self::$DB->query('DROP TEMPORARY TABLE t_curr');
		self::$DB->query('DROP TEMPORARY TABLE t_next');

		return $results;
	}

	/**
	 * Returns the holiday data used in Report1.
	 * @param string $year_month in the format 'YYYYMM'
	 * @param integer $user_id
	 * @return array assoicative array mapping to a given report record
	 */
	public function getReport1HolidayData($year_month, $user_id, $client_id = null)
	{
		$year  = substr($year_month, 0, 4);
		$month = substr($year_month, 4, 2);
		$start = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
		$end   = date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));

		// Start query string
		$sql = 'SELECT u.name AS user, COUNT(e.id) AS count ' .
				'FROM tbl_events AS e I' .
				'NNER JOIN tbl_rbac_users AS u ON e.user_id = u.id ' .
				'WHERE e.type_id = 2 ' .
				'AND e.date >= ' . self::$DB->quote($start, 'text') . ' ' .
				'AND e.date <= ' . self::$DB->quote($end, 'text') . ' ';

		// Optionally filter by user ID
		if ($user_id > 0)
		{
			$sql .= 'AND u.id = ' . self::$DB->quote($user_id, 'integer') . ' ';
		}

		if (!empty($client_id)) {
			$sql .= ' AND u.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		// Finish query string
		$sql .= 'GROUP BY u.name ORDER BY u.name';

//		echo "<p>$sql</p>";
		$results = self::$DB->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	/**
	 * Get a list of the new starter for a given month.
	 * @param string $year_month in the format 'YYYYMM'
	 * @param return where each item is a client name
	 */
	public function getNewStarters($year_month, $user_id)
	{
//		$sql = "SELECT cli.name AS client, 'abc' AS nbm " .
//				'FROM tbl_campaigns AS cam ' .
//				'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
//				'WHERE cam.start_year_month = ' . self::$DB->quote($year_month, 'text') . ' ' .
//				'ORDER BY cli.name';


		$sql = 'CREATE TEMPORARY TABLE t1_nbms ' .
				'SELECT nbm.*, u.name AS user ' .
				'FROM tbl_campaign_nbms AS nbm ' .
				'INNER JOIN tbl_rbac_users AS u ON nbm.user_id = u.id ' .
				'WHERE nbm.is_lead_nbm = 1';
//		echo "<p>$sql</p>";
		self::$DB->query($sql);

		$sql = "SELECT cli.name AS client, IFNULL(nbm.user, 'TBA') AS nbm " .
				'FROM tbl_campaigns AS cam ' .
				'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
				'LEFT JOIN t1_nbms AS nbm ON cam.id = nbm.campaign_id ' .
				'WHERE cam.start_year_month = ' . self::$DB->quote($year_month, 'text') . ' ';
		// Optionally filter by user ID
		if ($user_id > 0)
		{
			$sql .= 'AND nbm.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ';
		}
		// Finish query
		$sql .= 'ORDER BY cli.name';

//		echo "<p>$sql</p>";
//		$results = self::$DB->queryCol($sql);
		$results = self::$DB->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);

		self::$DB->query('DROP TEMPORARY TABLE t1_nbms');
		return $results;
	}

	/**
	 * Returns the data for report 2.
	 * @param string $start in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 * @return array associative array mapping to a given report record
	 */
	public function getReport2Data($start, $end, $nbm_exclusions = null, $client_id = null)
	{
//		$start = $this->params['start'];
//		$end   = $this->params['end'];
//		$db = $this->getDb();

		// Calls
		$query = "CREATE TEMPORARY TABLE t1 " .
					"SELECT comm.user_id, COUNT(comm.id) AS calls " .
					"FROM tbl_communications AS comm " .
					"WHERE comm.communication_date >= '$start' " .
					"AND comm.communication_date <= '$end' " .
					"AND comm.type_id = 1 " .
					"GROUP BY comm.user_id";
		self::$DB->query($query);

		// Non-Effectives
		$query = "CREATE TEMPORARY TABLE t2 " .
					"SELECT comm.user_id, COUNT(comm.id) AS `non_effectives` " .
					"FROM tbl_communications AS comm " .
					"WHERE comm.communication_date >= '$start' " .
					"AND comm.communication_date <= '$end' " .
					"AND comm.type_id = 1 " .
					"AND effective = 'non-effective' " .
					"GROUP BY comm.user_id";
		self::$DB->query($query);

		// Effectives
		$query = "CREATE TEMPORARY TABLE t3 " .
					"SELECT comm.user_id, COUNT(comm.id) AS `effectives` " .
					"FROM tbl_communications AS comm " .
					"WHERE comm.communication_date >= '$start' " .
					"AND comm.communication_date <= '$end' " .
					"AND comm.type_id = 1 " .
					"AND effective = 'effective' " .
					"GROUP BY comm.user_id";
		self::$DB->query($query);

		// On Target Effectives
		$query = "CREATE TEMPORARY TABLE t4 " .
					"SELECT comm.user_id, COUNT(comm.id) AS `otes` " .
					"FROM tbl_communications AS comm " .
					"WHERE comm.communication_date >= '$start' " .
					"AND comm.communication_date <= '$end' " .
					"AND comm.type_id = 1 " .
					"AND effective = 'effective' " .
					"AND ote = 1 " .
					"GROUP BY comm.user_id";
		self::$DB->query($query);

		// Off Target Effectives
		$query = "CREATE TEMPORARY TABLE t5 " .
				"SELECT comm.user_id, COUNT(comm.id) AS `non_otes` " .
				"FROM tbl_communications AS comm " .
				"WHERE comm.communication_date >= '$start' " .
				"AND comm.communication_date <= '$end' " .
				"AND comm.type_id = 1 " .
				"AND effective = 'effective' " .
				"AND ote = 0 " .
				"GROUP BY comm.user_id";
		self::$DB->query($query);

		// Meetings set
//		$query = "CREATE TEMPORARY TABLE t6 " .
//					"SELECT created_by AS user_id, COUNT(id) AS meetings " .
//					"FROM tbl_meetings " .
//					"WHERE created_at >= '$start' " .
//					"AND created_at <= '$end' " .
//					"AND status_id IN (12,13,18,19) " .
//					"GROUP BY user_id";

		$query = "CREATE TEMPORARY TABLE t6 " .
					"SELECT created_by AS user_id, COUNT(id) AS meetings " .
					"FROM tbl_meetings_shadow " .
					"WHERE created_at >= '$start' " .
					"AND created_at <= '$end' " .
					"AND status_id IN	 (12,13) " .
					"AND shadow_type = 'i' " .
					"GROUP BY user_id";


		self::$DB->query($query);

		// Meetings attended
		$query = 'CREATE TEMPORARY TABLE t7 ' .
			"SELECT created_by AS user_id, COUNT(id) AS meetings_attended " .
			"FROM tbl_meetings m " .
		'WHERE m.date >= \'' . $start . '\' ' .
		'AND m.date <= \'' . $end . '\' ' .
		'AND m.status_id >= 24 ' .
		"GROUP BY user_id";
		self::$DB->query($query);

		// Calls before 10am
		$query = "CREATE TEMPORARY TABLE t9 " .
					"SELECT comm.user_id, COUNT(comm.id) AS calls " .
					"FROM tbl_communications AS comm " .
					"WHERE comm.communication_date >= '$start' " .
					"AND comm.communication_date <= '$end' " .
					"AND comm.type_id = 1 " .
					"AND TIME(comm.communication_date) <= '10:00:00' " .
					"GROUP BY comm.user_id";
		self::$DB->query($query);

		// Tasks completed
		$query = "CREATE TEMPORARY TABLE t10 " .
					"SELECT user_id, COUNT(id) AS tasks_completed " .
					"FROM tbl_actions " .
					"WHERE completed_date >= '$start' " .
					"AND completed_date <= '$end' " .
					"GROUP BY user_id";
		self::$DB->query($query);

		// Tasks outstanding
		$query = "CREATE TEMPORARY TABLE t11 " .
					"SELECT user_id, COUNT(id) AS tasks_outstanding " .
					"FROM tbl_actions " .
					"WHERE completed_date IS NULL " .
					"OR completed_date = '0000-00-00 00:00:00' " .
					"GROUP BY user_id";
		self::$DB->query($query);

		// Meets diary overdue
		$query = 'CREATE TEMPORARY TABLE t12 ' .
					'SELECT created_by AS user_id, COUNT(id) AS meets_dairy_overdue ' .
					'FROM tbl_meetings ' .
					'WHERE status_id IN (12,13,18,19) ' .
					'AND NOW() > date ' .
					'GROUP BY created_by';
		self::$DB->query($query);

		// Meets diary pending
		$query = 'CREATE TEMPORARY TABLE t13 ' .
					'SELECT created_by AS user_id, COUNT(id) AS meets_dairy_pending ' .
					'FROM tbl_meetings ' .
					'WHERE status_id IN (12,13,18,19) ' .
					'AND date > NOW() ' .
//					"AND date <= '$end' " .
					'GROUP BY created_by';
		self::$DB->query($query);

		// Bring it all together
		$query = "SELECT t1.user_id, u.name AS nbm, t.name AS team, " .
					"IFNULL(t1.calls, 0) AS calls, " .
					"IFNULL(t2.non_effectives, 0) AS non_effectives, " .
					"IFNULL(t3.effectives, 0) AS effectives, " .
					"IFNULL(t4.otes, 0) AS on_target, " .
					"IFNULL(t5.non_otes, 0) AS off_target, " .
					"(IFNULL(t6.meetings, 0) / IFNULL(t3.effectives, 0)) * 100 AS conversion, " .
					"IFNULL(t6.meetings, 0) AS meetings, " .
					"IFNULL(t7.meetings_attended, 0) AS attended, " .
					"IFNULL(t1.calls, 0) + (10 * IFNULL(t3.effectives, 0)) + (100 * IFNULL(t6.meetings, 0)) AS kpis, " .
					"IFNULL(t9.calls, 0) AS calls_logged_by_10am, " .
					"IFNULL(t10.tasks_completed, 0) AS tasks_completed, " .
					"IFNULL(t11.tasks_outstanding, 0) AS tasks_outstanding, " .
					"IFNULL(t12.meets_dairy_overdue, 0) AS meets_diary_overdue, " .
					"IFNULL(t13.meets_dairy_pending, 0) AS meets_diary_pending, " .
					"IFNULL(t1.calls, 0) + (10 * IFNULL(t3.effectives, 0)) + (100 * IFNULL(t6.meetings, 0)) + (150 * IFNULL(t7.meetings_attended, 0)) AS super_kpis " .
					'FROM t1 ' .
					'LEFT JOIN t2 ON t1.user_id = t2.user_id ' .
					'LEFT JOIN t3 ON t1.user_id = t3.user_id ' .
					'LEFT JOIN t4 ON t1.user_id = t4.user_id ' .
					'LEFT JOIN t5 ON t1.user_id = t5.user_id ' .
					'LEFT JOIN t6 ON t1.user_id = t6.user_id ' .
					'LEFT JOIN t7 ON t1.user_id = t7.user_id ' .
					'LEFT JOIN t9 ON t1.user_id = t9.user_id ' .
					'LEFT JOIN t10 ON t1.user_id = t10.user_id ' .
					'LEFT JOIN t11 ON t1.user_id = t11.user_id ' .
					'LEFT JOIN t12 ON t1.user_id = t12.user_id ' .
					'LEFT JOIN t13 ON t1.user_id = t13.user_id ' .
					'LEFT JOIN tbl_rbac_users AS u ON t1.user_id = id ' .
					'LEFT JOIN tbl_team_nbms AS tn ON u.id = tn.user_id ' .
					'LEFT JOIN tbl_teams AS t ON tn.team_id = t.id ';

		$where = false;
		if (!empty($nbm_exclusions))
		{
			$query .= 'WHERE t1.user_id NOT IN (' . self::$DB->escape(implode(',', $nbm_exclusions)) . ') ';
			$where = true;
		}

		if (!empty($client_id)) {
			if ($where) {
				$query .= 'AND ';
			} else {
				$query .= 'WHERE ';
			}
			$query .= 'u.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'ORDER BY u.name';
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		self::$DB->query('DROP TEMPORARY TABLE t1');
		self::$DB->query('DROP TEMPORARY TABLE t2');
		self::$DB->query('DROP TEMPORARY TABLE t3');
		self::$DB->query('DROP TEMPORARY TABLE t4');
		self::$DB->query('DROP TEMPORARY TABLE t5');
		self::$DB->query('DROP TEMPORARY TABLE t6');
		self::$DB->query('DROP TEMPORARY TABLE t7');
//		self::$DB->query('DROP TEMPORARY TABLE t8');
		self::$DB->query('DROP TEMPORARY TABLE t9');
		self::$DB->query('DROP TEMPORARY TABLE t10');
		self::$DB->query('DROP TEMPORARY TABLE t11');
		self::$DB->query('DROP TEMPORARY TABLE t12');
		self::$DB->query('DROP TEMPORARY TABLE t13');

		return $results;
	}

//	/**
//	 *
//	 * @return array
//	 */
//	public function getReport3Data()
//	{
//		$startdate = '2007-07-01 00:00:00';
//		$enddate   = '2007-09-30 23:59:59';
//
//		$query = 'SELECT ds.user_id, cam.client_id, cli.name AS client, ds.`year_month`, COUNT(ds.call_fresh_effective_count) AS call_fresh_effective_count ' .
//					'FROM tbl_data_statistics AS ds ' .
//					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
//					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
//					'GROUP BY ds.user_id, cam.client_id, ds.`year_month`';
////		echo "<p>$query</p>";
//		$result = self::$DB->query($query);
//		return self::mdb2ResultToArray($result);
//	}

	/**
	 * Get the summary data for Report 3.
	 * @param string $start date in the format 'YYYY-MM-DD'
	 * @param integer $user_id
	 * @return array
	 */
	public function getReport3SummaryData($start, $user_id)
	{
		$query = 'SELECT u.name AS nbm, ' .
					'SUM(ds.information_request_count) AS information_request_count, ' .  // Yes
					'SUM(ds.information_request_pending_count) AS information_request_pending, ' .
					'SUM(ds.information_request_failed_count) AS information_request_failed, ' .
					'SUM(ds.information_request_converted_count) AS information_request_converted, ' .
					'ROUND((SUM(ds.information_request_failed_count) / SUM(ds.information_request_count)) * 100, 0) AS information_request_percentage_failed, ' .
					'ROUND((SUM(ds.information_request_converted_count) / SUM(ds.information_request_count)) * 100, 0) AS information_request_percentage_converted, ' .
					'SUM(ds.call_back_effective_count) AS call_back_effective_count, ' .  // Yes
					'SUM(ds.call_fresh_effective_count) AS call_fresh_effective_count ' .  // Yes
					'FROM tbl_rbac_users AS u ' .
					'INNER JOIN tbl_data_statistics AS ds ON u.id = ds.user_id ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
					'WHERE u.is_active = 1 ';
		if ($user_id > 0)
		{
			$query .= 'AND u.id = ' . self::$DB->quote($user_id, 'integer') . ' ';
		}
		$query .= 'AND ds.`year_month` >= ' . self::$DB->quote($start, 'text') . ' ' .
					'GROUP BY ds.user_id ' .
					'ORDER BY u.name';
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

//		$sql = 'SELECT u.name AS nbm, ' .
//					'SUM(ds.information_request_count) AS information_request_count, ' .
//					'SUM(ds.information_request_pending_count) AS information_request_pending, ' .
//					'SUM(ds.information_request_failed_count) AS information_request_failed, ' .
//					'SUM(ds.information_request_converted_count) AS information_request_converted, ' .
//					'ROUND((SUM(ds.information_request_failed_count) / SUM(ds.information_request_count)) * 100, 0) AS information_request_percentage_failed, ' .
//					'ROUND((SUM(ds.information_request_converted_count) / SUM(ds.information_request_count)) * 100, 0) AS information_request_percentage_converted, ' .
//					'SUM(ds.call_fresh_effective_count) AS call_fresh_effective_count ' .
//					'FROM tbl_rbac_users AS u ' .
	}

	/**
	 * Get the detail data for Report 3.
	 * @param string $start date in the format 'YYYY-MM-DD'
	 * @param integer $user_id
	 * @return array
	 */
	public function getReport3DetailData($start, $user_id)
	{
		$query = 'SELECT u.id AS user_id, u.name AS nbm, cli.name AS client, ' .
					'SUM(ds.information_request_count) AS information_request_count, ' .
					'SUM(ds.information_request_pending_count) AS information_request_pending, ' .
					'SUM(ds.information_request_failed_count) AS information_request_failed, ' .
					'SUM(ds.information_request_converted_count) AS information_request_converted, ' .
					'(SUM(ds.information_request_failed_count) / SUM(ds.information_request_count) * 100) AS information_request_percentage_failed, ' .
					'(SUM(ds.information_request_converted_count) / SUM(ds.information_request_count) * 100) AS information_request_percentage_converted, ' .
					'SUM(ds.call_back_effective_count) AS call_back_effective_count, ' .  // Yes
					'SUM(ds.call_fresh_effective_count) AS call_fresh_effective_count ' .  // Yes
					'FROM tbl_rbac_users AS u ' .
					'INNER JOIN tbl_data_statistics AS ds ON u.id = ds.user_id ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
					'WHERE u.is_active = 1 ';
		if ($user_id > 0)
		{
			$query .= 'AND u.id = ' . self::$DB->quote($user_id, 'integer') . ' ';
		}
		$query .= 'AND ds.`year_month` >= ' . self::$DB->quote($start, 'text') . ' ' .
					'GROUP BY ds.user_id, ds.campaign_id ' .
					'ORDER BY u.name, cli.name';
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Get the summary data for Report 5.
	 * @param string $start date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $client_id
	 * @param integer $all_statuses whether to include all statuses
	 * @return array
	 */
	public function getReport5SummaryData($start, $end, $client_id, $project_ref, $all_statuses = false)
	{
		if (is_null($project_ref))
		{
			// Find effectives
			$query = 'CREATE TEMPORARY TABLE t1_rpt5 ' .
						'SELECT cs.id AS status_id, ' .
						'COUNT(comm.id) AS effectives ' .
						'FROM tbl_lkp_communication_status AS cs ' .
						'LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id ' .
						'INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id ' .
						'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
						'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
						'WHERE cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
						'AND comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
						'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
						'GROUP BY cs.id';
			self::$DB->query($query);

			// Find non-effectives
			$query = 'CREATE TEMPORARY TABLE t2_rpt5 ' .
						'SELECT cs.id AS status_id, ' .
						'COUNT(comm.id) AS non_effectives ' .
						'FROM tbl_lkp_communication_status AS cs ' .
						'LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id ' .
						'LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
						'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
						'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
						'WHERE comm.is_effective = 0 ' .
						'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
						'AND comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
						'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
						'GROUP BY cs.id';
	//		echo "<p>$query</p>";
			self::$DB->query($query);
		}
		else
		{
			// Find effectives
			$query = 'CREATE TEMPORARY TABLE t1_rpt5 ' .
						'SELECT cs.id AS status_id, ' .
						'COUNT(comm.id) AS effectives ' .
						'FROM tbl_lkp_communication_status AS cs ' .
						'LEFT JOIN tbl_post_initiatives AS pi ON cs.id = pi.status_id ' .
						'INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id ' .
						'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
						'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
						'LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id ' .
						'LEFT JOIN tbl_tags t on pit.tag_id = t.id ' .
						'WHERE cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
						'AND t.value = ' . self::$DB->quote($project_ref, 'text') . ' ' .
						'AND t.category_id = 3 ' .
						'AND comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
						'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
						'GROUP BY cs.id';
//			echo "<p>$query</p>";
			self::$DB->query($query);

			// Find non-effectives
			$query = 'CREATE TEMPORARY TABLE t2_rpt5 ' .
						'SELECT cs.id AS status_id, ' .
						'COUNT(comm.id) AS non_effectives ' .
						'FROM tbl_lkp_communication_status AS cs ' .
						'LEFT JOIN tbl_communications AS comm ON cs.id = comm.status_id ' .
						'LEFT JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
						'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
						'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
						'LEFT JOIN tbl_post_initiative_tags pit ON pi.id = pit.post_initiative_id ' .
						'LEFT JOIN tbl_tags t on pit.tag_id = t.id ' .
						'WHERE comm.is_effective = 0 ' .
						'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
						'AND t.value = ' . self::$DB->quote($project_ref, 'text') . ' ' .
						'AND t.category_id = 3 ' .
						'AND comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
						'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
						'GROUP BY cs.id';
//			echo "<p>$query</p>";
			self::$DB->query($query);
		}

		// Join
		$query = 'SELECT cs.id AS status_id, cs.description, cs.full_description, ' .
					"IFNULL(t1.effectives, '') AS effectives, " .
					"IFNULL(t2.non_effectives, '') AS non_effectives " .
					'FROM tbl_lkp_communication_status AS cs ' .
					'LEFT JOIN t1_rpt5 AS t1 ON cs.id = t1.status_id ' .
					'LEFT JOIN t2_rpt5 AS t2 ON cs.id = t2.status_id ';

		if (!$all_statuses)
		{
			$query .= 'WHERE t1.effectives IS NOT NULL OR t2.non_effectives IS NOT NULL ';
		}

		$query .= 'ORDER BY cs.sort_order DESC';

		// Run query
		$rows = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		self::$DB->query('DROP TEMPORARY table t1_rpt5');
		self::$DB->query('DROP TEMPORARY table t2_rpt5');

		return $rows;
	}

	/**
	 * Get the detail data for Report 5.
	 * @param string $start date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $client_id
	 * @param string $project_ref
	 * @param integer $effectives
	 * @return array
	 */
	public function getReport5DetailData($start, $end, $client_id, $project_ref, $effectives)
	{
		// If required, limit communications to:
		//    (1) Effectives
		//    (2) Non-Effectives
		//    (3) Effectives and Non-Effectives
		switch($effectives)
		{
			case 1: // effectives only
//				$query = 'CREATE TEMPORARY table t1 ' .
//					'SELECT id, communication_date, note_id FROM tbl_communications ' .
//					'WHERE is_effective = 1 ' .
//					'AND communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
//					'AND communication_date <= ' . self::$DB->quote($end, 'timestamp');

				$query = 'CREATE TEMPORARY table t ' .
					'SELECT max(id) as id, post_initiative_id ' .
					'FROM tbl_communications ' .
					'WHERE is_effective = 1 ' .
					'AND communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'GROUP BY post_initiative_id';
				self::$DB->query($query);

				$query = 'CREATE TEMPORARY table t1 ' .
					'SELECT comm.id, comm.post_initiative_id, communication_date, note_id ' .
					'FROM tbl_communications comm ' .
					'JOIN t on t.id = comm.id';
				break;
			case 2: // non-effectives only
//				$query = 'CREATE TEMPORARY table t1 ' .
//					'SELECT id, communication_date, note_id FROM tbl_communications ' .
//					'WHERE is_effective = 0 ' .
//					'AND communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
//					'AND communication_date <= ' . self::$DB->quote($end, 'timestamp');
				$query = 'CREATE TEMPORARY table t ' .
					'SELECT max(id) as id, post_initiative_id ' .
					'FROM tbl_communications ' .
					'WHERE is_effective = 0 ' .
					'AND communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'GROUP BY post_initiative_id';
				self::$DB->query($query);

				$query = 'CREATE TEMPORARY table t1 ' .
					'SELECT comm.id, comm.post_initiative_id, communication_date, note_id ' .
					'FROM tbl_communications comm ' .
					'JOIN t on t.id = comm.id';

				break;
			case 3: // effectives and non-effectives
//				$query = 'CREATE TEMPORARY table t1 ' .
//					'SELECT id, communication_date, note_id FROM tbl_communications ' .
//					'WHERE communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
//					'AND communication_date <= ' . self::$DB->quote($end, 'timestamp');
				$query = 'CREATE TEMPORARY table t ' .
					'SELECT max(id) as id, post_initiative_id ' .
					'FROM tbl_communications ' .
					'WHERE communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'GROUP BY post_initiative_id';
				self::$DB->query($query);

				$query = 'CREATE TEMPORARY table t1 ' .
					'SELECT comm.id, comm.post_initiative_id, communication_date, note_id ' .
					'FROM tbl_communications comm ' .
					'JOIN t on t.id = comm.id';

				break;
		}
//		echo $query;
		self::$DB->query($query);

		if (is_null($project_ref))
		{
			$query = 'SELECT pi.id AS post_initiative_id, ' .
		                'comp.id AS company_id, ' .
		                'comp.name AS company, ' .
		                'cs1.sort_order, ' .
		                'cs1.id AS status_id, ' .
		                'cs1.description AS status, ' .
		                'comm.communication_date AS date, ' .
		                //'pin.created_at AS date, ' .
		                'pin.note AS note, ' .
		                'cli.name AS client, ' .
		                'post.id AS post_id, ' .
		                'post.job_title, ' .
		                "IFNULL(post.full_name, '') AS full_name " .
		                'FROM tbl_post_initiatives as pi ' .
		                'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
		                'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
		                'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
		                'INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id ' .
		                'INNER JOIN tbl_companies AS comp ON post.company_id = comp.id ' .
		                'LEFT JOIN tbl_sites AS site ON comp.id = site.company_id ' .
		                'INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id ';

            switch($effectives)
			{
				case 1: // effectives only
//					$query .= 'INNER JOIN t1 AS comm ON comm.id = pi.last_effective_communication_id ';
					$query .= 'INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id ';
					break;
				case 2: // non-effectives only
				case 3: // effectives and non-effectives
//					$query .= 'INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id ';
					$query .= 'INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id ';
					break;
			}

			$query .= 'LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id ' .
            		    'WHERE ' .
		                'cli.id = ' .self::$DB->quote($client_id, 'integer') . ' ' .
		                'ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;';
		}
		else
		{
			$query = 'SELECT pi.id AS post_initiative_id, ' .
		                'comp.id AS company_id, ' .
		                'comp.name AS company, ' .
		                'cs1.sort_order, ' .
		                'cs1.id AS status_id, ' .
		                'cs1.description AS status, ' .
		                'comm.communication_date AS date, ' .
		                //'pin.created_at AS date, ' .
		                'pin.note AS note, ' .
		                'cli.name AS client, ' .
		                'post.id AS post_id, ' .
		                'post.job_title, ' .
		                "IFNULL(post.full_name, '') AS full_name " .
		                'FROM tbl_post_initiatives as pi ' .
		                'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
		                'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
		                'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
		                'INNER JOIN vw_posts_contacts AS post ON pi.post_id = post.id ' .
		                'INNER JOIN tbl_companies AS comp ON post.company_id = comp.id ' .
		                'LEFT JOIN tbl_sites AS site ON comp.id = site.company_id ' .
		                'INNER JOIN tbl_lkp_communication_status AS cs1 ON pi.status_id = cs1.id ';

//            switch($effectives)
//			{
//				case 1: // effectives only
//					$query .= 'INNER JOIN t1 AS comm ON comm.id = pi.last_effective_communication_id ';
//					break;
//				case 2: // non-effectives only
//				case 3: // effectives and non-effectives
//					$query .= 'INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id ';
//					break;
//			}
            switch($effectives)
			{
				case 1: // effectives only
//					$query .= 'INNER JOIN t1 AS comm ON comm.id = pi.last_effective_communication_id ';
					$query .= 'INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id ';
					break;
				case 2: // non-effectives only
				case 3: // effectives and non-effectives
//					$query .= 'INNER JOIN t1 AS comm ON comm.id = pi.last_communication_id ';
					$query .= 'INNER JOIN t1 AS comm ON comm.post_initiative_id = pi.id ';
					break;
			}

			$query .= 'INNER JOIN tbl_post_initiative_tags AS pit ON pi.id = pit.post_initiative_id ' .
						'INNER JOIN tbl_tags AS t ON pit.tag_id = t.id ' .
		                'LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id ' .
		                'WHERE ' .
		                'cli.id = ' .self::$DB->quote($client_id, 'integer') . ' ' .
		                'AND t.value = ' . self::$DB->quote($project_ref, 'text') . ' ' .
						'AND t.category_id = 3 ' .
		                'ORDER BY cs1.sort_order DESC, comp.name, comm.communication_date;';
		}
//		echo $query;
		// Run query
		$rows = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		self::$DB->query('DROP TEMPORARY table t1');

		return $rows;
	}

	/**
	 * Get the full effective note history for a post initiative before a given date.
	 * @param string  $post_initiative_id the post inititative ID
	 * @param string  $date               the date, in the format 'YYYY-MM-DD HH:MM:SS', up to which we want the notes
	 * @param integer $effectives         whether to show (1) effectives, (2) non-effectives, or (3) both
	 * @return array
	 */
	public function getReport5FullNotesHistory($post_initiative_id, $date, $effectives)
	{
		switch($effectives)
		{
			case 1: // effectives only
				$query = 'SELECT cs.description AS status, ' .
							'comm.communication_date AS date, ' .
							'pin.note AS note ' .
							'FROM tbl_post_initiative_notes AS pin ' .
							'LEFT JOIN tbl_communications AS comm ON comm.note_id = pin.id ' .
							'LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id ' .
							'WHERE pin.post_initiative_id = ' .self::$DB->quote($post_initiative_id, 'integer') . ' ' .
							'AND comm.is_effective = 1 ' .
							'AND pin.for_client = 1 ' .
//							'AND pin.created_at <= ' . self::$DB->quote($date, 'timestamp') . ' ' .
							'AND comm.communication_date <= ' . self::$DB->quote($date, 'timestamp') . ' ' .
							'ORDER by pin.created_at DESC';

							//echo $query;
				break;

			case 2: // non-effectives only
				$query = 'CREATE TEMPORARY table t1 ' .
							'SELECT pin.id, pin.post_initiative_id, pin.note AS note ' .
							'FROM tbl_post_initiative_notes AS pin ' .
							'WHERE pin.post_initiative_id = ' .self::$DB->quote($post_initiative_id, 'integer') . ' ' .
							'AND pin.for_client = 1 ' .
							'AND pin.created_at <= ' . self::$DB->quote($date, 'timestamp');
				self::$DB->query($query);

				$query = 'SELECT cs.description AS status, ' .
							'comm.communication_date AS date, ' .
							'pin.note AS note ' .
							'FROM tbl_communications AS comm ' .
							'LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id ' .
							'LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id ' .
							'LEFT JOIN t1 ON comm.note_id = t1.id ' .
							'WHERE comm.post_initiative_id = ' .self::$DB->quote($post_initiative_id, 'integer') . ' ' .
							'AND comm.communication_date <= ' . self::$DB->quote($date, 'timestamp') . ' ' .
							'AND comm.is_effective = 0 ' .
							'ORDER by comm.communication_date DESC';
				break;

			case 3: // effectives and non-effectives
				$query = 'CREATE TEMPORARY table t1 ' .
							'SELECT pin.id, pin.post_initiative_id, pin.note AS note ' .
							'FROM tbl_post_initiative_notes AS pin ' .
							'WHERE pin.post_initiative_id = ' .self::$DB->quote($post_initiative_id, 'integer') . ' ' .
							'AND pin.for_client = 1 ' .
							'AND pin.created_at <= ' . self::$DB->quote($date, 'timestamp');
				self::$DB->query($query);

				$query = 'SELECT cs.description AS status, ' .
							'comm.communication_date AS date, ' .
							'pin.note AS note ' .
							'FROM tbl_communications AS comm ' .
							'LEFT JOIN tbl_post_initiative_notes AS pin ON comm.note_id = pin.id ' .
							'LEFT JOIN tbl_lkp_communication_status AS cs ON comm.status_id = cs.id ' .
							'LEFT JOIN t1 ON comm.note_id = t1.id ' .
							'WHERE comm.post_initiative_id = ' .self::$DB->quote($post_initiative_id, 'integer') . ' ' .
							'AND comm.communication_date <= ' . self::$DB->quote($date, 'timestamp') . ' ' .
							'ORDER by comm.communication_date DESC';
                break;
		}

		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		self::$DB->query('DROP TEMPORARY table IF EXISTS t1');

		return $result;
	}

	/**
	 * Returns the data for report 6.
	 * @param string $start in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $order_by
	 * @return array assoicative array mapping to a given report record
	 */
	public function getReport6Data($start, $end, $order_by = 0, $client_id = null)
	{
		//$debug = true;

		// New meetings
		$query = 'CREATE TEMPORARY TABLE m1 ' .
					'SELECT cam.client_id, m.post_initiative_id, MIN(m.created_at) AS date ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'GROUP BY cam.id, m.post_initiative_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		$query = 'CREATE TEMPORARY TABLE m2 ' .
					'SELECT m1.client_id, COUNT(m1.client_id) AS new_meets ' .
					'FROM m1 ' .
					'WHERE m1.date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m1.date <= ' . self::$DB->quote($end, 'timestamp') . ' ';

		if (!is_null($client_id))
		{
			$query .= 'AND m1.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY m1.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		//
		// Campaign Statistics data
		//

		// Campaign Owner and Campaign Month
		$query = 'CREATE TEMPORARY TABLE cs1 ' .
					"SELECT cam.client_id, IFNULL(u.name, '') AS campaign_owner, ds.campaign_current_month AS campaign_month " .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'LEFT JOIN tbl_campaign_nbms AS cn ON cam.id = cn.campaign_id ' .
					'LEFT JOIN tbl_rbac_users AS u ON cn.user_id = u.id ' .
					'WHERE ds.`year_month` = ' . self::$DB->quote(date('Ym'), 'text') . ' ' .
					'AND cn.is_lead_nbm = 1 ';

		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY ds.campaign_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Notice Month
		$query = 'CREATE TEMPORARY TABLE cs2 ' .
					'SELECT cam.client_id, cam.end_year_month AS campaign_notice_month ' .
					'FROM tbl_campaigns AS cam ';

		if (!is_null($client_id))
		{
			$query .= 'WHERE cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Campaign Meets Set +/-
		$query = 'CREATE TEMPORARY TABLE cs3 ' .
					'SELECT cam.client_id, ' .
					'ds.campaign_meeting_set_count_to_date - ds.campaign_meeting_set_target_to_date AS campaign_meets_set_compare ' .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'WHERE ds.`year_month` = ' . self::$DB->quote(date('Ym'), 'text') . ' ';

		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY ds.campaign_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Campaign Meets Att +/-
		$query = 'CREATE TEMPORARY TABLE cs4 ' .
					'SELECT cam.client_id, ' .
					'ds.campaign_meeting_category_attended_count_to_date - ds.campaign_meeting_category_attended_target_to_date AS campaign_meets_attended_compare ' .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'WHERE ds.`year_month` = ' . self::$DB->quote(date('Ym'), 'text') . ' ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY ds.campaign_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		//
		// Imperative Target for Period
		//
		$start_year_month = date('Ym', strtotime($start));
		$end_year_month   = date('Ym', strtotime($end));
		$query = 'CREATE TEMPORARY TABLE it1 ' .
					'SELECT cam.client_id, SUM(ds.campaign_meeting_set_imperative) AS imperative_target_for_period ' .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'WHERE ds.`year_month` >= ' . self::$DB->quote($start_year_month, 'text') . ' ' .
					'AND ds.`year_month` <= ' . self::$DB->quote($end_year_month, 'text') . ' ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		//
		// Selected Period Statistics
		//

		// Total Calls
		$query = 'CREATE TEMPORARY TABLE t1 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS calls ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.type_id = 1 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Non Target Sector Calls
		$query = 'CREATE TEMPORARY TABLE t8 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS non_target_sector_calls ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.type_id = 1 ' .
					'AND comm.ote = 0 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Non Target Sector Effectives
		$query = 'CREATE TEMPORARY TABLE t9 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS non_target_sector_effectives ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.type_id = 1 ' .
					'AND comm.ote = 0 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Non-Effectives
		$query = 'CREATE TEMPORARY TABLE t2 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS `non_effectives` ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.type_id = 1 ' .
					'AND comm.is_effective = 0 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';


		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Effectives
		$query = 'CREATE TEMPORARY TABLE t3 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS `effectives` ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.type_id = 1 ' .
					'AND comm.is_effective = 1 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// On Target Effectives
		$query = 'CREATE TEMPORARY TABLE t4 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS `otes` ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.type_id = 1 ' .
					'AND comm.is_effective = 1 ' .
					'AND comm.ote = 1 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Off Target Effectives
		$query = 'CREATE TEMPORARY TABLE t5 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS `non_otes` ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.type_id = 1 ' .
					'AND comm.is_effective = 1 ' .
					'AND ote = 0 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Meets Set
		$query = 'CREATE TEMPORARY TABLE t6 ' .
					'SELECT cam.client_id, COUNT(m.id) AS total_meets_set ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.created_at >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') . ' ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Meets Rearranged
		$query = 'CREATE TEMPORARY TABLE t13 ' .
					'SELECT cam.client_id, COUNT(m.id) AS meets_rearranged ' .
					'FROM tbl_meetings_shadow AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.created_at >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND m.status_id IN (18, 19) ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Meetings Due to be Attended
		$query = 'CREATE TEMPORARY TABLE t10 ' .
					'SELECT cam.client_id, COUNT(m.id) AS actual_meets_attended ' .
					'FROM tbl_meetings_shadow AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m.date <= ' . self::$DB->quote($end, 'timestamp') . ' ';

		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Actual Meetings Attended
		$query = 'CREATE TEMPORARY TABLE t7 ' .
					'SELECT cam.client_id, COUNT(m.id) AS actual_meets_attended ' .
					'FROM tbl_meetings_shadow AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m.date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND m.status_id >= 24 ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Meets Lapsed
		$query = 'CREATE TEMPORARY TABLE t15 ' .
					'SELECT cam.client_id, COUNT(m.id) AS meets_lapsed ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m.date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND m.status_id IN (12, 13, 18, 19) ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Meets to be Rearranged
		$query = 'CREATE TEMPORARY TABLE t16 ' .
					'SELECT cam.client_id, COUNT(m.id) AS meets_to_be_rearranged ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m.date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND m.status_id IN (14, 15, 16, 17) ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Meets in Diary for period
		$query = 'CREATE TEMPORARY TABLE t11 ' .
					'SELECT cam.client_id, COUNT(m.id) AS meets_in_diary_for_period ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND m.date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND m.status_id IN (12, 13, 18, 19) ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Future Meets
		$query = 'CREATE TEMPORARY TABLE t12 ' .
					'SELECT cam.client_id, COUNT(m.id) AS future_meets ' .
					'FROM tbl_meetings_shadow AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE m.date > ' . self::$DB->quote($end, 'timestamp') . ' ' .
					"AND m.shadow_type in ('i', 'u') " .
					'AND m.status_id IN (12, 13, 18, 19) ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Receptive Call Backs in Next 0-4 Weeks
		// Add 4 weeks to end of period
		$end_unix_timestamp = strtotime($end);
		$end_plus_4 = date('Y-m-d H:i:s', mktime(date('H', $end_unix_timestamp), date('i', $end_unix_timestamp), date('s', $end_unix_timestamp), date('m', $end_unix_timestamp), date('d', $end_unix_timestamp) + 28, date('Y', $end_unix_timestamp)));
		$query = 'CREATE TEMPORARY TABLE t17 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS receptive_call_backs_0_4 ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.next_communication_date > ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND comm.next_communication_date <= ' . self::$DB->quote($end_plus_4, 'timestamp') . ' ' .
					'AND comm.status_id IN (4,5,6) ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Receptive Call Backs in Next 4-8 Weeks
		// Add 8 weeks to end of period
		$end_plus_8 = date('Y-m-d H:i:s', mktime(date('H', $end_unix_timestamp), date('i', $end_unix_timestamp), date('s', $end_unix_timestamp), date('m', $end_unix_timestamp), date('d', $end_unix_timestamp) + 56, date('Y', $end_unix_timestamp)));
		$query = 'CREATE TEMPORARY TABLE t18 ' .
					'SELECT cam.client_id, COUNT(comm.id) AS receptive_call_backs_4_8 ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE comm.next_communication_date > ' . self::$DB->quote($end_plus_4, 'timestamp') . ' ' .
					'AND comm.next_communication_date <= ' . self::$DB->quote($end_plus_8, 'timestamp') . ' ' .
					'AND comm.status_id IN (4,5,6) ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';

		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Info Request Conversion
		$query = 'CREATE TEMPORARY TABLE t14 ' .
					'SELECT cam.client_id, (SUM(ds.information_request_converted_count) / SUM(ds.information_request_count)) * 100 AS info_request_conversion ' .
					'FROM tbl_data_statistics_daily AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'WHERE ds.date >= ' . self::$DB->quote($start, 'date') . ' ' .
					'AND ds.date <= ' . self::$DB->quote($end, 'date') . ' ';
		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		$query .= 'GROUP BY cam.client_id';
		if ($debug) echo "<p>$query;</p>";
		self::$DB->query($query);

		// Bring it all together
		$query = 'SELECT t1.client_id, ' .
					'IFNULL(cli.name, \'\') AS client, ' .
					'IFNULL(cs1.campaign_owner, \' \') AS campaign_owner, ' .
					'IFNULL(cs1.campaign_month, \' \') AS campaign_month, ' .
					'IFNULL(cs2.campaign_notice_month, \'\') AS campaign_notice_month, ' .
					'IFNULL(cs3.campaign_meets_set_compare, 0) AS campaign_meets_set_compare, ' .
					'IFNULL(cs4.campaign_meets_attended_compare, 0) AS campaign_meets_attended_compare, ' .
					'IFNULL(it1.imperative_target_for_period, 0) AS imperative_target_for_period, ' .
					'IFNULL(t1.calls, 0) AS totals_calls, ' .
					'IFNULL(t8.non_target_sector_calls, 0) AS non_target_sector_calls, ' .
					'IFNULL(t3.effectives, 0) AS total_effectives, ' .
					'IFNULL(t4.otes, 0) AS on_target_effectives, ' .
					'IFNULL(t5.non_otes, 0) AS off_target_effectives, ' .
					'IFNULL(t9.non_target_sector_effectives, 0) AS non_target_sector_effectives, ' .
					'(IFNULL(t3.effectives, 0) / IFNULL(t1.calls, 0)) * 100 AS overall_access, ' .
					'(IFNULL(t4.otes, 0) / IFNULL(t1.calls, 0)) * 100 AS on_target_access, ' .
					'IFNULL(t6.total_meets_set, 0) AS total_meets_set, ' .
					'IFNULL(m2.new_meets, 0) AS new_meets_set, ' .
					'IFNULL(t6.total_meets_set, 0) - IFNULL(m2.new_meets, 0) AS second_meets_set, ' .
					'IFNULL(t13.meets_rearranged, 0) AS meets_rearranged, ' .
					'(IFNULL(t6.total_meets_set, 0) / IFNULL(t3.effectives, 0)) * 100 AS total_conversion, ' .
					'(IFNULL(t6.total_meets_set, 0) / IFNULL(t4.otes, 0)) * 100 AS ote_conversion, ' .
					'IFNULL(t10.actual_meets_attended, 0) AS meets_due_to_be_attended, ' .
					'IFNULL(t7.actual_meets_attended, 0) AS actual_meets_attended, ' .
					'IFNULL(t15.meets_lapsed, 0) AS meets_lapsed, ' .
					'IFNULL(t16.meets_to_be_rearranged, 0) AS meets_to_be_rearranged, ' .
					'IFNULL(t11.meets_in_diary_for_period, 0) AS meets_in_diary_for_period, ' .
					'IFNULL(t12.future_meets, 0) AS future_meets, ' .
					'IFNULL(t17.receptive_call_backs_0_4, 0) AS receptive_call_backs_0_4, ' .
					'IFNULL(t18.receptive_call_backs_4_8, 0) AS receptive_call_backs_4_8, ' .
					'IFNULL(t14.info_request_conversion, 0) AS info_request_conversion ' .
					'FROM tbl_clients AS cli ' .
					'INNER JOIN tbl_campaigns AS cam ON cli.id = cam.client_id ' .
					'INNER JOIN tbl_campaign_nbms AS cn ON cam.id = cn.campaign_id ' .
					'LEFT JOIN cs1 ON cli.id = cs1.client_id ' .
					'LEFT JOIN cs2 ON cli.id = cs2.client_id ' .
					'LEFT JOIN cs3 ON cli.id = cs3.client_id ' .
					'LEFT JOIN cs4 ON cli.id = cs4.client_id ' .
					'LEFT JOIN t1 ON cli.id = t1.client_id ' .
					'LEFT JOIN t2 ON t1.client_id = t2.client_id ' .
					'LEFT JOIN t3 ON t1.client_id = t3.client_id ' .
					'LEFT JOIN t4 ON t1.client_id = t4.client_id ' .
					'LEFT JOIN t5 ON t1.client_id = t5.client_id ' .
					'LEFT JOIN t6 ON t1.client_id = t6.client_id ' .
					'LEFT JOIN t7 ON t1.client_id = t7.client_id ' .
					'LEFT JOIN t8 ON t1.client_id = t8.client_id ' .
					'LEFT JOIN t9 ON t1.client_id = t9.client_id ' .
					'LEFT JOIN t10 ON t1.client_id = t10.client_id ' .
					'LEFT JOIN t11 ON t1.client_id = t11.client_id ' .
					'LEFT JOIN t12 ON t1.client_id = t12.client_id ' .
					'LEFT JOIN t13 ON t1.client_id = t13.client_id ' .
					'LEFT JOIN t14 ON t1.client_id = t14.client_id ' .
					'LEFT JOIN t15 ON t1.client_id = t15.client_id ' .
					'LEFT JOIN t16 ON t1.client_id = t16.client_id ' .
					'LEFT JOIN t17 ON t1.client_id = t17.client_id ' .
					'LEFT JOIN t18 ON t1.client_id = t18.client_id ' .
					'LEFT JOIN it1 ON t1.client_id = it1.client_id ' .
					'LEFT JOIN m2 ON t1.client_id = m2.client_id ' .
					'WHERE cam.start_year_month IS NOT NULL ' .
					'AND cn.user_id = ' . self::$DB->quote(self::getCurrentUserId(), 'integer') . ' ';

		if (!is_null($client_id))
		{
			$query .= 'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
		}

		// Order by
		switch ($order_by)
		{
			case app_report_Report6::ORDER_BY_CLIENT_NAME:
				$query .= 'ORDER BY cli.name';
				break;

			case app_report_Report6::ORDER_BY_STATUS:
				$query .= 'ORDER BY cs4.campaign_meets_attended_compare';
				break;

			case app_report_Report6::ORDER_BY_CAMPAIGN_OWNER:
				$query .= 'ORDER BY cs1.campaign_owner';
				break;

			case app_report_Report6::ORDER_BY_CAMPAIGN_MONTH:
				$query .= 'ORDER BY cs1.campaign_month';
				break;

			case app_report_Report6::ORDER_BY_CAMPAIGN_MEETS_SET:
				$query .= 'ORDER BY campaign_meets_set_compare';
				break;

			case app_report_Report6::ORDER_BY_CAMPAIGN_MEETS_ATTENDED:
				$query .= 'ORDER BY campaign_meets_attended_compare';
				break;

			default:
				$query .= 'ORDER BY cli.name';
				break;
		}

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS m1');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS m2');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS cs1');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS cs2');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS cs3');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS cs4');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t1');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t2');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t3');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t4');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t5');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t6');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t7');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t8');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t9');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t10');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t11');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t12');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t13');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t14');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t15');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t16');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t17');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t18');
		self::$DB->query('DROP TEMPORARY TABLE IF EXISTS t19');
		return $results;
	}


	public function getReport7ClientCampaignSummary($start, $end, $client_id)
	{
//		$debug = true;

		$query = 'create temporary table tmp_NbmsWithCallsInPeriod ' .
        'SELECT ds.user_id, sum(call_count) as calls ' .
        'from ' .
        'tbl_data_statistics_daily ds ' .
        'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
        'join tbl_rbac_users u on ds.user_id = u.id ' .
        'WHERE date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
        'AND date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
        'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
        'GROUP BY ds.user_id ' .
        'HAVING calls > 0';

        $values = array();
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);


		$query = 'select ' .
					'name, ' .
					"date_format(concat(start_year_month, '01000000') , '%Y-%m-%d %H:%i:%s') as campaign_start_date, " .
					"date_format(concat(start_year_month, '01') , '%M %Y') as start_year_month, " .
					'PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM (CURRENT_DATE)), cam.start_year_month) + 1 as campaign_month, ' .
					'is_current ' .
					'from tbl_clients c ' .
					'join tbl_campaigns cam on c.id = cam.client_id ' .
					'AND c.id = ' . self::$DB->quote($client_id, 'integer');

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7ClientCampaignDisciplines($client_id)
	{
//		$debug = true;
		$query = 'select ' .
					'tc.value as discipline ' .
					'from tbl_campaign_disciplines cd ' .
					'join tbl_campaigns cam on cd.campaign_id = cam.id ' .
					'join tbl_tiered_characteristics tc on tc.id = cd.tiered_characteristic_id ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer');

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7ClientCampaignLeadNbm($client_id)
	{
//		$debug = true;
		$query = 'select ' .
					'rbac.name, cnbm.deactivated_date ' .
					'from tbl_campaign_nbms cnbm ' .
					'join tbl_campaigns cam on cnbm.campaign_id = cam.id ' .
					'join tbl_rbac_users rbac on rbac.id = cnbm.user_id ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'and cnbm.is_lead_nbm = true';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7ClientCampaignNonLeadNbm($client_id)
	{
//		$debug = true;


		$query = 'select ' .
					'rbac.name, cnbm.deactivated_date ' .
					'from tbl_campaign_nbms cnbm ' .
					'join tbl_campaigns cam on cnbm.campaign_id = cam.id ' .
					'join tbl_rbac_users rbac on rbac.id = cnbm.user_id ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'and cnbm.is_lead_nbm = false ' .
		            'join tmp_NbmsWithCallsInPeriod t on t.user_id = rbac.id ' .
					'order by deactivated_date, name';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7MeetingsTargets($start, $end, $client_id)
	{
//		$debug = true;
		$query = 'select ' .
					'sum(calls) AS calls_target, ' .
					'sum(effectives) AS effectives_target, ' .
					'sum(meetings_set) AS meetings_set_target, ' .
					'avg(meetings_set) AS ave_meetings_set_target, ' .
					'sum(meetings_attended) AS meetings_attended_target ' .
					'from ' .
					'tbl_campaign_targets ct ' .
					'JOIN tbl_campaigns c on ct.campaign_id = c.id ' .
					'WHERE `year_month` >= EXTRACT(YEAR_MONTH FROM ' . self::$DB->quote($start, 'timestamp') . ') ' .
					'AND `year_month` <= EXTRACT(YEAR_MONTH FROM ' . self::$DB->quote($end, 'timestamp') . ') ' .
					'AND client_id = ' . self::$DB->quote($client_id, 'integer');

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7DatabaseAnalysisProspect($start, $end, $client_id)
	{
		//$debug = true;
		$query = 'select ' .
					'sum(call_count) AS call_count, ' .
					'sum(call_effective_count) AS call_effective_count, ' .
					'(sum(call_effective_count)/sum(call_count) *100) AS access_rate, ' .
					'sum(call_ote_count) AS call_ote_count, ' .
					'sum(meeting_set_count) AS meeting_set_count, ' .
					'(sum(meeting_set_count)/sum(call_effective_count) *100) AS conversion_rate, ' .
					'sum(meeting_category_unknown_count) AS meeting_category_unknown_count, ' .
					'sum(meeting_attended_count) AS meeting_attended_count, ' .
					'sum(meeting_category_attended_count) AS meeting_category_attended_count, ' .
					'sum(meeting_category_cancelled_count) AS meeting_category_cancelled_count, ' .
					'sum(meeting_category_tbr_count) AS meeting_category_tbr_count ' .
					'from ' .
					'tbl_data_statistics_daily ds ' .
					'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
					'WHERE date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer');

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7MeetingsTargetsByNbm($start, $end, $client_id, $user_id)
	{
//		$debug = true;
		$query = 'select ' .
					'sum(effectives) AS effectives_target, ' .
					'sum(meetings_set) AS meetings_set_target, ' .
					'sum(meetings_attended) AS meetings_attended_target ' .
					'from ' .
					'tbl_campaign_nbm_targets ct ' .
					'JOIN tbl_campaigns c on ct.campaign_id = c.id ' .
					'WHERE `year_month` >= EXTRACT(YEAR_MONTH FROM ' . self::$DB->quote($start, 'timestamp') . ') ' .
					'AND `year_month` <= EXTRACT(YEAR_MONTH FROM ' . self::$DB->quote($end, 'timestamp') . ') ' .
					'AND client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'AND user_id = ' . self::$DB->quote($user_id, 'integer');

		if ($debug) echo "<p>$query;</p>";
//		exit();
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		return $results;
	}

	public function getReport7DatabaseAnalysisProspectByNbm($start, $end, $client_id)
	{
//		$debug = true;



//      'SELECT i.campaign_id, m_sh.created_by, m_sh.date, COUNT(m_sh.shadow_id) ' .
//      'FROM tbl_meetings_shadow_temp AS m_sh ' .
//      'JOIN tbl_data_statistics_daily_temp_1 ds_1 ON m_sh.shadow_id = ds_1.value ' .
//      'JOIN tbl_post_initiatives AS pi ON pi.id = m_sh.post_initiative_id ' .
//      'JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
//      'WHERE m_sh.status_id in (20,21,22,23) ' .
//      'GROUP BY i.campaign_id, m_sh.created_by, m_sh.date;';
//echo "<p>$sql</p>";
//$db->query($sql);

		$query = 'select ' .
					'u.id as user_id, ' .
					'u.name AS nbm, ' .
					'sum(call_count) AS call_count, ' .
					'sum(call_effective_count) AS call_effective_count, ' .
					'(sum(call_effective_count)/sum(call_count) *100) AS access_rate, ' .
					'sum(call_ote_count) AS call_ote_count, ' .
					'sum(meeting_set_count) AS meeting_set_count, ' .
					'(sum(meeting_set_count)/sum(call_effective_count) *100) AS conversion_rate, ' .
					'sum(meeting_category_unknown_count) AS meeting_category_unknown_count, ' .
					'sum(meeting_attended_count) AS meeting_attended_count, ' .
					'sum(meeting_category_attended_count) AS meeting_category_attended_count, ' .
					'sum(meeting_category_cancelled_count) AS meeting_category_cancelled_count ' .
					'from ' .
					'tbl_data_statistics_daily ds ' .
					'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
					'join tbl_rbac_users u on ds.user_id = u.id ' .
		            'join tmp_NbmsWithCallsInPeriod t on t.user_id = ds.user_id ' .
					'WHERE date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'GROUP BY ds.user_id';


		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7DatabaseAnalysisProspectByNbmByMonth($start, $end, $client_id)
	{

		// NOTE: This function assumes that the function getReport7DatabaseAnalysisProspectByNbm has already been run since
		// the function getReport7DatabaseAnalysisProspectByNbm creates a temporary table which restricts NBMs shown on this
		// report to those who have made at least one call

//		$debug = true;

//		 $query = 'create temporary table tmp_NbmsWithCallsInPeriod ' .
//        'SELECT ds.user_id, sum(call_count) as calls ' .
//        'from ' .
//        'tbl_data_statistics_daily ds ' .
//        'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
//        'join tbl_rbac_users u on ds.user_id = u.id ' .
//        'WHERE date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
//        'AND date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
//        'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
//        'GROUP BY ds.user_id ' .
//        'HAVING calls > 0';
//
//        $values = array();
//        $stmt = self::$DB->prepare($query);
//        $this->doStatement($stmt, $values);


		$query = 'select ' .
					'u.id as nbm_id, ' .
					'u.name AS nbm, ' .
					'extract(year_month FROM ds.`date`) as `year_month`, ' .
					'extract(year FROM ds.`date`) as `year`, ' .
					'date_format(ds.`date`, \'%M\') as `month`, ' .
					'sum(call_count) AS call_count, ' .
					'sum(call_effective_count) AS call_effective_count, ' .
					'sum(call_count) - sum(call_effective_count) AS call_non_effective_count, ' .
					'(sum(call_effective_count)/sum(call_count) *100) AS access_rate, ' .
					'sum(call_ote_count) AS call_ote_count, ' .
					'sum(call_effective_count) - sum(call_ote_count) AS call_ofte_count, ' .
					'sum(meeting_set_count) AS meeting_set_count, ' .
					'(sum(meeting_set_count)/sum(call_effective_count) *100) AS conversion_rate, ' .
					'sum(meeting_category_unknown_count) AS meeting_category_unknown_count, ' .
					'sum(meeting_attended_count) AS meeting_attended_count, ' .
					'sum(meeting_category_cancelled_count) AS meeting_category_cancelled_count ' .
					'from ' .
					'tbl_data_statistics_daily ds ' .
					'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
					'join tbl_rbac_users u on ds.user_id = u.id ' .
		            'join tmp_NbmsWithCallsInPeriod t on t.user_id = ds.user_id ' .
					'WHERE date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'GROUP BY ds.user_id, extract(year_month FROM ds.date)';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $results;
	}

	public function getReport7MeetingsSetSummaryNewMeetings($start, $end, $client_id)
	{
//		$debug = true;
//		$query = 'call sp_report_5_3b_4 (' . self::$DB->quote($start, 'timestamp') . ', ' .
//					self::$DB->quote($end, 'timestamp') . ', ' .
//					self::$DB->quote($client_id, 'integer') . ')';

		$query = "SELECT CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category, " .
				'EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`, ' .
				'lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment '.
				'from tbl_meetings m ' .
				'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
				'join tbl_initiatives i on pi.initiative_id = i.id ' .
				'join tbl_campaigns c on c.id = i.campaign_id ' .
				'join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
				'join vw_posts_contacts p on pi.post_id = p.id ' .
				'join tbl_companies co on co.id = p.company_id ' .
				'where m.created_at >= ' . self::$DB->quote($start, 'timestamp') .' ' .
				'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') .' ' .
				'AND c.client_id = ' . self::$DB->quote($client_id, 'integer');


		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		return $results;
	}



	public function getReport7MeetingsSetSummaryRearrangedMeetings($start, $end, $client_id)
	{
//		$debug = true;
	$values = array();

	$query = 'drop table if exists t1';
	if ($debug) echo "<p>$query;</p>";
	$stmt = self::$DB->prepare($query);
	$this->doStatement($stmt, $values);

	$query = 'create temporary table t1 ' .
				'select ms.id ' .
				'from tbl_meetings_shadow ms ' .
				'join tbl_post_initiatives pi on pi.id = ms.post_initiative_id ' .
				'join tbl_initiatives i on pi.initiative_id = i.id ' .
				'join tbl_campaigns c on c.id = i.campaign_id ' .
				'where ms.created_at < ' . self::$DB->quote($start, 'timestamp') .' ' .
				'and ms.shadow_timestamp >= ' . self::$DB->quote($start, 'timestamp') .' ' .
				'and ms.shadow_timestamp <= ' . self::$DB->quote($end, 'timestamp') .' ' .
				'AND c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
				'AND ms.status_id IN (18, 19) ' .
				'GROUP BY ms.id ';
	if ($debug) echo "<p>$query;</p>";
	$stmt = self::$DB->prepare($query);
	$this->doStatement($stmt, $values);

	$query = "SELECT CAST(CONCAT('Set: ', DATE_FORMAT(m.created_at, '%b'), ' ', YEAR(m.created_at)) AS CHAR(255)) AS category, " .
				'EXTRACT(YEAR_MONTH FROM m.created_at) AS `year_month`, ' .
				'lkp_cs.description, co.name, p.job_title, p.full_name, m.date, m.created_at, pi.comment '.
				'from t1 ' .
				'join tbl_meetings m on t1.id = m.id ' .
				'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
				'join tbl_initiatives i on pi.initiative_id = i.id ' .
				'join tbl_campaigns c on c.id = i.campaign_id ' .
				'join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
				'join vw_posts_contacts p on pi.post_id = p.id ' .
				'join tbl_companies co on co.id = p.company_id ' .
				'ORDER BY category DESC, `year_month`, date';



//		$query = 'call sp_report_7_rearranged_meetings (' . self::$DB->quote($start, 'timestamp') . ', ' .
//					self::$DB->quote($end, 'timestamp') . ', ' .
//					self::$DB->quote($client_id, 'integer') . ')';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		$query = 'drop table if exists t1';

	$stmt = self::$DB->prepare($query);
	$this->doStatement($stmt, $values);

		return $results;
	}

	public function getReport7CampaignCancellationsMeetingLeadTimes($start, $end, $client_id)
	{
		//$debug = true;

		$query = 'SELECT avg(DATEDIFF(m.date, m.created_at)) as day_count, count(m.id) as meeting_count ' .
					'from tbl_meetings m ' .
					'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
//					'where m.created_at >= ' . self::$DB->quote($start, 'timestamp') .' ' .
//					'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') .' ' .
					'WHERE c.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'UNION ' .
					'SELECT avg(DATEDIFF(m.date, m.created_at)) as day_count, count(m.id) as meeting_count ' .
					'from tbl_meetings m ' .
					'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
//					'where m.created_at >= ' . self::$DB->quote($start, 'timestamp') .' ' .
//					'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') .' ' .
					'where c.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'AND m.status_id in (14,15,16,17,20,21,22,23)';
		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		return $results;
	}

	public function getReport7PeriodCancellationsMeetingLeadTimes($start, $end, $client_id)
	{
//		$debug = true;

//	makeTemporaryTable_1($db);
//$sql = 'INSERT INTO tbl_data_statistics_daily_temp_1 (`value`) ' .
//		'SELECT MAX(m_sh.shadow_id) ' .
//		'FROM tbl_meetings_shadow_temp AS m_sh ' .
//		'WHERE m_sh.date >= \'' . $start_date . '\' ' .
//		'AND m_sh.date <= \'' . $end_date . '\' ' .
//		'AND m_sh.shadow_type = \'u\' ' .
//		'GROUP BY m_sh.id;';
//echo "<p>$sql</p>";
//$db->query($sql);
//
//makeTemporaryTable($db);
//$sql = 'INSERT INTO tbl_data_statistics_daily_temp (campaign_id, user_id, `date`, `value`) ' .
//		'SELECT i.campaign_id, m_sh.created_by, m_sh.date, COUNT(m_sh.shadow_id) ' .
//		'FROM tbl_meetings_shadow_temp AS m_sh ' .
//		'JOIN tbl_data_statistics_daily_temp_1 ds_1 ON m_sh.shadow_id = ds_1.value ' .
//		'JOIN tbl_post_initiatives AS pi ON pi.id = m_sh.post_initiative_id ' .
//		'JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
//		'WHERE m_sh.status_id in (20,21,22,23) ' .
//		'GROUP BY i.campaign_id, m_sh.created_by, m_sh.date;';
//echo "<p>$sql</p>";
//$db->query($sql);

		$query = 'SELECT avg(DATEDIFF(m.date, m.created_at)) as day_count, count(m.id) as meeting_count ' .
					'from tbl_meetings m ' .
					'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
					'where m.created_at >= ' . self::$DB->quote($start, 'timestamp') .' ' .
					'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') .' ' .
					'AND c.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'UNION ' .
					'SELECT avg(DATEDIFF(m.date, m.created_at)) as day_count, count(m.id) as meeting_count ' .
					'from tbl_meetings m ' .
					'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
					'where m.created_at >= ' . self::$DB->quote($start, 'timestamp') .' ' .
					'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') .' ' .
					'AND c.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'AND m.status_id in (14,15,16,17,20,21,22,23)';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		return $results;
	}

	public function getReport7CancellationsMeetingLeadTimesByCompany($start, $end, $client_id)
	{
//		$debug = true;

		$query = 'SELECT DATEDIFF(m.date, m.created_at) as day_count, co.name as company_name, ' .
					'lkp_cs.description as meeting_status ' .
					'from tbl_meetings m ' .
					'join tbl_post_initiatives pi on pi.id = m.post_initiative_id ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
					'join tbl_lkp_communication_status lkp_cs on lkp_cs.id = m.status_id ' .
					'join tbl_posts p on pi.post_id = p.id ' .
					'join tbl_companies co on co.id = p.company_id ' .
					'where m.created_at >= ' . self::$DB->quote($start, 'timestamp') .' ' .
					'AND m.created_at <= ' . self::$DB->quote($end, 'timestamp') .' ' .
					'AND c.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'AND m.status_id in (14,15,16,17,20,21,22,23)';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		return $results;
	}

	public function getReport7DatabaseAnalysis($start, $end, $client_id)
	{
//		$debug = true;
		$values = array();


		$query = 'create temporary table t ' .
					'select pi.id, lkp_cs.description, pi.status_id, next_communication_date, ' .
					'if (next_communication_date >= current_date, 1, 0) as future_callback, ' .
					'if (next_communication_date = null, 0, if(next_communication_date < current_date, 1,0)) as past_callback ' .
					'from tbl_post_initiatives pi ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
					'join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
					'left join tbl_posts p on pi.post_id = p.id ' .
					'left join tbl_companies co on co.id = p.company_id ' .
					'where c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
					'and p.deleted = false ' .
					'and co.deleted = false';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'select description as status, ' .
					'count(id) as total, ' .
					'sum(future_callback) as callback_future, ' .
					'sum(past_callback) as callback_past ' .
					'from t ' .
					'group by status_id ' .
					'order by status_id desc';

			if ($debug) echo "<p>$query;</p>";
			$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

			$query = 'drop table if exists t';

		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		return $results;
	}

	public function getReport7DatabaseAnalysisByCompany($start, $end, $client_id)
	{
//		$debug = true;
		$values = array();

//		$query = 'drop table if exists t';
//		if ($debug) echo "<p>$query;</p>";
//		$stmt = self::$DB->prepare($query);
//		$this->doStatement($stmt, $values);

		$query = 'create temporary table t ' .
					'select pi.id, co.id as company_id, lkp_cs.description, pi.status_id ' .
					'from tbl_post_initiatives pi ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
					'join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
					'left join tbl_posts p on pi.post_id = p.id ' .
					'left join tbl_companies co on co.id = p.company_id ' .
					'where c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
					'and p.deleted = false ' .
					'and co.deleted = false';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'create temporary table t1 ' .
					'select description as status, status_id, count(distinct company_id)  as company_count ' .
					'from t ' .
					'group by status_id';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'select status, sum(company_count) as company_count '.
					'from t1 ' .
					'group by status, status_id ' .
					'order by status_id desc';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		$query = 'drop table if exists t';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'drop table if exists t1';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		return $results;
	}

	public function getReport7DatabaseAnalysisProspectsNotYetAttempted($start, $end, $client_id)
	{
//		$debug = true;

		$query = 'select count(pi.id) ' .
			'from tbl_post_initiatives pi ' .
			'join tbl_initiatives i on pi.initiative_id = i.id ' .
			'join tbl_campaigns c on c.id = i.campaign_id ' .
			'left join tbl_communications com on com.post_initiative_id = pi.id ' .
			'left join tbl_posts p on pi.post_id = p.id ' .
			'left join tbl_companies co on co.id = p.company_id ' .
			'where c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
			'and p.deleted = false ' .
			'and co.deleted = false ' .
			'and com.id is null';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryOne($query);

		return $results;
	}

	public function getReport7DatabaseAnalysisCompaniesNotYetAttempted($start, $end, $client_id)
	{
//		$debug = true;
		$values = array();

		$query = 'create temporary table t ' .
				'select count(pi.id) as id ' .
				'from tbl_post_initiatives pi ' .
				'join tbl_initiatives i on pi.initiative_id = i.id ' .
				'join tbl_campaigns c on c.id = i.campaign_id ' .
				'left join tbl_communications com on com.post_initiative_id = pi.id ' .
				'left join tbl_posts p on pi.post_id = p.id ' .
				'left join tbl_companies co on co.id = p.company_id ' .
				'where c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
				'and p.deleted = false ' .
				'and co.deleted = false ' .
				'and com.id is null ' .
				'group by co.id';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'select count(id) ' .
				'from t';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryOne($query);

		$query = 'drop table if exists t';

		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		return $results;
	}

	public function getReport7LeadNBMEffectiveAnalysis($start, $end, $client_id)
	{
//		$debug = true;
		$values = array();

		$query = 'create temporary table t ' .
					'select com.id as communication_id, pi.id as prospect_id, ' .
					' lkp_cs.description, pi.status_id, co.id as company_id ' .
					'from tbl_communications com ' .
					'join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
					'join tbl_initiatives i on pi.initiative_id = i.id ' .
					'join tbl_campaigns c on c.id = i.campaign_id ' .
					'join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
					'left join tbl_posts p on pi.post_id = p.id ' .
					'left join tbl_companies co on co.id = p.company_id ' .
					'where com.communication_date >= ' . self::$DB->quote($start, 'timestamp') .' ' .
					'AND com.communication_date <= ' . self::$DB->quote($end, 'timestamp') .' ' .
					'and c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
					'and p.deleted = false ' .
					'and co.deleted = false ' .
					'and com.is_effective = true';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'create temporary table t1 ' .
					'select description as status, status_id, ' .
					'count(distinct prospect_id) as prospect_total, ' .
					'count(communication_id) as effective_total, ' .
					'count(distinct company_id)  as company_total ' .
					'from t ' .
					'group by status_id';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'select status, sum(prospect_total) as total_prospects, ' .
					'sum(effective_total) as total_effectives, ' .
					'sum(company_total) total_companies, ' .
					'sum(effective_total)/sum(prospect_total) as average_effectives_per_prospect ' .
					'from t1 ' .
					'group by status, status_id ' .
					'order by status_id desc';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		$query = 'drop table if exists t';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'drop table if exists t1';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		return $results;
	}

	public function getReport7LeadNBMDisciplineAnalysis($start, $end, $client_id)
	{
//		$debug = true;
		$query = 'select user_id ' .
				'from tbl_campaign_nbms cnbm ' .
				'join tbl_campaigns c on c.id = cnbm.campaign_id ' .
				'and c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
				'and cnbm.is_lead_nbm = true ' .
				'and deactivated_date = \'0000-00-00\'';


		$results = self::$DB->queryOne($query);
		if ($debug) print_r($results);


		$query = 'select u.id as user_id, ' .
				'u.name AS nbm, ' .
				'sum(call_count) AS calls, ' .
				'sum(call_effective_count) AS effectives, ' .
				'(sum(call_effective_count)/sum(call_count) *100) AS access, ' .
				'sum(meeting_set_count) AS meets_set, ' .
				'(sum(meeting_set_count)/sum(call_effective_count) *100) AS conversion, ' .
				'tc.value as discipline ' .
				'from tbl_data_statistics_daily ds ' .
				'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
				'join tbl_rbac_users u on ds.user_id = u.id ' .
				'join tbl_campaign_disciplines cd on cd.campaign_id = cam.id ' .
				'join tbl_tiered_characteristics tc on tc.id = cd.tiered_characteristic_id ' .
				'WHERE ds.user_id = ' . $results . ' ' .
				'GROUP BY cd.tiered_characteristic_id';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		return $results;
	}

	public function getReport7LeadNBMSectorAnalysis($start, $end, $client_id)
	{
//		$debug = true;
		$query = 'select user_id ' .
				'from tbl_campaign_nbms cnbm ' .
				'join tbl_campaigns c on c.id = cnbm.campaign_id ' .
				'and c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
				'and cnbm.is_lead_nbm = true ' .
				'and deactivated_date = \'0000-00-00\'';


		$results = self::$DB->queryOne($query);
		if ($debug) print_r($results);

		$values = array();

		$query = 'create temporary table t (' .
				'communication_id int(11), ' .
				'is_effective tinyint, ' .
				'`value` varchar(100), ' .
				'category_id int(11), ' .
				'parent_id int(11), ' .
				'KEY `communication_id` (communication_id), ' .
				'KEY `value` (`value`), ' .
				'KEY `category_id` (`category_id`), ' .
				'KEY `parent_id` (`parent_id`) ' .
				');';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'insert into t ' .
				'select ' .
				'com.id as communication_id, ' .
				'com.is_effective, ' .
				'tc.value, ' .
				'tc.category_id, ' .
				'tc.parent_id ' .
				'from tbl_communications com ' .
				'join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
				'join tbl_initiatives i on pi.initiative_id = i.id ' .
				'join tbl_campaigns cam on i.campaign_id = cam.id ' .
				'join tbl_posts p on p.id = pi.post_id ' .
				'left join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id ' .
				'left join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id ' .
				'WHERE com.user_id = ' . $results;


		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'create temporary table t1 ( ' .
				'meeting_id int(11), ' .
				'communication_id int(11), ' .
				'KEY `meeting_id` (`meeting_id`), ' .
				'KEY `communication_id` (`communication_id`) ' .
				')';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'insert into t1 ' .
				'select m.id as meeting_id, m.communication_id ' .
				'from tbl_meetings m  ' .
				'WHERE created_by = ' . $results;

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);


		$query = 'select t.value as sector, ' .
				'count(t.communication_id) as calls, ' .
				'sum(t.is_effective) as effectives, ' .
				'count(t1.meeting_id) as meets_set, ' .
				'sum(t.is_effective)/count(t.communication_id)*100 as access, ' .
				'count(t1.meeting_id)/sum(t.is_effective)*100 as conversion ' .
				'from t ' .
				'left join t1 on t1.communication_id = t.communication_id ' .
				'where t.category_id = 1 ' .
				'and t.parent_id = 0 ' .
				'group by t.value;';

		if ($debug) echo "<p>$query;</p>";
		$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

		$query = 'drop table if exists t';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'drop table if exists t1';
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		return $results;
	}

	public function getReport7Pipeline($start, $end, $client_id)
	{
//		$debug = true;

		self::$DB->prepare($query, $types);

		$values = array();
		$query = 'create temporary table t ' .
				'select pi.id, ' .
				'lkp_cs.description, ' .
				'pi.status_id, ' .
				'next_communication_date, ' .
				'extract(year_month from next_communication_date) as `year_month`, ' .
				"concat(extract(year_month from next_communication_date), '01') " .
				'from tbl_post_initiatives pi ' .
				'join tbl_initiatives i on pi.initiative_id = i.id ' .
				'join tbl_campaigns c on c.id = i.campaign_id ' .
				'join tbl_lkp_communication_status lkp_cs on lkp_cs.id = pi.status_id ' .
				'left join tbl_posts p on pi.post_id = p.id ' .
				'left join tbl_companies co on co.id = p.company_id ' .
				'where c.client_id = ' . self::$DB->quote($client_id, 'integer') .' ' .
				'and pi.next_communication_date  is not NULL ' .
				"and concat(extract(year_month from next_communication_date), '01') >= concat(extract(year_month from date_add(current_date(), interval 1 MONTH)), '01') " .
				'and p.deleted = false ' .
				'and co.deleted = false';

		if ($debug) echo "<p>$query;</p>";
		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		$query = 'select description as status, ' .
				'count(id) as total, ' .
				'`year_month` ' .
				'from t ' .
				'group by `year_month`, status_id ' .
				'order by `year_month`, status_id desc';

			if ($debug) echo "<p>$query;</p>";
			$results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

			$query = 'drop table if exists t';

		$stmt = self::$DB->prepare($query);
		$this->doStatement($stmt, $values);

		return $results;
	}

	///////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////////

//	REPORT 8

	///////////////////////////////////////////////////////////

	public function getReport8KeyToTermsAll($start, $end, $client_id, $filter_id)
	{
		$values = array(self::$DB->quote($start, 'timestamp'),
					self::$DB->quote($end, 'timestamp'),
					self::$DB->quote($client_id, 'integer'),
					self::$DB->quote($filter_id, 'integer'));

		self::$DB->loadModule('Function');
		$result = self::$DB->function->executeStoredProc('sp_report_8_Key_To_Terms_All', $values);

		$results = $result->fetchAll(MDB2_FETCHMODE_ASSOC);

		$result->nextResult();

		return $results;
	}

	public function getReport8KeyToTermsOnlyCommunications($start, $end, $client_id, $filter_id)
	{
		$values = array(self::$DB->quote($start, 'timestamp'),
					self::$DB->quote($end, 'timestamp'),
					self::$DB->quote($client_id, 'integer'),
					self::$DB->quote($filter_id, 'integer'));

		self::$DB->loadModule('Function');
		$result = self::$DB->function->executeStoredProc('sp_report_8_Key_To_Terms_Only_Communications', $values);

		$results = $result->fetchAll(MDB2_FETCHMODE_ASSOC);

		$result->nextResult();

		return $results;
	}

	public function getReport8CampaignSummary($start, $end, $client_id)
	{
		$values = array(self::$DB->quote($start, 'timestamp'),
					self::$DB->quote($end, 'timestamp'),
					self::$DB->quote($client_id, 'integer'));

		self::$DB->loadModule('Function');
		$result = self::$DB->function->executeStoredProc('sp_report_8_Summary_Figures_For_Campaign', $values);
		$results = $result->fetchAll(MDB2_FETCHMODE_ASSOC);

		$result->nextResult();

//		echo '<br />----------mapper results <br />';
//
//		echo '<pre>' . print_r($results) . '</pre><br />---------------<br />';

		return $results;
	}

	public function getReport8PeriodSummary($start, $end, $client_id)
	{
		$values = array(self::$DB->quote($start, 'timestamp'),
					self::$DB->quote($end, 'timestamp'),
					self::$DB->quote($client_id, 'integer'));

		self::$DB->loadModule('Function');
		$result = self::$DB->function->executeStoredProc('sp_report_8_Summary_Figures_For_Period', $values);
		$results = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
		$result->nextResult();

		return $results;
	}

	public function getReport8SectorPenetrationSummary($start, $end, $client_id)
	{
		$values = array(self::$DB->quote($start, 'timestamp'),
					self::$DB->quote($end, 'timestamp'),
					self::$DB->quote($client_id, 'integer'));

		self::$DB->loadModule('Function');
		$result = self::$DB->function->executeStoredProc('sp_report_8_sector_penetration', $values);
		$results = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
		$result->nextResult();

		return $results;
	}

	public function getReport8PeriodResults($start, $end, $client_id, $filter_id)
	{
		$values = array(self::$DB->quote($start, 'timestamp'),
					self::$DB->quote($end, 'timestamp'),
					self::$DB->quote($client_id, 'integer'),
					self::$DB->quote($filter_id, 'integer'));

		self::$DB->loadModule('Function');
		$result = self::$DB->function->executeStoredProc('sp_report_8_period_results_calls', $values);
		$results = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
		$result->nextResult();

		return $results;
	}

	///////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////

//  REPORT 10 - Global Industry sector analysis

    ///////////////////////////////////////////////////////////


   public function getReport10GlobalSectorAnalysis($start, $end)
    {
//      $debug = true;
        $values = array();

        $query = 'create temporary table t (' .
                'communication_id int(11), ' .
                'is_effective tinyint, ' .
                '`value` varchar(100), ' .
                'category_id int(11), ' .
                'parent_id int(11), ' .
                'KEY `communication_id` (communication_id), ' .
                'KEY `value` (`value`), ' .
                'KEY `category_id` (`category_id`), ' .
                'KEY `parent_id` (`parent_id`) ' .
                ');';

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'insert into t ' .
                'select ' .
                'com.id as communication_id, ' .
                'com.is_effective, ' .
                'tc.value, ' .
                'tc.category_id, ' .
                'tc.parent_id ' .
                'from tbl_communications com ' .
                'join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
                'join tbl_initiatives i on pi.initiative_id = i.id ' .
                'join tbl_campaigns cam on i.campaign_id = cam.id ' .
                'join tbl_posts p on p.id = pi.post_id ' .
                'left join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id ' .
                'left join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id ' .
                'where com.communication_date >= ' . self::$DB->quote($start, 'timestamp') .' ' .
                'AND com.communication_date <= ' . self::$DB->quote($end, 'timestamp');


        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'create temporary table t1 ( ' .
                'meeting_id int(11), ' .
                'communication_id int(11), ' .
                'KEY `meeting_id` (`meeting_id`), ' .
                'KEY `communication_id` (`communication_id`) ' .
                ')';

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'insert into t1 ' .
                'select m.id as meeting_id, m.communication_id ' .
                'from tbl_meetings m';
                'where m.date >= ' . self::$DB->quote($start, 'timestamp') .' ' .
                'AND m.date <= ' . self::$DB->quote($end, 'timestamp');
//                'WHERE created_by = ' . $results;

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);


        $query = 'select t.value as sector, ' .
                'count(t.communication_id) as calls, ' .
                'sum(t.is_effective) as effectives, ' .
                'count(t1.meeting_id) as meets_set, ' .
                'sum(t.is_effective)/count(t.communication_id)*100 as access, ' .
                'count(t1.meeting_id)/sum(t.is_effective)*100 as conversion ' .
                'from t ' .
                'left join t1 on t1.communication_id = t.communication_id ' .
                'where t.category_id = 1 ' .
                'and t.parent_id = 0 ' .
                'group by t.value;';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        $query = 'drop table if exists t';
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'drop table if exists t1';
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        return $results;
    }


    public function getReport10NbmSectorAnalysis($start, $end)
    {
//      $debug = true;
        $values = array();

        $query = 'create temporary table t (' .
                'communication_id int(11), ' .
                'is_effective tinyint, ' .
                'nbm varchar(100), ' .
                '`value` varchar(100), ' .
                'category_id int(11), ' .
                'parent_id int(11), ' .
                'KEY `communication_id` (communication_id), ' .
                'KEY `value` (`value`), ' .
                'KEY `category_id` (`category_id`), ' .
                'KEY `parent_id` (`parent_id`) ' .
                ');';

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'insert into t ' .
                'select ' .
                'com.id as communication_id, ' .
                'com.is_effective, ' .
                'u.name as nbm, ' .
                'tc.value, ' .
                'tc.category_id, ' .
                'tc.parent_id ' .
                'from tbl_communications com ' .
                'join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
                'join tbl_initiatives i on pi.initiative_id = i.id ' .
                'join tbl_campaigns cam on i.campaign_id = cam.id ' .
                'join tbl_posts p on p.id = pi.post_id ' .
                'join tbl_rbac_users u on u.id = com.user_id ' .
                'left join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id ' .
                'left join tbl_tiered_characteristics tc on otc.tiered_characteristic_id = tc.id ' .
                'where com.communication_date >= ' . self::$DB->quote($start, 'timestamp') .' ' .
                'AND com.communication_date <= ' . self::$DB->quote($end, 'timestamp');


        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'create temporary table t1 ( ' .
                'meeting_id int(11), ' .
                'communication_id int(11), ' .
                'KEY `meeting_id` (`meeting_id`), ' .
                'KEY `communication_id` (`communication_id`) ' .
                ')';

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'insert into t1 ' .
                'select m.id as meeting_id, m.communication_id ' .
                'from tbl_meetings m';
                'where m.date >= ' . self::$DB->quote($start, 'timestamp') .' ' .
                'AND m.date <= ' . self::$DB->quote($end, 'timestamp');
//                'WHERE created_by = ' . $results;

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);


        $query = 'select t.nbm, t.value as sector, ' .
                'count(t.communication_id) as calls, ' .
                'sum(t.is_effective) as effectives, ' .
                'count(t1.meeting_id) as meets_set, ' .
                'sum(t.is_effective)/count(t.communication_id)*100 as access, ' .
                'count(t1.meeting_id)/sum(t.is_effective)*100 as conversion ' .
                'from t ' .
                'left join t1 on t1.communication_id = t.communication_id ' .
                'where t.category_id = 1 ' .
                'and t.parent_id = 0 ' .
                'group by t.nbm, t.value;';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        $query = 'drop table if exists t';
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'drop table if exists t1';
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        return $results;
    }
        ///////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////

//  REPORT 11 - Global discipline analysis

    ///////////////////////////////////////////////////////////


   public function getReport11GlobalDisciplineAnalysis($start, $end)
    {
//        $debug = true;

        $query = 'select u.id as user_id, ' .
                'u.name AS nbm, ' .
                'sum(call_count) AS calls, ' .
                'sum(call_effective_count) AS effectives, ' .
                '(sum(call_effective_count)/sum(call_count) *100) AS access, ' .
                'sum(meeting_set_count) AS meets_set, ' .
                '(sum(meeting_set_count)/sum(call_effective_count) *100) AS conversion, ' .
                'tc.value as discipline ' .
                'from tbl_data_statistics_daily ds ' .
                'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
                'join tbl_rbac_users u on ds.user_id = u.id ' .
                'join tbl_campaign_disciplines cd on cd.campaign_id = cam.id ' .
                'join tbl_tiered_characteristics tc on tc.id = cd.tiered_characteristic_id ' .
                'WHERE date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
                'AND date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
                'GROUP BY cd.tiered_characteristic_id';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }


    public function getReport11NbmDisciplineAnalysis($start, $end)
    {
//      $debug = true;
        $query = 'select u.id as user_id, ' .
                'u.name AS nbm, ' .
                'sum(call_count) AS calls, ' .
                'sum(call_effective_count) AS effectives, ' .
                '(sum(call_effective_count)/sum(call_count) *100) AS access, ' .
                'sum(meeting_set_count) AS meets_set, ' .
                '(sum(meeting_set_count)/sum(call_effective_count) *100) AS conversion, ' .
                'tc.value as discipline ' .
                'from tbl_data_statistics_daily ds ' .
                'join tbl_campaigns cam on ds.campaign_id = cam.id ' .
                'join tbl_rbac_users u on ds.user_id = u.id ' .
                'join tbl_campaign_disciplines cd on cd.campaign_id = cam.id ' .
                'join tbl_tiered_characteristics tc on tc.id = cd.tiered_characteristic_id ' .
                'WHERE date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
                'AND date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
                'and u.is_active = true ' .
                'GROUP BY u.name, cd.tiered_characteristic_id';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;


    }
        ///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////

//  REPORT 12 - Global sector discipline analysis

    ///////////////////////////////////////////////////////////


   public function getReport12GlobalSectorDisciplineAnalysis($start, $end)
    {
//        $debug = true;

    	$values = array();

        $query = 'create temporary table t (' .
                'communication_id int(11), ' .
                'is_effective tinyint, ' .
                '`value` varchar(100), ' .
                'category_id int(11), ' .
                'parent_id int(11), ' .
                '`value_1` varchar(100), ' .
                'category_id_1 int(11), ' .
                'parent_id_1 int(11), ' .
                'KEY `communication_id` (communication_id), ' .
                'KEY `value` (`value`), ' .
                'KEY `category_id` (`category_id`), ' .
                'KEY `parent_id` (`parent_id`) ' .
                ');';

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'insert into t ' .
                'select ' .
                'com.id as communication_id, ' .
                'com.is_effective, ' .
                'tc.value, ' .
                'tc.category_id, ' .
                'tc.parent_id, ' .
                'tc1.value as value_1, ' .
                'tc1.category_id as category_id_1, ' .
                'tc1.parent_id as parent_id_1 ' .
                'from tbl_communications com ' .
                'join tbl_post_initiatives pi on com.post_initiative_id = pi.id ' .
                'join tbl_initiatives i on pi.initiative_id = i.id ' .
                'join tbl_campaigns cam on i.campaign_id = cam.id ' .
                'join tbl_posts p on p.id = pi.post_id ' .
                'join tbl_campaign_disciplines cd on cd.campaign_id = cam.id ' .
                'join tbl_tiered_characteristics tc on tc.id = cd.tiered_characteristic_id ' .
                'left join tbl_object_tiered_characteristics otc on p.company_id = otc.company_id ' .
                'left join tbl_tiered_characteristics tc1 on otc.tiered_characteristic_id = tc1.id ' .
                'where com.communication_date >= ' . self::$DB->quote($start, 'timestamp') .' ' .
                'AND com.communication_date <= ' . self::$DB->quote($end, 'timestamp');


        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'create temporary table t1 ( ' .
                'meeting_id int(11), ' .
                'communication_id int(11), ' .
                'KEY `meeting_id` (`meeting_id`), ' .
                'KEY `communication_id` (`communication_id`) ' .
                ')';

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'insert into t1 ' .
                'select m.id as meeting_id, m.communication_id ' .
                'from tbl_meetings m';
                'where m.date >= ' . self::$DB->quote($start, 'timestamp') .' ' .
                'AND m.date <= ' . self::$DB->quote($end, 'timestamp');

        if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);


        $query = 'select t.value_1 as sector, ' .
                't.value as discipline, ' .
                'count(t.communication_id) as calls, ' .
                'sum(t.is_effective) as effectives, ' .
                'count(t1.meeting_id) as meets_set, ' .
                'sum(t.is_effective)/count(t.communication_id)*100 as access, ' .
                'count(t1.meeting_id)/sum(t.is_effective)*100 as conversion ' .
                'from t ' .
                'left join t1 on t1.communication_id = t.communication_id ' .
                'where t.category_id_1 = 1 ' .
                'and t.parent_id_1 = 0 ' .
                'group by t.value_1, t.value';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

         $query = 'drop table if exists t';
           if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        $query = 'drop table if exists t1';
          if ($debug) echo "<p>$query;</p>";
        $stmt = self::$DB->prepare($query);
        $this->doStatement($stmt, $values);

        return $results;
    }



        ///////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////

//  REPORT 13 - Gloabl NBM Bonus report

    ///////////////////////////////////////////////////////////


   public function getReport13QuarterSummary($year, $nbm_exclusions, $client_id = null)
    {
//        $debug = true;

        $query = 'select period_year, period_quarter, u.name as nbm, ' .
                 'sum(meeting_category_attended_count) as attended, ' .
                 'sum(meeting_category_tbr_count) as tbr, ' .
                'sum(meeting_category_cancelled_count) as cancelled, ' .
                'sum(meeting_category_unknown_count) as unknown, ' .
                '(sum(meeting_in_diary_this_month_count) - sum(meeting_category_unknown_count)) as diary ' .
                'from tbl_data_statistics ds ' .
                'join tbl_rbac_users u on u.id = ds.user_id ' .
                 'where period_year = ' . self::$DB->quote($year, 'integer') . ' ';

         //  Optionally exclude NBMs
         if (!empty($nbm_exclusions))
         {
             $query .= ' AND u.id NOT IN (' . self::$DB->escape(implode(',', $nbm_exclusions)) . ')';
         }

				 if (!empty($client_id)) {
					 $query .= ' AND u.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
				 }

         $query .=  'and u.is_active = 1 ' .
                'group by period_quarter, user_id ' .
                'order by period_year desc, period_quarter desc, u.name';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }

    public function getReport13QuarterClientSummary($year, $nbm_exclusions, $client_id = null)
    {
//        $debug = true;

        $query = 'select period_year, period_quarter, u.name as nbm, c.name as client, ' .
                 'sum(meeting_category_attended_count) as attended, ' .
                 'sum(meeting_category_tbr_count) as tbr, ' .
                'sum(meeting_category_cancelled_count) as cancelled, ' .
                'sum(meeting_category_unknown_count) as unknown, ' .
                '(sum(meeting_in_diary_this_month_count) - sum(meeting_category_unknown_count)) as diary ' .
                'from tbl_data_statistics ds ' .
                'join tbl_rbac_users u on u.id = ds.user_id ' .
                'join tbl_campaigns cam on cam.id = ds.campaign_id ' .
                'join tbl_clients c on c.id = cam.client_id ' .
                'where period_year = ' . self::$DB->quote($year, 'integer') . ' ';

		    //  Optionally exclude NBMs
		        if (!empty($nbm_exclusions))
		        {
		            $query .= ' AND u.id NOT IN (' . self::$DB->escape(implode(',', $nbm_exclusions)) . ')';
		        }

						if (!empty($client_id)) {
	 						$query .= ' AND u.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ';
	 					}

           $query .=  'and u.is_active = 1 ' .
                'group by period_quarter, user_id, c.name ' .
                'order by period_year desc, period_quarter desc, u.name, c.name';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }


        ///////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////

//  REPORT 14 - NBM Bonus detail report

    ///////////////////////////////////////////////////////////

    public function getReport14NBMList($nbm_exclusions, $client_id = null)
    {
    	 $query = 'select id from tbl_rbac_users ' .
    	           'WHERE is_active = 1';

        //  Optionally exclude NBMs
        if (!empty($nbm_exclusions))
        {
            $query .= ' AND id NOT IN (' . self::$DB->escape(implode(',', $nbm_exclusions)) . ')';
        }

				if (!empty($client_id)) {
					$query .= ' AND client_id = ' . self::$DB->quote($client_id, 'integer');
				}


        $query .= ' ORDER BY name';

//        echo "<p>$query;</p>";

        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
        return $results;
    }

    public function getReport14SingleNBMList($nbm_id)
    {
         $query =   'select id from tbl_rbac_users ' .
                    'WHERE id = ' . self::$DB->quote($nbm_id);

//        echo "<p>$query;</p>";

        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
        return $results;
    }

    public function getReport14NBMSummary($year, $userId)
    {
//        $debug = true;

        $query = 'select period_year, period_quarter, u.name as nbm, ' .
                 'sum(meeting_category_attended_count) as attended, ' .
                 'sum(meeting_category_tbr_count) as tbr, ' .
                'sum(meeting_category_cancelled_count) as cancelled, ' .
                'sum(meeting_category_unknown_count) as unknown, ' .
                '(sum(meeting_in_diary_this_month_count) - sum(meeting_category_unknown_count)) as diary ' .
                'from tbl_data_statistics ds ' .
                'join tbl_rbac_users u on u.id = ds.user_id ' .
                'where period_year = ' . self::$DB->quote($year, 'integer') . ' ' .
                'and u.id = ' . self::$DB->quote($userId, 'integer') . ' ' .
                'group by period_quarter ' .
                'order by period_year, period_quarter';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }

    public function getReport14QuarterClientSummary($year, $userId)
    {
//        $debug = true;

        $query = 'select period_year, period_quarter, u.name as nbm, c.name as client, ' .
                 'sum(meeting_category_attended_count) as attended, ' .
                 'sum(meeting_category_tbr_count) as tbr, ' .
                'sum(meeting_category_cancelled_count) as cancelled, ' .
                'sum(meeting_category_unknown_count) as unknown, ' .
                '(sum(meeting_in_diary_this_month_count) - sum(meeting_category_unknown_count)) as diary ' .
                'from tbl_data_statistics ds ' .
                'join tbl_rbac_users u on u.id = ds.user_id ' .
                'join tbl_campaigns cam on cam.id = ds.campaign_id ' .
                'join tbl_clients c on c.id = cam.client_id ' .
                'where period_year = ' . self::$DB->quote($year, 'integer') . ' ' .
                'and u.id = ' . self::$DB->quote($userId, 'integer') . ' ' .
                'group by period_quarter, c.name ' .
                'order by period_year, period_quarter, c.name';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }


        ///////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////

//  REPORT 15 - NBM Bonus detail report

    ///////////////////////////////////////////////////////////



    public function getReport15ClientExceptionBase($clientId)
    {
//        $debug = true;

    	$query = 'select c.name as client_name, u.name as nbm ' .
                'from tbl_clients c ' .
                'join tbl_campaigns cam on cam.client_id = c.id ' .
                'join tbl_campaign_nbms camn on cam.id = camn.campaign_id and camn.is_lead_nbm = true ' .
                'join tbl_rbac_users u on camn.user_id = u.id ' .
                'where c.id = ' . self::$DB->quote($clientId, 'integer');

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }

    public function getReport15ClientMeetings($clientId)
    {
//        $debug = true;

    	$query = 'select ' .
                'sum(meeting_category_tbr_count) as tbr, ' .
                'sum(meeting_category_unknown_count) as unknown ' .
                'from tbl_clients c ' .
                'join tbl_campaigns cam on cam.client_id = c.id ' .
                'join tbl_data_statistics ds on cam.id = ds.campaign_id ' .
                'where c.id = ' . self::$DB->quote($clientId, 'integer');

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }


    public function getReport15ClientFreshLeads($clientId)
    {
//        $debug = true;

    	$query = 'select ' .
                'count(pi.id) as fresh_lead_count ' .
                'from tbl_post_initiatives pi ' .
                'join tbl_initiatives i on i.id = pi.initiative_id ' .
                'join tbl_campaigns cam on cam.id = i.campaign_id ' .
                'join tbl_clients c on c.id = cam.client_id ' .
                'where c.id = ' . self::$DB->quote($clientId, 'integer') . ' ' .
                'and pi.status_id = 7';

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }

    public function getReport15ClientMaxTargetDate($clientId)
    {
//        $debug = true;

        $query = 'select max(camt.year_month) as max_target_date ' .
                'from tbl_clients c ' .
                'join tbl_campaigns cam on cam.client_id = c.id ' .
                'join tbl_campaign_targets camt on camt.campaign_id = cam.id ' .
                'where c.id = ' . self::$DB->quote($clientId, 'integer');

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }

    public function getReport15ClientSectorCount($clientId)
    {
//        $debug = true;

        $query = 'select count(cams.id) as sector_count ' .
                'from tbl_clients c ' .
                'join tbl_campaigns cam on cam.client_id = c.id ' .
                'join tbl_campaign_sectors cams on cams.campaign_id = cam.id ' .
                'where c.id = ' . self::$DB->quote($clientId, 'integer');

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }

    public function getReport15ClientDisciplineCount($clientId)
    {
//        $debug = true;

        $query = 'select count(camd.id) as discipline_count ' .
                'from tbl_clients c ' .
                'join tbl_campaigns cam on cam.client_id = c.id ' .
                'join tbl_campaign_disciplines camd on camd.campaign_id = cam.id ' .
                'where c.id = ' . self::$DB->quote($clientId, 'integer');

        if ($debug) echo "<p>$query;</p>";
        $results = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

        return $results;
    }

    ///////////////////////////////////////////////////////////
	/**
	 * Get the target number of calls made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public function getTargetCalls($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$start_year_month = date('Ym', strtotime($start_date));
		$end_year_month   = date('Ym', strtotime($end_date));
		$current_year_month = $start_year_month;
		$target = 0;

		$nbm_calls_per_day = app_domain_Configuration::getProperty('NBM_CALLS_PER_DAY');
		while ($current_year_month <= $end_year_month)
		{
			// Get the target for the current month
			if (!is_null($nbm_id))
			{
				$nbm_count = 1;
			}
			elseif (!is_null($team_id))
			{
				$sql = 'SELECT COUNT(*) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_team_nbms AS tn ON cnt.user_id = tn.user_id ' .
						'WHERE `year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'AND tn.team_id = ' . self::$DB->quote($team_id, 'integer') . ' ' .
						'GROUP BY (cnt.user_id)';
				$nbm_count = self::$DB->queryOne($sql);
			}
			else
			{
				$sql = 'SELECT COUNT(*) FROM tbl_campaign_nbm_targets ' .
						'WHERE `year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'GROUP BY (user_id)';
				$nbm_count = self::$DB->queryOne($sql);
			}

			$working_days_count = Utils::getWorkingDaysInMonth($current_year_month);
			$number = $nbm_calls_per_day * $nbm_count * $working_days_count;

			// Pro-rata where appropriate
			if ($current_year_month == $start_year_month && $current_year_month == $end_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $start_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year)));
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $end_year_month)
			{
				// Pro-rata for last month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($end_date, 0, 4);
				$month = substr($end_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays(date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)), $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}

			$target += $number;

			// Move to next month
			$year  = substr($current_year_month, 0, 4);
			$month = substr($current_year_month, 4, 2);
			$current_year_month = date('Ym', mktime(0, 0, 0, $month+1, 1, $year));
		}
		return $target;
	}

	/**
	 * Get the target number of effective calls made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public function getTargetEffectives($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$start_year_month = date('Ym', strtotime($start_date));
		$end_year_month   = date('Ym', strtotime($end_date));
		$current_year_month = $start_year_month;
		$target = 0;

		while ($current_year_month <= $end_year_month)
		{
			// Get the target for the current month
			if (!is_null($nbm_id))
			{
				$sql = 'SELECT SUM(cnt.effectives) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'AND cnt.user_id = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
						"AND cam.end_year_month = ''";
			}
			elseif (!is_null($team_id))
			{
				$sql = 'SELECT SUM(cnt.effectives) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_team_nbms AS tn ON cnt.user_id = tn.user_id ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'AND tn.team_id = ' . self::$DB->quote($team_id, 'integer') . ' ' .
						"AND cam.end_year_month = ''";
			}
			else
			{
				$sql = 'SELECT SUM(cnt.effectives) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						"AND cam.end_year_month = ''";
			}
			$number = self::$DB->queryOne($sql);

			// Pro-rata where appropriate
			if ($current_year_month == $start_year_month && $current_year_month == $end_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $start_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year)));
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $end_year_month)
			{
				// Pro-rata for last month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($end_date, 0, 4);
				$month = substr($end_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays(date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)), $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}

			$target += $number;

			// Move to next month
			$year  = substr($current_year_month, 0, 4);
			$month = substr($current_year_month, 4, 2);
			$current_year_month = date('Ym', mktime(0, 0, 0, $month+1, 1, $year));
		}
		return $target;
	}

	/**
	 * Get the target number of meetings set.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public function getTargetMeetingsSet($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$start_year_month = date('Ym', strtotime($start_date));
		$end_year_month   = date('Ym', strtotime($end_date));
		$current_year_month = $start_year_month;
		$target = 0;

		while ($current_year_month <= $end_year_month)
		{
			// Get the target for the current month
			if (!is_null($nbm_id))
			{
				$sql = 'SELECT SUM(cnt.meetings_set) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'AND cnt.user_id = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
						"AND cam.end_year_month = ''";
			}
			elseif (!is_null($team_id))
			{
				$sql = 'SELECT SUM(cnt.meetings_set) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_team_nbms AS tn ON cnt.user_id = tn.user_id ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'AND tn.team_id = ' . self::$DB->quote($team_id, 'integer') . ' ' .
						"AND cam.end_year_month = ''";
			}
			else
			{
				$sql = 'SELECT SUM(cnt.meetings_set) FROM tbl_campaign_targets AS cnt ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						"AND cam.end_year_month = ''";
			}
			$number = self::$DB->queryOne($sql);

			// Pro-rata where appropriate
			if ($current_year_month == $start_year_month && $current_year_month == $end_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $start_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year)));
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $end_year_month)
			{
				// Pro-rata for last month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($end_date, 0, 4);
				$month = substr($end_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays(date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)), $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}

			$target += $number;

			// Move to next month
			$year  = substr($current_year_month, 0, 4);
			$month = substr($current_year_month, 4, 2);
			$current_year_month = date('Ym', mktime(0, 0, 0, $month+1, 1, $year));
		}
		return $target;
	}

	/**
	 * Get the target number of meetings attended.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public function getTargetMeetingsAttended($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$start_year_month = date('Ym', strtotime($start_date));
		$end_year_month   = date('Ym', strtotime($end_date));
		$current_year_month = $start_year_month;
		$target = 0;

		while ($current_year_month <= $end_year_month)
		{
			// Get the target for the current month
			if (!is_null($nbm_id))
			{
				$sql = 'SELECT SUM(cnt.meetings_attended) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'AND cnt.user_id = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
						"AND cam.end_year_month = ''";
			}
			elseif (!is_null($team_id))
			{
				$sql = 'SELECT SUM(cnt.meetings_attended) FROM tbl_campaign_nbm_targets AS cnt ' .
						'INNER JOIN tbl_team_nbms AS tn ON cnt.user_id = tn.user_id ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						'AND tn.team_id = ' . self::$DB->quote($team_id, 'integer') . ' ' .
						"AND cam.end_year_month = ''";
			}
			else
			{
				$sql = 'SELECT SUM(cnt.meetings_attended) FROM tbl_campaign_targets AS cnt ' .
						'INNER JOIN tbl_campaigns AS cam ON cnt.campaign_id = cam.id ' .
						'WHERE cnt.`year_month` = ' . self::$DB->quote($current_year_month, 'text') . ' ' .
						"AND cam.end_year_month = ''";
			}
			$number = self::$DB->queryOne($sql);

			// Pro-rata where appropriate
			if ($current_year_month == $start_year_month && $current_year_month == $end_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $start_year_month)
			{
				// Pro-rata if first month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($start_date, 0, 4);
				$year  = substr($start_date, 0, 4);
				$month = substr($start_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays($start_date, date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year)));
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}
			elseif ($current_year_month == $end_year_month)
			{
				// Pro-rata for last month
				$working_days_in_month = Utils::getWorkingDaysInMonth($current_year_month);
				$year  = substr($end_date, 0, 4);
				$month = substr($end_date, 5, 2);
				$pr_working_days_in_month = Utils::getWorkingDays(date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)), $end_date);
				$number = round(($pr_working_days_in_month/$working_days_in_month) * $number);
			}

			$target += $number;

			// Move to next month
			$year  = substr($current_year_month, 0, 4);
			$month = substr($current_year_month, 4, 2);
			$current_year_month = date('Ym', mktime(0, 0, 0, $month+1, 1, $year));
		}
		return $target;
	}

	/**
	 * Get the actual number of calls made in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public function getActualCalls($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		if (!is_null($campaign_id))
		{
			$sql = 'SELECT IFNULL(SUM(call_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		}
		elseif (!is_null($nbm_id))
		{
			$sql = 'SELECT IFNULL(SUM(call_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND user_id = ' . self::$DB->quote($nbm_id, 'integer');
		}
		elseif (!is_null($team_id))
		{
			$sql = 'SELECT IFNULL(SUM(dsd.call_count), 0) FROM tbl_data_statistics_daily AS dsd ' .
					'INNER JOIN tbl_team_nbms AS tn ON dsd.user_id = tn.user_id ' .
					'WHERE dsd.`date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND dsd.`date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND tn.team_id = ' . self::$DB->quote($team_id, 'integer');
		}
		else
		{
			$sql = 'SELECT IFNULL(SUM(call_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'integer') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date');
		}
		return self::$DB->queryOne($sql);
	}

	/**
	 * Get the actual number of effective calls made in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public function getActualEffectives($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		if (!is_null($campaign_id))
		{
			$sql = 'SELECT IFNULL(SUM(call_effective_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		}
		elseif (!is_null($nbm_id))
		{
			$sql = 'SELECT IFNULL(SUM(call_effective_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND user_id = ' . self::$DB->quote($nbm_id, 'integer');
		}
		elseif (!is_null($team_id))
		{
			$sql = 'SELECT IFNULL(SUM(dsd.call_effective_count), 0) FROM tbl_data_statistics_daily AS dsd ' .
					'INNER JOIN tbl_team_nbms AS tn ON dsd.user_id = tn.user_id ' .
					'WHERE dsd.`date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND dsd.`date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND tn.team_id = ' . self::$DB->quote($team_id, 'integer');
		}
		else
		{
			$sql = 'SELECT IFNULL(SUM(call_effective_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date');
		}
		return self::$DB->queryOne($sql);
	}

	/**
	 * Get the actual number of meetings set.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public function getActualMeetingsSet($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		if (!is_null($campaign_id))
		{
			$sql = 'SELECT IFNULL(SUM(meeting_set_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		}
		elseif (!is_null($nbm_id))
		{
			$sql = 'SELECT IFNULL(SUM(meeting_set_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND user_id = ' . self::$DB->quote($nbm_id, 'integer');
		}
		elseif (!is_null($team_id))
		{
			$sql = 'SELECT IFNULL(SUM(dsd.meeting_set_count), 0) FROM tbl_data_statistics_daily AS dsd ' .
					'INNER JOIN tbl_team_nbms AS tn ON dsd.user_id = tn.user_id ' .
					'WHERE dsd.`date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND dsd.`date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND tn.team_id = ' . self::$DB->quote($nbm_id, 'integer');
		}
		else
		{
			$sql = 'SELECT IFNULL(SUM(meeting_set_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date');
		}
		return self::$DB->queryOne($sql);
	}

	/**
	 * Get the actual number of meetings attended.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public function getActualMeetingsAttended($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		if (!is_null($campaign_id))
		{
			$sql = 'SELECT IFNULL(SUM(meeting_attended_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		}
		elseif (!is_null($nbm_id))
		{
			$sql = 'SELECT IFNULL(SUM(meeting_attended_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND user_id = ' . self::$DB->quote($nbm_id, 'integer');
		}
		elseif (!is_null($team_id))
		{
			$sql = 'SELECT IFNULL(SUM(dsd.meeting_attended_count), 0) FROM tbl_data_statistics_daily AS dsd ' .
					'INNER JOIN tbl_team_nbms AS tn ON dsd.user_id = tn.user_id ' .
					'WHERE dsd.`date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND dsd.`date` <= ' . self::$DB->quote($end_date, 'date') . ' ' .
					'AND tn.team_id = ' . self::$DB->quote($nbm_id, 'integer');
		}
		else
		{
			$sql = 'SELECT IFNULL(SUM(meeting_attended_count), 0) FROM tbl_data_statistics_daily ' .
					'WHERE `date` >= ' . self::$DB->quote($start_date, 'date') . ' ' .
					'AND `date` <= ' . self::$DB->quote($end_date, 'date');
		}
		return self::$DB->queryOne($sql);
	}

}

?>