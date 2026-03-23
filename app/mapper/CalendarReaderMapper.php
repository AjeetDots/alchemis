<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/ReaderMapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package ContinuityOnline
 */
class app_mapper_CalendarReaderMapper extends app_mapper_ReaderMapper implements app_domain_CalendarReaderReader
{

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$data = array('id' => $id);
		// factor this out

		// Returns an MDB2_Result object
		$result = $this->doStatement($this->selectStmt, $data);

		return $result;
//		// Extract and return an associative array from the MDB2_Result object
//		return $this->load($result);
	}

	/**
	 * Returns an inline subquery fragment for LEFT JOIN that counts how many times
	 * each meeting has been rearranged within the given datetime range.
	 * Replaces the previous pattern of creating/dropping two temporary tables per request
	 * (4 extra round-trips: CREATE t2, CREATE t1, DROP t1, DROP t2).
	 *
	 * @param string $from_dt  datetime 'YYYY-MM-DD HH:MM:SS'
	 * @param string $to_dt    datetime 'YYYY-MM-DD HH:MM:SS'
	 * @return string  SQL fragment suitable for use as: LEFT JOIN {result} ON m.id = t1.id
	 */
	private function rearrangedJoin($from_dt, $to_dt)
	{
		return '(SELECT id, COUNT(id) AS rearranged_count ' .
		       'FROM (SELECT id FROM tbl_meetings_shadow ' .
		       'WHERE status_id IN (18, 19) ' .
		       "AND shadow_type = 'u' " .
		       'AND date >= ' . self::$DB->quote($from_dt, 'timestamp') . ' ' .
		       'AND date <= ' . self::$DB->quote($to_dt, 'timestamp') . ' ' .
		       'GROUP BY id, date) AS _ms ' .
		       'GROUP BY id) AS t1';
	}

	/**
	 * Finds the meetings and information requests on a given date, filtered by to either NBM or client.  If neither
	 * $nbm_id or $client_id is supplied, the function does not filter and pulls all (i.e. global) results.
	 * @param string $date
	 * @param integer $nmb_id
	 * @param integer $client_id
	 */
	public function findByDate($date, $nbm_id = null, $client_id = null)
	{
//		echo "<p><b>app_mapper_CalendarReaderMapper::findByDate($date, $nbm_id, $client_id)</b></p>";

		$from = $date . ' 00:00:00';
		$to   = $date . ' 23:59:59';

		$rearranged_join = $this->rearrangedJoin($from, $to);

		if (!is_null($nbm_id))
		{
					// Meetings
					// Need to add a temp table with the latest entry from tbl_meetings_shadow so that we can find the
					// user_id of the last person to update the meeting
			$sql = "SELECT m.id, m.date, m.reminder_date, CONCAT(m.client, ' / ', m.company) AS subject, m.notes, 'meeting' AS type, NULL AS type_id, " .
					'1 AS type_priority, company_id, post_id, m.created_by AS nbm_id, NULL as client_id, m.post_initiative_id, m.initiative_id, ' .
					'false AS completed, m.status_id AS status_id, ' .
					'IFNULL(t1.rearranged_count, 0) AS rearranged_count ' .
					'FROM vw_calendar_meetings AS m ' .
					'LEFT JOIN ' . $rearranged_join . ' ON m.id = t1.id ' .
					'WHERE (m.`date` >= ' . self::$DB->quote($from, 'timestamp') . ' ' .
					'AND m.`date` <= ' . self::$DB->quote($to, 'timestamp') . ') ' .
			        'AND (m.created_by = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
			        'OR m.modified_by = ' . self::$DB->quote($nbm_id, 'integer') . ') ' .
					'UNION ' .



					// Actions
					"SELECT a.id, a.due_date AS `date`, a.reminder_date, a.subject, a.notes, 'action' AS type, a.type_id AS type_id, " .
					'2 AS type_priority, p.company_id, pi.post_id, a.user_id AS nbm_id, NULL as client_id, a.post_initiative_id, pi.initiative_id, ' .
					"IF(completed_date IS NULL OR completed_date = '0000-00-00 00:00:00', false, true) AS completed, NULL AS status_id, " .
					'0 AS rearranged_count ' .
					'FROM tbl_actions AS a ' .
					'LEFT JOIN tbl_post_initiatives AS pi ON a.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
					'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE (a.due_date >= ' . self::$DB->quote($from, 'timestamp') . ' AND a.due_date <= ' . self::$DB->quote($to, 'timestamp') . ') AND a.user_id = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
					'UNION ' .

					// Events
					"SELECT e.id, CONCAT(e.date, ' 00:00:00') AS `date`, e.reminder_date, CONCAT('[', e.type, '] ', e.subject) AS subject, e.notes, 'event' AS type, NULL AS type_id, " .
					'3 AS type_priority, NULL AS company_id, NULL AS post_id, e.user_id AS nbm_id, NULL as client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
					'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
					'FROM vw_events AS e ' .
					'WHERE (e.date >= ' . self::$DB->quote($date, 'timestamp') . ' AND e.date <= ' . self::$DB->quote($date, 'timestamp') . ') AND e.user_id = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
					'UNION ' .

					// Bank holidays
					"SELECT b.id, CONCAT(b.date, ' 00:00:00') AS `date`, '' AS reminder_date, b.name AS subject, '' AS notes, 'bank_holiday' AS type, NULL AS type_id, " .
					'4 AS type_priority, NULL AS company_id, NULL AS post_id, ' . self::$DB->quote($nbm_id, 'integer') . ' AS nbm_id , NULL as client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
					'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
					'FROM tbl_bank_holidays AS b ' .
					'WHERE (b.date >= ' . self::$DB->quote($date, 'timestamp') . ' AND b.date <= ' . self::$DB->quote($date, 'timestamp') . ') ' .
					'ORDER BY `completed`, `date`, `type_priority`';

		}
		elseif (!is_null($client_id))
		{
					// Meetings
			$sql = "SELECT m.id, m.date, m.reminder_date, CONCAT(m.client, ' / ', m.company) AS subject, m.notes, 'meeting' AS type, NULL AS type_id, " .
					'1 AS type_priority, company_id, post_id, m.created_by AS nbm_id, m.client_id, m.post_initiative_id, m.initiative_id, ' .
					'false AS completed, m.status_id AS status_id, ' .
					'IFNULL(t1.rearranged_count, 0) AS rearranged_count ' .
					'FROM vw_calendar_meetings AS m ' .
					'LEFT JOIN ' . $rearranged_join . ' ON m.id = t1.id ' .
					'WHERE (m.`date` >= ' . self::$DB->quote($from, 'timestamp') . ' AND m.`date` <= ' . self::$DB->quote($to, 'timestamp') . ') AND m.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'UNION ' .

					// Actions
					"SELECT a.id, a.due_date AS `date`, a.reminder_date, a.subject, a.notes, 'action' AS type, a.type_id AS type_id, " .
					'2 AS type_priority, p.company_id, pi.post_id, a.user_id AS nbm_id, cam.client_id, a.post_initiative_id, pi.initiative_id, ' .
					"IF(completed_date IS NULL OR completed_date = '0000-00-00 00:00:00', false, true) AS completed, NULL AS status_id, 0 AS rearranged_count " .
					'FROM tbl_actions AS a ' .
					'LEFT JOIN tbl_post_initiatives AS pi ON a.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
					'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE (a.due_date >= ' . self::$DB->quote($from, 'timestamp') . ' AND a.due_date <= ' . self::$DB->quote($to, 'timestamp') . ') AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'UNION ' .

					// Events
					"SELECT e.id, CONCAT(e.date, ' 00:00:00') AS `date`, e.reminder_date, CONCAT('[', e.type, '] ', e.subject) AS subject, e.notes, 'event' AS type, NULL AS type_id, " .
					'3 AS type_priority, NULL AS company_id, NULL AS post_id, e.user_id AS nbm_id, e.client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
					'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
					'FROM vw_events AS e ' .
					'WHERE (e.date >= ' . self::$DB->quote($date, 'timestamp') . ' AND e.date <= ' . self::$DB->quote($date, 'timestamp') . ') AND e.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'UNION ' .

					// Bank holidays
					"SELECT b.id, CONCAT(b.date, ' 00:00:00') AS `date`, '' AS reminder_date, b.name AS subject, '' AS notes, 'bank_holiday' AS type, NULL AS type_id, " .
					'4 AS `type_priority`, NULL AS company_id, NULL AS post_id, NULL AS nbm_id, ' . self::$DB->quote($client_id, 'integer') . ' AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
					'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
					'FROM tbl_bank_holidays AS b ' .
					'WHERE (b.date >= ' . self::$DB->quote($date, 'timestamp') . ' AND b.date <= ' . self::$DB->quote($date, 'timestamp') . ') ' .
					'ORDER BY `completed`, `date`, `type_priority`';
		}
		else
		{
					// Meetings
			$sql = "SELECT m.id, m.date, m.reminder_date, CONCAT(m.client, ' / ', m.company) AS subject, m.notes, 'meeting' AS type, NULL AS type_id, " .
					'1 AS type_priority, m.company_id, m.post_id, m.created_by AS nbm_id, m.client_id, m.post_initiative_id, m.initiative_id, ' .
					'false AS completed, m.status_id AS status_id, ' .
					'IFNULL(t1.rearranged_count, 0) AS rearranged_count ' .
					'FROM vw_calendar_meetings AS m ' .
					'LEFT JOIN ' . $rearranged_join . ' ON m.id = t1.id ' .
					'WHERE (m.`date` >= ' . self::$DB->quote($from, 'timestamp') . ' AND `date` <= ' . self::$DB->quote($to, 'timestamp') . ') ' .
					'UNION ' .

					// Actions
					"SELECT a.id, a.due_date AS `date`, a.reminder_date, a.subject, a.notes, 'action' AS type, a.type_id AS type_id, " .
					'2 AS type_priority, p.company_id, pi.post_id, a.user_id AS nbm_id, NULL AS client_id, a.post_initiative_id, pi.initiative_id, ' .
					"IF(completed_date IS NULL OR completed_date = '0000-00-00 00:00:00', false, true) AS completed, NULL AS status_id, 0 AS rearranged_count " .
					'FROM tbl_actions AS a ' .
					'LEFT JOIN tbl_post_initiatives AS pi ON a.post_initiative_id = pi.id ' .
					'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
					'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE (a.due_date >= ' . self::$DB->quote($from, 'timestamp') . ' AND a.due_date <= ' . self::$DB->quote($to, 'timestamp') . ') ' .
					'UNION ' .

					// Events
					"SELECT e.id, CONCAT(e.date, ' 00:00:00') AS `date`, e.reminder_date, CONCAT('[', e.type, '] ', e.subject) AS subject, e.notes, 'event' AS type, NULL AS type_id, " .
					'3 AS type_priority, NULL AS company_id, NULL AS post_id, e.user_id AS nbm_id, NULL AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
					'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
					'FROM vw_events AS e ' .
					'WHERE (e.date >= ' . self::$DB->quote($date, 'timestamp') . ' AND e.date <= ' . self::$DB->quote($date, 'timestamp') . ') ' .
					'UNION ' .

					// Bank holidays
					"SELECT b.id, CONCAT(b.date, ' 00:00:00') AS `date`, '' AS reminder_date, b.name AS subject, '' AS notes, 'bank_holiday' AS type, NULL AS type_id, " .
					'4 AS type_priority, NULL AS company_id, NULL AS post_id, NULL AS nbm_id, NULL AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
					'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
					'FROM tbl_bank_holidays AS b ' .
					'WHERE (b.date >= ' . self::$DB->quote($date, 'timestamp') . ' AND b.date <= ' . self::$DB->quote($date, 'timestamp') . ') ' .
					'ORDER BY `completed`, `date`, `type_priority`';
		}
//		echo $sql;
		return self::$DB->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Fetches all calendar entries (meetings, actions, events, bank holidays) for an entire date
	 * range in a single query, replacing the N+1 pattern of calling findByDate() per day.
	 * Returns a flat array of rows; the caller is responsible for grouping them by date.
	 *
	 * @param string  $from      First day of range 'YYYY-MM-DD'
	 * @param string  $to        Last day of range  'YYYY-MM-DD'
	 * @param integer $nbm_id
	 * @param integer $client_id
	 * @return array
	 */
	public function findByDateRange($from, $to, $nbm_id = null, $client_id = null)
	{
		$from_dt = $from . ' 00:00:00';
		$to_dt   = $to   . ' 23:59:59';

		$rearranged_join = $this->rearrangedJoin($from_dt, $to_dt);

		if (!is_null($nbm_id))
		{
			$sql =
				"SELECT m.id, m.date, m.reminder_date, CONCAT(m.client, ' / ', m.company) AS subject, m.notes, 'meeting' AS type, NULL AS type_id, " .
				'1 AS type_priority, company_id, post_id, m.created_by AS nbm_id, NULL AS client_id, m.post_initiative_id, m.initiative_id, ' .
				'false AS completed, m.status_id AS status_id, ' .
				'IFNULL(t1.rearranged_count, 0) AS rearranged_count ' .
				'FROM vw_calendar_meetings AS m ' .
				'LEFT JOIN ' . $rearranged_join . ' ON m.id = t1.id ' .
				'WHERE (m.`date` >= ' . self::$DB->quote($from_dt, 'timestamp') . ' AND m.`date` <= ' . self::$DB->quote($to_dt, 'timestamp') . ') ' .
				'AND (m.created_by = ' . self::$DB->quote($nbm_id, 'integer') . ' OR m.modified_by = ' . self::$DB->quote($nbm_id, 'integer') . ') ' .
				'UNION ' .
				"SELECT a.id, a.due_date AS `date`, a.reminder_date, a.subject, a.notes, 'action' AS type, a.type_id AS type_id, " .
				'2 AS type_priority, p.company_id, pi.post_id, a.user_id AS nbm_id, NULL AS client_id, a.post_initiative_id, pi.initiative_id, ' .
				"IF(completed_date IS NULL OR completed_date = '0000-00-00 00:00:00', false, true) AS completed, NULL AS status_id, 0 AS rearranged_count " .
				'FROM tbl_actions AS a ' .
				'LEFT JOIN tbl_post_initiatives AS pi ON a.post_initiative_id = pi.id ' .
				'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
				'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
				'WHERE (a.due_date >= ' . self::$DB->quote($from_dt, 'timestamp') . ' AND a.due_date <= ' . self::$DB->quote($to_dt, 'timestamp') . ') AND a.user_id = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
				'UNION ' .
				"SELECT e.id, CONCAT(e.date, ' 00:00:00') AS `date`, e.reminder_date, CONCAT('[', e.type, '] ', e.subject) AS subject, e.notes, 'event' AS type, NULL AS type_id, " .
				'3 AS type_priority, NULL AS company_id, NULL AS post_id, e.user_id AS nbm_id, NULL AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
				'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
				'FROM vw_events AS e ' .
				'WHERE (e.date >= ' . self::$DB->quote($from, 'text') . ' AND e.date <= ' . self::$DB->quote($to, 'text') . ') AND e.user_id = ' . self::$DB->quote($nbm_id, 'integer') . ' ' .
				'UNION ' .
				"SELECT b.id, CONCAT(b.date, ' 00:00:00') AS `date`, '' AS reminder_date, b.name AS subject, '' AS notes, 'bank_holiday' AS type, NULL AS type_id, " .
				'4 AS type_priority, NULL AS company_id, NULL AS post_id, ' . self::$DB->quote($nbm_id, 'integer') . ' AS nbm_id, NULL AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
				'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
				'FROM tbl_bank_holidays AS b ' .
				'WHERE (b.date >= ' . self::$DB->quote($from, 'text') . ' AND b.date <= ' . self::$DB->quote($to, 'text') . ') ' .
				'ORDER BY `completed`, `date`, `type_priority`';
		}
		elseif (!is_null($client_id))
		{
			$sql =
				"SELECT m.id, m.date, m.reminder_date, CONCAT(m.client, ' / ', m.company) AS subject, m.notes, 'meeting' AS type, NULL AS type_id, " .
				'1 AS type_priority, company_id, post_id, m.created_by AS nbm_id, m.client_id, m.post_initiative_id, m.initiative_id, ' .
				'false AS completed, m.status_id AS status_id, ' .
				'IFNULL(t1.rearranged_count, 0) AS rearranged_count ' .
				'FROM vw_calendar_meetings AS m ' .
				'LEFT JOIN ' . $rearranged_join . ' ON m.id = t1.id ' .
				'WHERE (m.`date` >= ' . self::$DB->quote($from_dt, 'timestamp') . ' AND m.`date` <= ' . self::$DB->quote($to_dt, 'timestamp') . ') AND m.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
				'UNION ' .
				"SELECT a.id, a.due_date AS `date`, a.reminder_date, a.subject, a.notes, 'action' AS type, a.type_id AS type_id, " .
				'2 AS type_priority, p.company_id, pi.post_id, a.user_id AS nbm_id, cam.client_id, a.post_initiative_id, pi.initiative_id, ' .
				"IF(completed_date IS NULL OR completed_date = '0000-00-00 00:00:00', false, true) AS completed, NULL AS status_id, 0 AS rearranged_count " .
				'FROM tbl_actions AS a ' .
				'LEFT JOIN tbl_post_initiatives AS pi ON a.post_initiative_id = pi.id ' .
				'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
				'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
				'WHERE (a.due_date >= ' . self::$DB->quote($from_dt, 'timestamp') . ' AND a.due_date <= ' . self::$DB->quote($to_dt, 'timestamp') . ') AND cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
				'UNION ' .
				"SELECT e.id, CONCAT(e.date, ' 00:00:00') AS `date`, e.reminder_date, CONCAT('[', e.type, '] ', e.subject) AS subject, e.notes, 'event' AS type, NULL AS type_id, " .
				'3 AS type_priority, NULL AS company_id, NULL AS post_id, e.user_id AS nbm_id, e.client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
				'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
				'FROM vw_events AS e ' .
				'WHERE (e.date >= ' . self::$DB->quote($from, 'text') . ' AND e.date <= ' . self::$DB->quote($to, 'text') . ') AND e.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
				'UNION ' .
				"SELECT b.id, CONCAT(b.date, ' 00:00:00') AS `date`, '' AS reminder_date, b.name AS subject, '' AS notes, 'bank_holiday' AS type, NULL AS type_id, " .
				'4 AS type_priority, NULL AS company_id, NULL AS post_id, NULL AS nbm_id, ' . self::$DB->quote($client_id, 'integer') . ' AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
				'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
				'FROM tbl_bank_holidays AS b ' .
				'WHERE (b.date >= ' . self::$DB->quote($from, 'text') . ' AND b.date <= ' . self::$DB->quote($to, 'text') . ') ' .
				'ORDER BY `completed`, `date`, `type_priority`';
		}
		else
		{
			$sql =
				"SELECT m.id, m.date, m.reminder_date, CONCAT(m.client, ' / ', m.company) AS subject, m.notes, 'meeting' AS type, NULL AS type_id, " .
				'1 AS type_priority, m.company_id, m.post_id, m.created_by AS nbm_id, m.client_id, m.post_initiative_id, m.initiative_id, ' .
				'false AS completed, m.status_id AS status_id, ' .
				'IFNULL(t1.rearranged_count, 0) AS rearranged_count ' .
				'FROM vw_calendar_meetings AS m ' .
				'LEFT JOIN ' . $rearranged_join . ' ON m.id = t1.id ' .
				'WHERE (m.`date` >= ' . self::$DB->quote($from_dt, 'timestamp') . ' AND `date` <= ' . self::$DB->quote($to_dt, 'timestamp') . ') ' .
				'UNION ' .
				"SELECT a.id, a.due_date AS `date`, a.reminder_date, a.subject, a.notes, 'action' AS type, a.type_id AS type_id, " .
				'2 AS type_priority, p.company_id, pi.post_id, a.user_id AS nbm_id, NULL AS client_id, a.post_initiative_id, pi.initiative_id, ' .
				"IF(completed_date IS NULL OR completed_date = '0000-00-00 00:00:00', false, true) AS completed, NULL AS status_id, 0 AS rearranged_count " .
				'FROM tbl_actions AS a ' .
				'LEFT JOIN tbl_post_initiatives AS pi ON a.post_initiative_id = pi.id ' .
				'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
				'LEFT JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
				'WHERE (a.due_date >= ' . self::$DB->quote($from_dt, 'timestamp') . ' AND a.due_date <= ' . self::$DB->quote($to_dt, 'timestamp') . ') ' .
				'UNION ' .
				"SELECT e.id, CONCAT(e.date, ' 00:00:00') AS `date`, e.reminder_date, CONCAT('[', e.type, '] ', e.subject) AS subject, e.notes, 'event' AS type, NULL AS type_id, " .
				'3 AS type_priority, NULL AS company_id, NULL AS post_id, e.user_id AS nbm_id, NULL AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
				'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
				'FROM vw_events AS e ' .
				'WHERE (e.date >= ' . self::$DB->quote($from, 'text') . ' AND e.date <= ' . self::$DB->quote($to, 'text') . ') ' .
				'UNION ' .
				"SELECT b.id, CONCAT(b.date, ' 00:00:00') AS `date`, '' AS reminder_date, b.name AS subject, '' AS notes, 'bank_holiday' AS type, NULL AS type_id, " .
				'4 AS type_priority, NULL AS company_id, NULL AS post_id, NULL AS nbm_id, NULL AS client_id, NULL AS post_initiative_id, NULL AS initiative_id, ' .
				'false AS completed, NULL AS status_id, 0 AS rearranged_count ' .
				'FROM tbl_bank_holidays AS b ' .
				'WHERE (b.date >= ' . self::$DB->quote($from, 'text') . ' AND b.date <= ' . self::$DB->quote($to, 'text') . ') ' .
				'ORDER BY `completed`, `date`, `type_priority`';
		}

		return self::$DB->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	}

	public function findMeetingsByClient($client_id, $returnSqlOnly = false)
	{
		$date_floor = '2011-01-01 00:00:00';

		$rearranged_join = $this->rearrangedJoin($date_floor, '9999-12-31 23:59:59');

		$sql = 'SELECT m.id, m.date, ADDTIME(m.date, \'01:00:00\') AS end_date, m.reminder_date, CONCAT(m.client, \' / \', m.company) AS subject, m.notes, \'meeting\' AS type, m.status_id AS status_id, ' .
				'IFNULL(t1.rearranged_count, 0) AS rearranged_count, m.created_at, m.client_id, RIGHT(RAND(), 10) as random_number ' .
				'FROM vw_calendar_meetings AS m ' .
				'LEFT JOIN ' . $rearranged_join . ' ON m.id = t1.id ' .
				'WHERE m.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
				'AND m.date >= \'' . $date_floor . '\' ' .
				'ORDER BY m.date DESC LIMIT 15';

		return self::$DB->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	}



}

?>
