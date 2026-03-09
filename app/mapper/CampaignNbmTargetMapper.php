<?php

/**
 * Defines the app_mapper_CampaignNbmTargetMapper class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package Alchemis
 */
class app_mapper_CampaignNbmTargetMapper extends app_mapper_Mapper implements app_domain_CampaignNbmTargetFinder
{
	protected static $DB;
	protected $selectStmt;
	protected $selectAllStmt;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT * FROM tbl_campaign_nbm_targets WHERE id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All
		$query = 'SELECT * FROM tbl_campaign_nbm_targets';
		$types = array();
		$this->selectAllStmt = self::$DB->prepare($query, $types);

	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_CampaignNbmTarget($array['id']);
		$obj->setUserId($array['user_id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setYearMonth($array['year_month']);
		$obj->setPlannedDays($array['planned_days']);
		$obj->setProjectManagementDays($array['project_management_days']);
		$obj->setEffectives($array['effectives']);
		$obj->setMeetingsSetImperative($array['meetings_set']);
		$obj->setMeetingsSet($array['meetings_set_imperative']);
		$obj->setMeetingsAttended($array['meetings_attended']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e.
	 * @return string
	 */
	protected function targetClass()
	{
		return 'app_domain_CampaignNbmTarget';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_campaign_nbm_targets');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_campaign_nbm_targets (id, user_id, campaign_id, `year_month`, planned_days, project_management_days, ' .
				'effectives, meetings_set, meetings_set_imperative, meetings_attended) VALUES ' .
				'(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

		$types = array('integer', 'integer', 'integer', 'text', 'integer', 'decimal', 'integer', 'integer', 'integer', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	$object->getId(),
						$object->getUserId(),
						$object->getCampaignId(),
						$object->getYearMonth(),
						$object->getPlannedDays(),
						$object->getProjectManagementDays(),
						$object->getEffectives(),
						$object->getMeetingsSet(),
						$object->getMeetingsSetImperative(),
 						$object->getMeetingsAttended());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_nbm_targets SET user_id = ?, campaign_id = ?, `year_month` = ?, planned_days = ?, ' .
				' project_management_days = ?,  effectives = ?,  meetings_set = ?,  meetings_set_imperative = ?, meetings_attended = ? ' .
				'WHERE id = ?';

		$types = array('integer', 'integer', 'text', 'integer', 'decimal', 'integer', 'integer', 'integer', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	$object->getUserId(),
						$object->getCampaignId(),
						$object->getYearMonth(),
						$object->getPlannedDays(),
						$object->getProjectManagementDays(),
						$object->getEffectives(),
						$object->getMeetingsSet(),
						$object->getMeetingsSetImperative(),
 						$object->getMeetingsAttended(),
 						$object->getId());
		$this->doStatement($updateStmt, $data);

	}


	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_campaign_nbm_targets WHERE id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($stmt, $data);
	}

	/**
	* @param integer $campaign_id the campaign
	*/
	public function findLatestTargetPeriodByCampaignIdAndUserId($campaign_id, $user_id)
	{
		$values = array($campaign_id, $user_id);
		$query = 'select max(`year_month`) from tbl_campaign_nbm_targets where campaign_id = ? and user_id = ?';
// 		echo $query;
		$types = array('integer', 'integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}

// 	/**
// 	 * Responsible for deleting any existing campaign nbm target periods and replacing with the latest campaign target periods
// 	 * @param integer $campaign_id
// 	 */
// 	public function copyCampaignTargetPeriodsToCampaignNbmTargets($campaign_id)
// 	{
// 		$query = 'DELETE FROM tbl_campaign_nbm_targets WHERE campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
// 		self::$DB->query($query);

// 		$query = 'INSERT INTO tbl_campaign_nbm_targets ( ' .
// 				'campaign_id, ' .
// 				'user_id, ' .
// 				'`year_month`) ' .
// 				'SELECT ' .
// 				'c.id, ' .
// 				'cn.user_id, ' .
// 				'ct.`year_month` ' .
// 				'FROM tbl_campaigns AS c ' .
// 				'JOIN tbl_campaign_targets AS ct ON ct.campaign_id = c.id ' .
// 				'JOIN tbl_campaign_nbms AS cn ON cn.campaign_id = c.id ' .
// 				'WHERE c.id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
// 				'AND cn.deactivated_date = \'0000-00-00\'';
// 		self::$DB->query($query);

// 		// update tbl_campaign_nbm_targets_seq sequence field and auto_increment value
// 		$table = 'tbl_campaign_nbm_targets';
// 		$item_count = self::$DB->queryOne('select max(id) from ' . $table);

// 		$table .= '_seq';
// 		self::$DB->query('DELETE FROM ' . $table);
// 		self::$DB->query('insert into ' . $table . ' (sequence) values (' . $item_count . ')');
// 		self::$DB->query('ALTER TABLE ' . $table . ' AUTO_INCREMENT = '. $item_count +1);

// 		return true;
// 	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$values = array($id);

		// Returns an MDB2_Result object
		$result = $this->doStatement($this->selectStmt, $values);

		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

	/**
	 * Find all campaign_nbms.
	 * @return app_mapper_CampaignNbmCollection collection of app_domain_CampaignNbm objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_NbmCampaignTargetCollection($result, $this);
	}


	/**
	 * Find all nbm campaign targets and statistics.
	 * @return raw array
	 */
	public function findByCampaignIdUserIdAndYearMonth($campaign_id, $user_id, $year_month)
	{
		$values = array($campaign_id, $user_id, $year_month);
		$query = 'SELECT * FROM tbl_campaign_nbm_targets ' .
				'WHERE campaign_id = ? ' .
				'and user_id = ? ' .
				'and `year_month` = ?';
		$types = array('integer', 'integer' , 'text');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return $this->load($result);
	}

   /**
     * Find max year_month for each current campaign
     * @return raw array
     */
    public function findMaxYearMonthByUserId($user_id)
    {
//        $values = array($user_id);
        $query = 'SELECT max(camt.`year_month`) as `max_year_month`, camt.campaign_id ' .
                'FROM tbl_campaign_nbm_targets camt ' .
                'JOIN tbl_campaigns cam on cam.id = camt.campaign_id ' .
                'JOIN tbl_clients cl on cl.id = cam.client_id ' .
                'WHERE user_id = ' .self::$DB->quote($user_id, 'integer') . ' ' .
                'AND cl.is_current = 1 ' .
                'GROUP BY camt.campaign_id';

//        echo $query;

        $result = self::$DB->query($query);
        return self::mdb2ResultToArray($result);

//        $types = array('integer');
//        $stmt = self::$DB->prepare($query, $types);
//        $result = $this->doStatement($stmt, $values);
//        return $this->load($result);
    }

	/**
	 * Find all nbm campaign targets and statistics.
	 * @param integer $id
	 * @return raw array
	 */
	public function findStatisticsById($id)
	{
		$query = 'SELECT ' .
				'c.id AS campaign_id, cl.name AS client_name, ' .
				'SUM(cnt.planned_days) AS planned_days, ' .
				'SUM(cnt.project_management_days) AS project_management_days, ' .
				'SUM(cnt.effectives) AS effectives_target, ' .
				'SUM(cnt.meetings_set) AS meetings_set_target, ' .
				'SUM(cnt.meetings_set_imperative) AS meetings_set_imperative_target, ' .
				'SUM(cnt.meetings_attended) AS meetings_attended_target, ' .
				'IFNULL(SUM(ds.campaign_current_month), 0) AS campaign_current_month,' .
				'IFNULL(SUM(ds.campaign_monthly_fee), 0) AS campaign_monthly_fee,' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date), 0) AS campaign_meeting_set_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date) - SUM(ds.campaign_meeting_set_count_to_date), 0) AS standard_campaign_meeting_set_target,' .
				'IFNULL(SUM(ds.campaign_meeting_set_count_to_date), 0) AS campaign_meeting_set_to_date_count,' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date), 0) AS campaign_meeting_category_attended_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date) - SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS standard_campaign_meeting_category_attended_target, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS campaign_meeting_category_attended_to_date_count, ' .
				'IFNULL(ROUND(SUM(ds.call_effective_count) / 10), 1) AS call_days_actual, ' .
				'IFNULL(SUM(ds.call_effective_count)-SUM(ds.call_ote_count), 0) AS offte, ' .
				'IFNULL(SUM(ds.call_ote_count), 0) AS ote, ' .
				'IFNULL(SUM(ds.call_count), 0) AS call_count, ' .
				'IFNULL(SUM(ds.call_effective_count), 0) AS effectives, ' .
				'IFNULL(SUM(ds.meeting_set_count), 0) AS meetings_set, ' .
				'IFNULL(SUM(ds.meeting_time_lag_0_3), 0) AS meeting_time_lag_0_3, ' .
				'IFNULL(SUM(ds.meeting_time_lag_3_5), 0) AS meeting_time_lag_3_5, ' .
				'IFNULL(SUM(ds.meeting_time_lag_5_7), 0) AS meeting_time_lag_5_7, ' .
				'IFNULL(SUM(ds.meeting_time_lag_7_), 0) AS meeting_time_lag_7_, ' .
				'IFNULL(SUM(ds.meeting_in_diary_this_month_count), 0) AS meetings_in_diary_this_month, ' .
				'IFNULL(SUM(ds.meeting_category_attended_count), 0) AS meeting_category_attended_count, ' .
				'IF(SUM(ds.call_effective_count), ROUND((SUM(ds.meeting_set_count) / SUM(ds.call_effective_count)) * 100), 0) AS conversion_rate, ' .
				'IF(SUM(ds.call_count), ROUND((SUM(ds.call_effective_count) / SUM(ds.call_count)) * 100), 0) AS access_rate ' .
				'FROM tbl_campaign_nbm_targets AS cnt ' .
				'JOIN tbl_campaigns AS c ON c.id = cnt.campaign_id ' .
				'JOIN tbl_clients AS cl ON cl.id = c.client_id ' .
		        'LEFT JOIN tbl_data_statistics AS ds ON cnt.user_id = ds.user_id AND cnt.campaign_id = ds.campaign_id ' .
				'AND cnt.`year_month` = ds.`year_month` ' .
				'WHERE cnt.id = ' . self::$DB->quote($id, 'integer') . ' ' .
				'GROUP BY cnt.id';
