<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 *
 */
class app_mapper_CampaignMapper extends app_mapper_Mapper implements app_domain_CampaignFinder
{
	protected static $DB;
	protected $selectAllStmt;
	protected $selectStmt;
	protected $id;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$query = 'SELECT C.*, CL.name AS client_name ' .
					'FROM tbl_campaigns C ' .
					'INNER JOIN tbl_clients CL ON C.client_id = CL.id ' .
					'ORDER BY CL.name';
		$this->selectAllStmt = self::$DB->prepare($query);

		// Select single
		$query = 'SELECT c.*, cl.name AS client_name, ' .
					'lkp_cbt.description as billing_terms, lkp_cpt.description as payment_terms, ' .
					'lkp_cpm.description as payment_method, lkp_ct.description as type_name, ' .
					'ru.name as created_by_name ' .
					'FROM tbl_campaigns c ' .
					'LEFT JOIN tbl_clients cl ON c.client_id = cl.id ' .
					'LEFT JOIN tbl_lkp_campaign_types lkp_ct ON lkp_ct.id = c.type_id ' .
					'LEFT JOIN tbl_lkp_campaign_billing_terms lkp_cbt ON lkp_cbt.id = c.billing_terms_id ' .
					'LEFT JOIN tbl_lkp_campaign_payment_terms lkp_cpt ON lkp_cpt.id = c.payment_terms_id ' .
					'LEFT JOIN tbl_lkp_campaign_payment_methods lkp_cpm ON lkp_cpm.id = c.payment_method_id ' .
					'LEFT JOIN tbl_rbac_users ru ON ru.id = c.created_by ' .
					'WHERE c.id = :id';


