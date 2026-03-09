<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/ReaderMapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package ContinuityOnline
 */
class app_mapper_StatisticsReaderMapper extends app_mapper_ReaderMapper implements app_domain_StatisticsReaderReader
{
	protected $selectAllStmt;
	protected $selectByDateStmt;
	protected $selectByDateClientIdStmt;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select all: use UNION of existing views (vw_calendar does not exist in DB)
		$selectAllQuery = 'SELECT id, date, reminder_date, notes, \'meeting\' AS type FROM vw_calendar_meetings ' .
			'UNION ALL ' .
			'SELECT id, date, reminder_date, notes, \'info\' AS type FROM vw_calendar_information_requests';
		$this->selectAllStmt = self::$DB->prepare($selectAllQuery);
		
		$query = 	'SELECT id, date, reminder_date, notes, \'meeting\' AS type ' .
					'FROM tbl_meetings AS m ' .
					'WHERE (`date` >= :from AND `date` <= :to) ' .
//					'ORDER BY `date` ' .
					'UNION ' .
					'SELECT id, date, reminder_date, notes, \'info\' AS type ' .
					'FROM tbl_information_requests AS i ' .
					'WHERE (`date` >= :from AND `date` <= :to) ' .
					'ORDER BY `date`';
		$types = array('from' => 'text', 'to' => 'text');
		$this->selectByDateStmt = self::$DB->prepare($query, $types);
		
		// Select by date and client ID
		$query = 'SELECT id, date, reminder_date, notes, \'meeting\' AS type, client_id ' .
					'FROM vw_calendar_meetings AS m ' .
					'WHERE (`date` >= :from AND `date` <= :to) AND client_id = :client_id ' .
					'UNION ' .
					'SELECT id, date, reminder_date, notes, \'info\' AS type, client_id ' .
					'FROM vw_calendar_information_requests AS i ' .
					'WHERE (`date` >= :from AND `date` <= :to) AND client_id = :client_id ' .
					'ORDER BY `date`';
		$types = array('from' => 'text', 'to' => 'text', 'client_id' => 'integer');
		$this->selectByDateClientIdStmt = self::$DB->prepare($query, $types);
	}

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
	 * @param integer $user_id
	 * @param string $year_month
	 */
	public static function findCallsByUserIdAndYearMonth($user_id, $year_month)
	{
		$query = 'SELECT SUM(call_count) AS call_count, ' .
					'SUM(call_effective_count) AS call_effective_count, ' .
					'SUM(meeting_set_count) AS meeting_set_count ' .
					'FROM tbl_data_statistics AS ds ' .
					'WHERE ds.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND ds.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
					'GROUP BY ds.user_id, ds.`year_month`';
		return self::$DB->queryRow($query, null, MDB2_FETCHMODE_ASSOC);
	}

}

?>