//		echo $query;

		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find all nbm campaign targets and statistics.
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return raw array
	 */
	public function findStatisticsByUserIdAndYearMonth($user_id, $year_month)
	{
		$query = 'SELECT ' .
				'c.id AS campaign_id, cl.name AS client_name, ' .
				'SUM(cnt.planned_days) AS planned_days, ' .
				'SUM(cnt.project_management_days) AS project_management_days, ' .
				'SUM(cnt.effectives) AS effectives_target, ' .
				'SUM(cnt.meetings_set) AS meetings_set_target, ' .
				'SUM(cnt.meetings_set_imperative) AS meetings_set_imperative_target, ' .
				'SUM(cnt.meetings_attended) AS meetings_attended_target, ' .
				'IFNULL(SUM(ds.campaign_current_month), 0) AS campaign_current_month,' .
				'IFNULL(SUM(ds.campaign_monthly_fee), 0) AS campaign_monthly_fee,' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date), 0) AS campaign_meeting_set_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date) - SUM(ds.campaign_meeting_set_count_to_date), 0) AS standard_campaign_meeting_set_target,' .
				'IFNULL(SUM(ds.campaign_meeting_set_count_to_date), 0) AS campaign_meeting_set_to_date_count,' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date), 0) AS campaign_meeting_category_attended_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date) - SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS standard_campaign_meeting_category_attended_target, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS campaign_meeting_category_attended_to_date_count, ' .
				'IFNULL(ROUND(SUM(ds.call_effective_count) / 10), 1) AS call_days_actual, ' .
				'IFNULL(SUM(ds.call_effective_count)-SUM(ds.call_ote_count), 0) AS offte, ' .
				'IFNULL(SUM(ds.call_ote_count), 0) AS ote, ' .
				'IFNULL(SUM(ds.call_count), 0) AS call_count, ' .
				'IFNULL(SUM(ds.call_effective_count), 0) AS effectives, ' .
				'IFNULL(SUM(ds.meeting_set_count), 0) AS meetings_set, ' .
				'IFNULL(SUM(ds.meeting_time_lag_0_3), 0) AS meeting_time_lag_0_3, ' .
				'IFNULL(SUM(ds.meeting_time_lag_3_5), 0) AS meeting_time_lag_3_5, ' .
				'IFNULL(SUM(ds.meeting_time_lag_5_7), 0) AS meeting_time_lag_5_7, ' .
				'IFNULL(SUM(ds.meeting_time_lag_7_), 0) AS meeting_time_lag_7_, ' .
				'IFNULL(SUM(ds.meeting_in_diary_this_month_count), 0) AS meetings_in_diary_this_month, ' .
				'IFNULL(SUM(ds.meeting_category_attended_count), 0) AS meeting_category_attended_count, ' .
				'IF(SUM(ds.call_effective_count), ROUND((SUM(ds.meeting_set_count) / SUM(ds.call_effective_count)) * 100), 0) AS conversion_rate, ' .
				'IF(SUM(ds.call_count), ROUND((SUM(ds.call_effective_count) / SUM(ds.call_count)) * 100), 0) AS access_rate ' .
				'FROM tbl_campaign_nbm_targets AS cnt ' .
				'JOIN tbl_campaigns AS c ON c.id = cnt.campaign_id ' .
				'JOIN tbl_clients AS cl ON cl.id = c.client_id ' .
				'LEFT JOIN tbl_data_statistics AS ds ON cnt.user_id = ds.user_id AND cnt.campaign_id = ds.campaign_id ' .
				'AND cnt.`year_month` = ds.`year_month` ' .
				'WHERE cnt.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND cnt.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
				'GROUP BY cnt.campaign_id ' .
				'ORDER BY cl.name';