		$types = array('id' => 'integer');
		$this->selectStmt = self::$DB->prepare($query, $types);


	}

		/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Campaign($array['id']);
		$obj->setClientId($array['client_id']);
		$obj->setClientName($array['client_name']);
		$obj->setTypeId($array['type_id']);
		$obj->setTypeName($array['type_name']);
		$obj->setStartYearMonth($array['start_year_month']);
		$obj->setEndYearMonth($array['end_year_month']);
		$obj->setInitialFee($array['initial_fee']);
		$obj->setCurrentFee($array['current_fee']);
		$obj->setContractSentDate($array['contract_sent_date']);
		$obj->setContractReceivedDate($array['contract_received_date']);
		$obj->setSoFormReceivedDate($array['so_form_received_date']);
		$obj->setBillingTermsId($array['billing_terms_id']);
		$obj->setBillingTerms($array['billing_terms']);
		$obj->setPaymentTermsId($array['payment_terms_id']);
		$obj->setPaymentTerms($array['payment_terms']);
		$obj->setPaymentMethodId($array['payment_method_id']);
		$obj->setPaymentMethod($array['payment_method']);
		$obj->setMinimumDuration($array['minimum_duration']);
		$obj->setNoticePeriod($array['notice_period']);
		$obj->setNoticeDate($array['notice_date']);
		$obj->setAdditionalTermsExist($array['additional_terms_exist']);
		$obj->setCreatedAt($array['created_at']);
		$obj->setCreatedBy($array['created_by']);
		$obj->setCreatedByName($array['created_by_name']);
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
		return 'app_domain_Campaign';
	}


	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_campaigns');
		return $this->id;
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{

		// Insert
		$query = 'INSERT INTO tbl_campaigns ' .
				'(id, client_id, type_id, ' .
				'start_year_month, end_year_month, initial_fee, ' .
				'current_fee, contract_sent_date, contract_received_date, ' .
				'so_form_received_date, billing_terms_id, payment_terms_id, ' .
				'payment_method_id, minimum_duration, notice_period, ' .
				'notice_date, additional_terms_exist, created_at, ' .
				'created_by) ' .
				'VALUES ' .
				'(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'integer',
						'text', 'text', 'integer',
						'integer','date', 'date',
						'date', 'integer', 'integer',
						'integer', 'integer', 'integer',
						'date',	'integer', 'date',
						'integer');
		$result_types = MDB2_PREPARE_MANIP;
		$insertStmt = self::$DB->prepare($query, $types, $result_types);

		$values = array($object->getId(), $object->getClientId(), $object->getTypeId(),
						$object->getStartYearMonth(), $object->getEndYearMonth(),  $object->getInitialFee(),
						$object->getCurrentFee(), $object->getContractSentDate(), $object->getContractReceivedDate(),
						$object->getSoFormReceivedDate(), $object->getBillingTermsId(), $object->getPaymentTermsId(),
						$object->getPaymentMethodId(), $object->getMinimumDuration(), $object->getNoticePeriod(),
						$object->getNoticeDate(), $object->getAdditionalTermsExist(), $object->getCreatedAt(),
						$object->getCreatedBy());

		try
		{
			$this->doStatement($insertStmt, $values);
		}
		catch (app_base_MDB2Exception $e)
		{
			// do nothing
		}
	}


	/**
	 * @param app_domain_DomainObject $object
	 */
	public function update(app_domain_DomainObject $object)
	{
		$query = 	'UPDATE tbl_campaigns ' .
					'SET ' .
					'client_id = ?, type_id = ?, start_year_month = ?, ' .
					'end_year_month = ?, initial_fee = ?, current_fee = ?, ' .
					'contract_sent_date = ?, contract_received_date = ?, so_form_received_date = ?, ' .
					'billing_terms_id = ?, payment_terms_id = ?, payment_method_id = ?, ' .
					'minimum_duration = ?, notice_period = ?, notice_date = ?, ' .
					'additional_terms_exist = ?, created_at = ?, created_by = ? ' .
					'WHERE id = ?';
		$types = array('integer', 'integer', 'text',
						'text', 'integer', 'integer',
						'date', 'date',	'date',
						'integer', 'integer', 'integer',
						'integer', 'integer', 'date',
						'integer', 'date', 'integer',
						'integer');
		$updateStmt = self::$DB->prepare($query, $types);
		$values = array($object->getClientId(), $object->getTypeId(), $object->getStartYearMonth(),
						$object->getEndYearMonth(),  $object->getInitialFee(), $object->getCurrentFee(),
						$object->getContractSentDate(), $object->getContractReceivedDate(),	$object->getSoFormReceivedDate(),
						$object->getBillingTermsId(), $object->getPaymentTermsId(),	$object->getPaymentMethodId(),
						$object->getMinimumDuration(), $object->getNoticePeriod(), $object->getNoticeDate(),
						$object->getAdditionalTermsExist(), $object->getCreatedAt(), $object->getCreatedBy(),
						$object->getId());
		$this->doStatement($updateStmt, $values);

	}


	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see load()
	 */
	public function doFind($id)
	{
		$values = array('id' => $id);
		// factor this out

		// Returns an MDB2_Result object
		$result = $this->doStatement($this->selectStmt, $values);

		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}


	/**
	 *
	 * @return app_mapper_CampaignCollection
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_CampaignCollection($result, $this);
	}

	/**
	 *
	 * @param integer $clientId the client ID
	 */
	public function findByClientId($client_id)
	{
		$query = 'SELECT c.*, cl.name AS client_name, ' .
					'lkp_cbt.description as billing_terms, lkp_cpt.description as payment_terms, ' .
					'lkp_cpm.description as payment_method, lkp_ct.description as type_name, ' .
					'ru.name as created_by_name ' .
					'FROM tbl_campaigns c ' .
					'LEFT JOIN tbl_clients cl ON c.client_id = cl.id ' .
					'LEFT JOIN tbl_lkp_campaign_types lkp_ct ON lkp_ct.id = c.type_id ' .
					'LEFT JOIN tbl_lkp_campaign_billing_terms lkp_cbt ON lkp_cbt.id = c.billing_terms_id ' .
					'LEFT JOIN tbl_lkp_campaign_payment_terms lkp_cpt ON lkp_cpt.id = c.payment_terms_id ' .
					'LEFT JOIN tbl_lkp_campaign_payment_methods lkp_cpm ON lkp_cpm.id = c.payment_method_id ' .
					'LEFT JOIN tbl_rbac_users ru ON ru.id = c.created_by ' .
					'WHERE c.client_id = ' . self::$DB->quote($client_id, 'integer');

		$result = self::$DB->query($query);

		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
		
	}

	/**
	 * @param integer $user_id
	 */
	public function findByUserId($user_id)
	{
		$query = 	'SELECT c.*, cl.name AS client_name, ' .
					'lkp_cbt.description as billing_terms, lkp_cpt.description as payment_terms, ' .
					'lkp_cpm.description as payment_method, lkp_ct.description as type_name, ' .
					'ru.name as created_by_name ' .
					'FROM tbl_campaigns c ' .
					'INNER JOIN tbl_clients cl ON c.client_id = cl.id ' .
					'LEFT JOIN tbl_lkp_campaign_types lkp_ct ON lkp_ct.id = c.type_id ' .
					'LEFT JOIN tbl_lkp_campaign_billing_terms lkp_cbt ON lkp_cbt.id = c.billing_terms_id ' .
					'LEFT JOIN tbl_lkp_campaign_payment_terms lkp_cpt ON lkp_cpt.id = c.payment_terms_id ' .
					'LEFT JOIN tbl_lkp_campaign_payment_methods lkp_cpm ON lkp_cpm.id = c.payment_method_id ' .
					'LEFT JOIN tbl_rbac_users ru ON ru.id = c.created_by ' .
					'LEFT JOIN tbl_campaign_nbms cn ON c.id = cn.campaign_id ' .
					'WHERE cn.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'ORDER BY cl.name';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CampaignCollection($result, $this);
	}


	/**
	 * find campaigns available to the current user
	 */
	public function findByCurrentUserId()
	{

		return $this->findByUserId(self::getCurrentUserId());
	}

	/**
	 * @param integer $campaign_id the campaign
	 */
	public function findLatestTargetPeriodByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'select max(`year_month`) from tbl_campaign_targets where campaign_id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find the progress of campaigns associated with a user.
	 * @param integer $user_id
	 * @return array
	 */
	public function findProgressByUserId($user_id)
	{
		// Prefer current month; if no data, try previous month; then try latest year_month in DB.
		$year_month = date('Ym', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
		$ids = $this->fetchProgressIdsForUserAndMonth($user_id, $year_month);
		if (empty($ids)) {
			$year_month_prev = date('Ym', strtotime('first day of last month'));
			$ids = $this->fetchProgressIdsForUserAndMonth($user_id, $year_month_prev);
		}
		if (empty($ids)) {
			$year_month_latest = $this->fetchLatestYearMonthInStatistics();
			if ($year_month_latest !== '') {
				$ids = $this->fetchProgressIdsForUserAndMonth($user_id, $year_month_latest);
			}
		}
		if (empty($ids)) {
			return array();
		}

		$rows = $this->fetchProgressRowsByIds($ids, true);
		// If no rows with is_current=1 (e.g. staging data), show anyway without filter so data appears like on live
		if (empty($rows)) {
			$rows = $this->fetchProgressRowsByIds($ids, false);
		}
		return $rows;
	}

	/**
	 * Run the main campaign progress SELECT for given ds.id list.
	 * @param string $ids comma-separated ds.id values
	 * @param bool $onlyCurrentClient if true, add AND cli.is_current = 1
	 * @return array
	 */
	protected function fetchProgressRowsByIds($ids, $onlyCurrentClient = true)
	{
		$query = 'SELECT ds.id, ds.campaign_id, cli.name AS campaign_name, cli.id AS client_id, ds.user_id, ds.`year_month`, ' .
					'ds.campaign_current_month, ds.campaign_meeting_set_target, ' .
					'ds.campaign_meeting_set_target_to_date, ds.campaign_meeting_set_count_to_date, ' .
					'ds.campaign_meeting_category_attended_target_to_date, ds.campaign_meeting_category_attended_count_to_date, ' .
					'ds.meeting_in_diary_this_month_count ' .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
					'WHERE ds.id IN (' . $ids . ') ';
		if ($onlyCurrentClient) {
			$query .= 'AND cli.is_current = 1 ';
		}
		$query .= 'ORDER BY cli.name';
		$result = self::$DB->query($query);
		if (MDB2::isError($result)) {
			return array();
		}
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Fetch comma-separated ds.id values for campaign progress (first query).
	 * @param int $user_id
	 * @param string $year_month e.g. 202603
	 * @return string comma-separated ids or empty string
	 */
	protected function fetchProgressIdsForUserAndMonth($user_id, $year_month)
	{
		$query = 'SELECT MAX(ds.id) AS id FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaign_nbms AS cam ON ds.campaign_id = cam.campaign_id ' .
					'WHERE ds.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
					'AND cam.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'GROUP BY ds.campaign_id';
		$result = self::$DB->query($query);
		if (MDB2::isError($result)) {
			return '';
		}
		$array = $result->fetchCol();
		if (!is_array($array)) {
			$array = array();
		}
		return implode(',', $array);
	}

	/**
	 * Get the latest year_month present in tbl_data_statistics (fallback when current/prev month have no data).
	 * Uses backticks for year_month for MySQL reserved-word safety.
	 * @return string e.g. 203412 or empty string
	 */
	protected function fetchLatestYearMonthInStatistics()
	{
		$query = 'SELECT MAX(`year_month`) FROM tbl_data_statistics';
		$result = self::$DB->query($query);
		if (MDB2::isError($result)) {
			return '';
		}
		$row = $result->fetchOne();
		return ($row !== null && $row !== '') ? (string) $row : '';
	}

	/**
	 * Find the list of marketing services from tbl_tiered_characteristics.
	 * @return array
	 */
	public function findAllDisciplines()
	{
		$query = 'SELECT * ' .
				'FROM tbl_tiered_characteristics AS tc ' .
				'WHERE tc.parent_id =  18 ' .
				'ORDER BY tc.value';

		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find the list of marketing services for this campaign (ie assigned to this campaign).
	 * @param integer $campaign_id
	 * @return array
	 */
	public function findDisciplines($campaign_id)
	{

		$query = 'SELECT tc.id, tc.value ' .
				'FROM tbl_tiered_characteristics AS tc ' .
				'JOIN tbl_campaign_disciplines AS cd ON cd.tiered_characteristic_id = tc.id ' .
				'WHERE cd.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'ORDER BY tc.value';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find the list of marketing services for the parent campaign of this initiative
	 * (ie assigned to the parent campaign).
	 * @param integer $initiative_id
	 * @return array
	 */
	public function findDisciplinesByInitiativeId($initiative_id)
	{
		$query = 'SELECT i.campaign_id ' .
				'FROM tbl_initiatives AS i ' .
				'WHERE i.id = ' . self::$DB->quote($initiative_id, 'integer');
		$result = self::$DB->query($query);
		$row = $result->fetchRow();
		return $this->findDisciplines($row[0]);
	}


	/**
	 * Find the list of marketing services for the parent campaign of this initiative
	 * (ie assigned to the parent campaign).
	 * @param integer $initiative_id
	 * @return single item
	 */
	public function findCampaignIdByInitiativeId($initiative_id)
	{
		$query = 'SELECT i.campaign_id ' .
				'FROM tbl_initiatives AS i ' .
				'WHERE i.id = ' . self::$DB->quote($initiative_id, 'integer');
		$result = self::$DB->query($query);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find the list of marketing services available for an initiative (ie not yet assigned to this campaign).
	 * @param integer $campaign_id
	 * @return array
	 */
	public function findAvailableDisciplines($campaign_id)
	{

		$query = 'SELECT tc.id, tc.value ' .
				'FROM tbl_tiered_characteristics AS tc ' .
				'LEFT JOIN tbl_campaign_disciplines AS cd ON cd.tiered_characteristic_id = tc.id ' .
				'WHERE (cd.id is null or cd.campaign_id != ' . self::$DB->quote($campaign_id, 'integer') . ') ' .
				'AND tc.parent_id = 1 ' .
				'GROUP BY tc.id, tc.value ' .
				'ORDER BY tc.value';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find the list of marketing services available for the parent campaign of this initiative
	 * (ie not yet assigned to the parent campaign).
	 * @param integer $initiative_id
	 * @return array
	 */
	public function findAvailableDisciplinesByInitiativeId($initiative_id)
	{

		$query = 'SELECT i.campaign_id ' .
				'FROM tbl_initiatives AS i ' .
				'WHERE i.id = ' . self::$DB->quote($initiative_id, 'integer');
		$result = self::$DB->query($query);
		$row = $result->fetchRow();
		return $this->findAvailableDisciplines($row[0]);
	}


	/**
	 * Find the list of marketing services available for the parent campaign of this initiative
	 * (ie not yet assigned to the parent campaign).
	 * @param integer $initiative_id
	 * @return array
	 */
	public function isCompanyDoNotCall($campaign_id, $company_id)
	{
		$query = 'SELECT count(id) ' .
				'FROM tbl_campaign_do_not_call AS cdnc ' .
				'WHERE cdnc.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'AND cdnc.company_id = ' . self::$DB->quote($company_id, 'integer');
		$result = self::$DB->query($query);
		$row = $result->fetchRow();
		if ($row[0] == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/** Find campaign type lookup information
	 * @return array
	 */
	public function lookupCampaignTypeOptions()
	{
		$query = 'select id, description from tbl_lkp_campaign_types order by sort_order';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/** Find billing terms lookup information
	 * @return array
	 */
	public function lookupBillingTermsOptions()
	{
		$query = 'select id, description from tbl_lkp_campaign_billing_terms order by sort_order';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/** Find payment terms lookup information
	 * @return array
	 */
	public function lookupPaymentTermsOptions()
	{
		$query = 'select id, description from tbl_lkp_campaign_payment_terms order by sort_order';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/** Find payment methods lookup information
	 * @return array
	 */
	public function lookupPaymentMethodsOptions()
	{
		$query = 'select id, description from tbl_lkp_campaign_payment_methods order by sort_order';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Returns the date of the last effective made on a given campaign.
	 * @param integer $campaign_id
	 * @return string datetime in the format 'YYYY-MM-DD HH:MM:SS'
	 */
	public function findLastEffectiveDate($campaign_id)
	{
		$query = 'SELECT comm.communication_date ' .
					'FROM tbl_campaigns AS cam ' .
					'INNER JOIN tbl_initiatives AS init ON cam.id = init.campaign_id ' .
					'INNER JOIN tbl_post_initiatives AS pi ON init.id = pi.initiative_id ' .
					'INNER JOIN tbl_communications AS comm ON pi.last_effective_communication_id = comm.id ' .
					'WHERE cam.id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
					'ORDER BY comm.communication_date DESC LIMIT 1';
		$r = self::$DB->queryOne($query);
		return $r;
	}

	/**
	 * Gets the current prospect status for a given campaign.
	 * @param integer $campaign_id
	 * @return array
	 */
	public function getProspectsStatuses($campaign_id)
	{
		$query = 'SELECT cs.id AS status_id, cs.description AS status, COUNT(cs.description) AS count ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS c ON i.campaign_id = c.id ' .
					'INNER JOIN tbl_lkp_communication_status AS cs ON pi.status_id = cs.id ' .
					'WHERE c.client_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
					'GROUP BY cs.description ' .
					'ORDER BY cs.sort_order DESC';
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
}

	/**
	 * Get the infromation request stats for a given campaign
	 * @param integer $campaign_id
	 * @return array
	 */
	public function getInformationRequestSummary($client_id)
	{
		$query = 'SELECT SUM(ds.information_request_count) AS information_request_count, ' .
					'SUM(ds.information_request_pending_count) AS information_request_pending, ' .
					'SUM(ds.information_request_failed_count) AS information_request_failed, ' .
					'SUM(ds.information_request_converted_count) AS information_request_converted, ' .
					'ROUND((SUM(ds.information_request_failed_count) / SUM(ds.information_request_count)) * 100) AS information_request_percentage_failed, ' .
					'ROUND((SUM(ds.information_request_converted_count) / SUM(ds.information_request_count)) * 100) AS information_request_percentage_converted ' .
					'FROM tbl_rbac_users AS u ' .
					'INNER JOIN tbl_data_statistics AS ds ON u.id = ds.user_id ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
					'WHERE u.is_active = 1 ' .
					'AND cam.id = ' . self::$DB->quote($client_id, 'integer');
				echo "<P>$query</p>";
		return self::$DB->queryRow($query, null, MDB2_FETCHMODE_ASSOC);
	}


	/** Find campaign discipline records for a post
	 * @return array
	 */
	public function findCampaignDisciplineRecordsByCampaignIdPostId($campaign_id, $post_id)
	{
		$query = 'select cd.id, cd.tiered_characteristic_id, tc.value as discipline, ' .
				'lkp_pdm.description as decision_maker_type, ' .
				'lkp_pau.description as agency_user_type, ' .
				'STR_TO_DATE(concat(pdrd.`year_month`, \'01\'), \'%Y%m%d\') as review_date ' .
				'from tbl_campaign_disciplines cd ' .
				'LEFT JOIN tbl_tiered_characteristics tc ON tc.id = cd.tiered_characteristic_id ' .
				'LEFT JOIN tbl_post_decision_makers pdm ON cd.tiered_characteristic_id = pdm.discipline_id ' .
				'AND pdm.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'LEFT JOIN tbl_lkp_decision_maker_types lkp_pdm ON lkp_pdm.id = pdm.type_id ' .
				'LEFT JOIN tbl_post_agency_users pau ON cd.tiered_characteristic_id = pau.discipline_id ' .
				'AND pau.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'LEFT JOIN tbl_lkp_agency_user_types lkp_pau ON lkp_pau.id = pau.type_id ' .
				'LEFT JOIN tbl_post_discipline_review_dates pdrd ON cd.tiered_characteristic_id = pdrd.discipline_id ' .
				'AND pdrd.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'where cd.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'order by cd.tiered_characteristic_id';
//		echo $query;
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}



	/**
	 * Return the number of calls made between two dates, optionally fitered to a given campaign.
	 * @param string $start
	 * @param string $end
	 * @param integer $campaign_id
	 * @return integer
	 */
	public function getCallCount($start, $end, $campaign_id = null)
	{
		$sql = 'SELECT COUNT(comm.id) AS count ' .
				'FROM tbl_communications AS comm ' .
				'JOIN tbl_post_initiatives AS pi ON pi.id = comm.post_initiative_id ' .
				'JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
				'WHERE comm.type_id = 1 ' .
				'AND comm.communication_date >= ' . self::$DB->quote($start, 'text') . ' ' .
				'AND comm.communication_date <= ' . self::$DB->quote($end, 'text');
		if (!is_null($campaign_id))
		{
			$sql .= ' AND i.campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		}
		return self::$DB->queryOne($sql);
	}


	/**
	 * Looks up the id of a campaign where the client id = $client_id
	 * @param integer $client_id
	 * @return integer
	 */
	public function findIdByClientId($client_id)
	{
		$sql = 'SELECT id ' .
				'FROM tbl_campaigns ' .
				'WHERE client_id = ' . self::$DB->quote($client_id, 'integer');
		return self::$DB->queryOne($sql);
	}



}

?>