//		echo "<P>$query</P>";
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find all nbm campaign targets and statistics.where effectives and meetings targets are both zero
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return raw array
	 */
	public function findStatisticsZeroTargetsByUserIdAndYearMonth($user_id, $year_month)
	{
		$query = 'SELECT ' .
				'c.id AS campaign_id, cl.name AS client_name, ' .
				'SUM(cnt.planned_days) AS planned_days, ' .
				'SUM(cnt.project_management_days) AS project_management_days, ' .
				'SUM(cnt.effectives) AS effectives_target, ' .
				'SUM(cnt.meetings_set) AS meetings_set_target, ' .
				'SUM(cnt.meetings_set_imperative) AS meetings_set_imperative_target, ' .
				'SUM(cnt.meetings_attended) AS meetings_attended_target, ' .
				'IFNULL(SUM(ds.campaign_current_month), 0) AS campaign_current_month,' .
				'IFNULL(SUM(ds.campaign_monthly_fee), 0) AS campaign_monthly_fee,' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date), 0) AS campaign_meeting_set_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date) - SUM(ds.campaign_meeting_set_count_to_date), 0) AS standard_campaign_meeting_set_target,' .
				'IFNULL(SUM(ds.campaign_meeting_set_count_to_date), 0) AS campaign_meeting_set_to_date_count,' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date), 0) AS campaign_meeting_category_attended_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date) - SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS standard_campaign_meeting_category_attended_target, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS campaign_meeting_category_attended_to_date_count, ' .
				'IFNULL(ROUND(SUM(ds.call_effective_count) / 10), 1) AS call_days_actual, ' .
				'IFNULL(SUM(ds.call_effective_count)-SUM(ds.call_ote_count), 0) AS offte, ' .
				'IFNULL(SUM(ds.call_ote_count), 0) AS ote, ' .
				'IFNULL(SUM(ds.call_count), 0) AS call_count, ' .
				'IFNULL(SUM(ds.call_effective_count), 0) AS effectives, ' .
				'IFNULL(SUM(ds.meeting_set_count), 0) AS meetings_set, ' .
				'IFNULL(SUM(ds.meeting_time_lag_0_3), 0) AS meeting_time_lag_0_3, ' .
				'IFNULL(SUM(ds.meeting_time_lag_3_5), 0) AS meeting_time_lag_3_5, ' .
				'IFNULL(SUM(ds.meeting_time_lag_5_7), 0) AS meeting_time_lag_5_7, ' .
				'IFNULL(SUM(ds.meeting_time_lag_7_), 0) AS meeting_time_lag_7_, ' .
				'IFNULL(SUM(ds.meeting_in_diary_this_month_count), 0) AS meetings_in_diary_this_month, ' .
				'IFNULL(SUM(ds.meeting_category_attended_count), 0) AS meeting_category_attended_count, ' .
				'IF(SUM(ds.call_effective_count), ROUND((SUM(ds.meeting_set_count) / SUM(ds.call_effective_count)) * 100), 0) AS conversion_rate, ' .
				'IF(SUM(ds.call_count), ROUND((SUM(ds.call_effective_count) / SUM(ds.call_count)) * 100), 0) AS access_rate ' .
				'FROM tbl_campaign_nbm_targets AS cnt ' .
				'JOIN tbl_campaigns AS c ON c.id = cnt.campaign_id ' .
		        'JOIN tbl_campaign_nbms AS cn ON cn.campaign_id = cnt.campaign_id AND cn.user_id = cnt.user_id ' .
		        'JOIN tbl_clients AS cl ON cl.id = c.client_id ' .
				'LEFT JOIN tbl_data_statistics AS ds ON cnt.user_id = ds.user_id AND cnt.campaign_id = ds.campaign_id ' .
				'AND cnt.`year_month` = ds.`year_month` ' .
				'WHERE cnt.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND cnt.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
				'AND (cnt.effectives = 0 ' .
		 		'OR cnt.meetings_set = 0) ' .
		        'AND cl.is_current = 1 ' . // added 22/02/2010 - DMC - DN confirmed he would only remove mark clients as non-current once there all data was no longer being displayed on the monthly planner
                'AND (cn.deactivated_date >= ' . self::$DB->quote(substr($year_month, 0, 4) . '-' . substr($year_month, 4,2) . '-01', 'text')  . ' ' .
                'OR cn.deactivated_date = \'0000-00-00\') ' .
				'GROUP BY cnt.campaign_id ' .
				'ORDER BY cl.name';


		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find all nbm campaign targets and statistics.where effectives and meetings targets are both zero
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return raw array
	 */
	public function findStatisticsNonZeroTargetsByUserIdAndYearMonth($user_id, $year_month)
	{
		$query = 'SELECT ' .
				'c.id AS campaign_id, cl.name AS client_name, ' .
				'SUM(cnt.planned_days) AS planned_days, ' .
				'SUM(cnt.project_management_days) AS project_management_days, ' .
				'SUM(cnt.effectives) AS effectives_target, ' .
				'SUM(cnt.meetings_set) AS meetings_set_target, ' .
				'SUM(cnt.meetings_set_imperative) AS meetings_set_imperative_target, ' .
				'SUM(cnt.meetings_attended) AS meetings_attended_target, ' .
				'IFNULL(SUM(ds.campaign_current_month), 0) AS campaign_current_month,' .
				'IFNULL(SUM(ds.campaign_monthly_fee), 0) AS campaign_monthly_fee,' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date), 0) AS campaign_meeting_set_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date) - SUM(ds.campaign_meeting_set_count_to_date), 0) AS standard_campaign_meeting_set_target,' .
				'IFNULL(SUM(ds.campaign_meeting_set_count_to_date), 0) AS campaign_meeting_set_to_date_count,' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date), 0) AS campaign_meeting_category_attended_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date) - SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS standard_campaign_meeting_category_attended_target, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS campaign_meeting_category_attended_to_date_count, ' .
				'IFNULL(ROUND(SUM(ds.call_effective_count) / 10), 1) AS call_days_actual, ' .
				'IFNULL(SUM(ds.call_effective_count)-SUM(ds.call_ote_count), 0) AS offte, ' .
				'IFNULL(SUM(ds.call_ote_count), 0) AS ote, ' .
				'IFNULL(SUM(ds.call_count), 0) AS call_count, ' .
				'IFNULL(SUM(ds.call_effective_count), 0) AS effectives, ' .
				'IFNULL(SUM(ds.meeting_set_count), 0) AS meetings_set, ' .
				'IFNULL(SUM(ds.meeting_time_lag_0_3), 0) AS meeting_time_lag_0_3, ' .
				'IFNULL(SUM(ds.meeting_time_lag_3_5), 0) AS meeting_time_lag_3_5, ' .
				'IFNULL(SUM(ds.meeting_time_lag_5_7), 0) AS meeting_time_lag_5_7, ' .
				'IFNULL(SUM(ds.meeting_time_lag_7_), 0) AS meeting_time_lag_7_, ' .
				'IFNULL(SUM(ds.meeting_in_diary_this_month_count), 0) AS meetings_in_diary_this_month, ' .
				'IFNULL(SUM(ds.meeting_category_attended_count), 0) AS meeting_category_attended_count, ' .
				'IF(SUM(ds.call_effective_count), ROUND((SUM(ds.meeting_set_count) / SUM(ds.call_effective_count)) * 100), 0) AS conversion_rate, ' .
				'IF(SUM(ds.call_count), ROUND((SUM(ds.call_effective_count) / SUM(ds.call_count)) * 100), 0) AS access_rate ' .
				'FROM tbl_campaign_nbm_targets AS cnt ' .
				'JOIN tbl_campaigns AS c ON c.id = cnt.campaign_id ' .
		        'JOIN tbl_campaign_nbms AS cn ON cn.campaign_id = cnt.campaign_id AND cn.user_id = cnt.user_id ' .
		        'JOIN tbl_clients AS cl ON cl.id = c.client_id ' .
				'LEFT JOIN tbl_data_statistics AS ds ON cnt.user_id = ds.user_id AND cnt.campaign_id = ds.campaign_id ' .
				'AND cnt.`year_month` = ds.`year_month` ' .
				'WHERE cnt.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND cnt.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
				'AND cnt.effectives > 0 ' .
		 		'AND cnt.meetings_set > 0 ' .
                'AND (cn.deactivated_date >= ' . self::$DB->quote(substr($year_month, 0, 4) . '-' . substr($year_month, 4,2) . '-01', 'text')  . ' ' .
                'OR cn.deactivated_date = \'0000-00-00\') ' .
				'GROUP BY cnt.campaign_id ' .
				'ORDER BY cl.name';
//		echo "<P>$query</P>";
if(($_GET['Monthly'] ?? null) == 3){
	echo '<pre>';
			echo "<P>$query</P>";
	echo '</pre>';
}
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find all nbm campaign targets and statistics.
	 * @param integer $user_id
	 * @param string $year_month in the format 'YYYYMM'
	 * @return raw array
	 */
	public function findTotalStatisticsByUserIdAndYearMonth($user_id, $year_month)
	{
		$query = 'SELECT ' .
				'c.id AS campaign_id, cl.name AS client_name, ' .
				'SUM(cnt.planned_days) AS planned_days, ' .
				'SUM(cnt.project_management_days) AS project_management_days, ' .
				'SUM(cnt.effectives) AS effectives_target, ' .
				'SUM(cnt.meetings_set) AS meetings_set_target, ' .
				'SUM(cnt.meetings_set_imperative) AS meetings_set_imperative_target, ' .
				'SUM(cnt.meetings_attended) AS meetings_attended_target, ' .
				'IFNULL(SUM(ds.campaign_current_month), 0) AS campaign_current_month,' .
				'IFNULL(SUM(ds.campaign_monthly_fee), 0) AS campaign_monthly_fee,' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date), 0) AS campaign_meeting_set_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_set_target_to_date) - SUM(ds.campaign_meeting_set_count_to_date), 0) AS standard_campaign_meeting_set_target,' .
				'IFNULL(SUM(ds.campaign_meeting_set_count_to_date), 0) AS campaign_meeting_set_to_date_count,' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date), 0) AS campaign_meeting_category_attended_target_to_date, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_target_to_date) - SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS standard_campaign_meeting_category_attended_target, ' .
				'IFNULL(SUM(ds.campaign_meeting_category_attended_count_to_date), 0) AS campaign_meeting_category_attended_to_date_count, ' .
				'IFNULL(ROUND(SUM(ds.call_effective_count) / 10), 1) AS call_days_actual, ' .
				'IFNULL(SUM(ds.call_effective_count)-SUM(ds.call_ote_count), 0) AS offte, ' .
				'IFNULL(SUM(ds.call_ote_count), 0) AS ote, ' .
				'IFNULL(SUM(ds.call_count), 0) AS call_count, ' .
				'IFNULL(SUM(ds.call_effective_count), 0) AS effectives, ' .
				'IFNULL(SUM(ds.meeting_set_count), 0) AS meetings_set, ' .
				'IFNULL(SUM(ds.meeting_time_lag_0_3), 0) AS meeting_time_lag_0_3, ' .
				'IFNULL(SUM(ds.meeting_time_lag_3_5), 0) AS meeting_time_lag_3_5, ' .
				'IFNULL(SUM(ds.meeting_time_lag_5_7), 0) AS meeting_time_lag_5_7, ' .
				'IFNULL(SUM(ds.meeting_time_lag_7_), 0) AS meeting_time_lag_7_, ' .
				'IFNULL(SUM(ds.meeting_in_diary_this_month_count), 0) AS meetings_in_diary_this_month, ' .
				'IFNULL(SUM(ds.meeting_category_attended_count), 0) AS meeting_category_attended_count, ' .
				'IF(SUM(ds.call_effective_count), ROUND((SUM(ds.meeting_set_count) / SUM(ds.call_effective_count)) * 100), 0) AS conversion_rate, ' .
				'IF(SUM(ds.call_count), ROUND((SUM(ds.call_effective_count) / SUM(ds.call_count)) * 100), 0) AS access_rate ' .
				'FROM tbl_campaign_nbm_targets AS cnt ' .
				'JOIN tbl_campaigns AS c ON c.id = cnt.campaign_id ' .
		        'JOIN tbl_campaign_nbms AS cn ON cn.campaign_id = cnt.campaign_id AND cn.user_id = cnt.user_id ' .
				'JOIN tbl_clients AS cl ON cl.id = c.client_id ' .
				'LEFT JOIN tbl_data_statistics AS ds ON cnt.user_id = ds.user_id AND cnt.campaign_id = ds.campaign_id ' .
				'AND cnt.`year_month` = ds.`year_month` ' .
				'where cnt.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'and cnt.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
		        'AND (cn.deactivated_date >= ' . self::$DB->quote(substr($year_month, 0, 4) . '-' . substr($year_month, 4,2) . '-01', 'text')  . ' ' .
		        'OR cn.deactivated_date = \'0000-00-00\') ' .
				'group by cnt.user_id, cnt.`year_month`';
//		echo "<P>$query</P>$year_month" . "01 00:00:00";
		$row = self::$DB->queryRow($query, null, MDB2_FETCHMODE_ASSOC);
		if (is_array($row)) {
			if (isset($row['call_days_actual']) && is_array($row['call_days_actual'])) {
				$row['call_days_actual'] = 0;
			}
			if (isset($row['project_management_days']) && is_array($row['project_management_days'])) {
				$row['project_management_days'] = 0;
			}
		}
		return $row;
	}

   /**
     * Find project management days by nbm and year_month
     * @return raw array
     */
    public function findTotalProjectManagementDaysByUserIdAndYearMonth($user_id, $year_month)
    {
        $values = array($user_id, $year_month);
        $query = 'SELECT sum(project_management_days) FROM tbl_campaign_nbm_targets ' .
                'WHERE user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
                'and `year_month` = ' . self::$DB->quote($year_month, 'text');
//        $types = array('integer' , 'text');
//        $stmt = self::$DB->prepare($query, $types);
//        $result = $this->doStatement($stmt, $values);
        return self::$DB->queryOne($query);
//        return $this->load($result);
    }

}

?>