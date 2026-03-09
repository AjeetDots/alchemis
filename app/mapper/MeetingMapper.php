<?php

/**
 * Defines the app_mapper_MeetingMapper class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/ShadowMapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package Alchemis
 */
class app_mapper_MeetingMapper extends app_mapper_ShadowMapper implements app_domain_MeetingFinder
{
	protected static $DB;
	protected $selectAllStmt;
	protected $selectStmt;
	protected $selectByPostInitiativeIdStmt;
	protected $selectCurrentByPostInitiativeIdStmt;
	protected $insertStmt;
	protected $updateStmt;
	protected $id;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT m.*, u.name AS created_by_name ' .
				'FROM tbl_meetings m ' .
				'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
				'ORDER BY m.id');

		// Select single
		$query = 'SELECT m.*, u.name AS created_by_name ' .
				'FROM tbl_meetings m ' .
				'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
				'WHERE m.id = ?';
		$this->selectStmt = self::$DB->prepare($query);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Meeting($array['id']);
		$obj->setPostInitiativeId($array['post_initiative_id']);
		$obj->setCommunicationId($array['communication_id']);
		$obj->setIsCurrent($array['is_current']);
		$obj->setStatusId($array['status_id']);
		$obj->setTypeId($array['type_id']);
		$obj->setDate($array['date']);
		$obj->setReminderDate($array['reminder_date']);
		$obj->setAttendedDate($array['attended_date']);
		$obj->setLocationId($array['location_id']);
		$obj->setNbmPredictedRating($array['nbm_predicted_rating']);
		$obj->setNotes($array['notes']);
		$obj->setCreatedAt($array['created_at']);
		$obj->setCreatedBy($array['created_by']);
		$obj->setModifiedAt($array['modified_at']);
        $obj->setModifiedBy($array['modified_by']);
		$obj->setCreatedByName($array['created_by_name']);
		$obj->setFeedbackRating($array['feedback_rating']);
		$obj->setFeedbackDecisionMaker($array['feedback_decision_maker']);
		$obj->setFeedbackAgencyUser($array['feedback_agency_user']);
		$obj->setFeedbackBudgetAvailable($array['feedback_budget_available']);
		$obj->setFeedbackReceptive($array['feedback_receptive']);
		$obj->setFeedbackTargeting($array['feedback_targeting']);
		$obj->setFeedbackMeetingLength($array['feedback_meeting_length']);
		$obj->setFeedbackComments($array['feedback_comments']);
		$obj->setFeedbackNextSteps($array['feedback_next_steps']);
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
		return 'app_domain_Meeting';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_meetings');
//		echo "<pre>";
//		print_r($this->id);
//		echo "</pre>";

		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		// Insert
		$query = 'INSERT INTO tbl_meetings (id, post_initiative_id, communication_id, is_current, status_id, ' .
				'type_id, date, reminder_date, attended_date, location_id, nbm_predicted_rating, notes, created_at, created_by, ' .
		        'modified_at, modified_by, ' .
				'feedback_rating, feedback_decision_maker, feedback_agency_user, feedback_budget_available, ' .
				'feedback_receptive, feedback_targeting, feedback_meeting_length, feedback_comments, ' .
				'feedback_next_steps) ' .
				'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'date', 'date', 'date', 'integer', 'integer', 'text', 'date', 'integer',
		                 'date', 'integer',
						'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'text', 'text');
		$this->insertStmt = self::$DB->prepare($query, $types);
		$data = array($object->getId(), $object->getPostInitiativeId(), $object->getCommunicationId(), $object->getIsCurrent(),
						$object->getStatusId(), $object->getTypeId(), $object->getDate(),
						$object->getReminderDate(), $object->getAttendedDate(), $object->getLocationId(), $object->getNbmPredictedRating(), $object->getNotes(),
						$object->getCreatedAt(),
						$object->getCreatedBy(),
						$object->getCreatedAt(),
                        $object->getCreatedBy(),
						$object->getFeedbackRating(), $object->getFeedbackDecisionMaker(),
						$object->getFeedbackAgencyUser(), $object->getFeedbackBudgetAvailable(), $object->getFeedbackReceptive(),
						$object->getFeedbackTargeting(), $object->getFeedbackMeetingLength(), $object->getFeedbackComments(),
						$object->getFeedbackNextSteps());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		// Update
		$query = 'UPDATE tbl_meetings SET post_initiative_id = ?, communication_id = ?, is_current = ?, ' .
				'status_id = ?, type_id = ?, date = ?, reminder_date = ?, attended_date = ?, location_id = ?, nbm_predicted_rating = ?, notes = ?, ' .
		        'created_at = ?, created_by = ?, modified_at = ?, modified_by = ?, ' .
				'feedback_rating = ?, feedback_decision_maker = ?, feedback_agency_user = ?, feedback_budget_available = ?, ' .
				'feedback_receptive = ?, feedback_targeting = ?, feedback_meeting_length = ?, ' .
				'feedback_comments = ?, feedback_next_steps = ? WHERE id = ?';
		$types = array('integer', 'integer', 'integer', 'integer', 'integer', 'date', 'date', 'date', 'integer', 'integer', 'text',
		               'date', 'integer',
		               'date', 'integer',
					   'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'text', 'text', 'integer');

		$this->updateStmt = self::$DB->prepare($query, $types);
		$data = array($object->getPostInitiativeId(), $object->getCommunicationId(), $object->getIsCurrent(),
						$object->getStatusId(), $object->getTypeId(), $object->getDate(),
						$object->getReminderDate(), $object->getAttendedDate(), $object->getLocationId(), $object->getNbmPredictedRating(), $object->getNotes(),
						$object->getCreatedAt(),
						$object->getCreatedBy(),
						$object->getModifiedAt(),
                        $object->getModifiedBy(),
						$object->getFeedbackRating(), $object->getFeedbackDecisionMaker(),
						$object->getFeedbackAgencyUser(), $object->getFeedbackBudgetAvailable(), $object->getFeedbackReceptive(),
						$object->getFeedbackTargeting(), $object->getFeedbackMeetingLength(), $object->getFeedbackComments(),
						$object->getFeedbackNextSteps(), $object->getId());

		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_meetings WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);

		// now update all entries in tbl_meetings_shadow to shadow_type = 'z'.
		// Type z = where meetings have been completely removed from tbl_meetings
		$query = "UPDATE tbl_meetings_shadow SET shadow_type = 'z' WHERE id = " . self::$DB->quote($object->getId(), 'integer');
		self::$DB->query($query);
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$values = array($id);
		// factor this out

		// Returns an MDB2_Result object
		$result = $this->doStatement($this->selectStmt, $values);

		// Extract and return an associative array from the MDB2_Result object
		return $this->load($result);
	}

	/**
	 * Find all meetings.
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_MeetingCollection($result, $this);
	}

	/**
	 * Find all meetings by post initiative id.
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public function findByPostInitiativeId($post_initiative_id)
	{
		// Select by post initiative id
		$query = 'SELECT m.*, ms.description AS status ' .
					'FROM tbl_meetings m JOIN tbl_lkp_communication_status ms ON m.status_id = ms.id ' .
					'WHERE post_initiative_id = ? ' .
					'ORDER BY date DESC';
		$this->selectByPostInitiativeIdStmt = self::$DB->prepare($query);

		$values = array($post_initiative_id);
		$result = $this->doStatement($this->selectByPostInitiativeIdStmt, $values);
		return new app_mapper_MeetingCollection($result, $this);
	}


	/**
	 * Find meeting by communication_id.
	 * @return app_domain_Meeting object
	 */
	public function findByCommunicationId($communication_id)
	{
		// Select by communication id
		$query = 	'SELECT m.*, u.name AS created_by_name ' .
					'FROM tbl_meetings m ' .
					'JOIN tbl_rbac_users AS u ON m.created_by = u.id ' .
					'WHERE communication_id = ' . self::$DB->quote($communication_id, 'integer');
//		$this->selectByPostInitiativeIdStmt = self::$DB->prepare($query);
//		$values = array($post_initiative_id);
//		$result = $this->doStatement($this->selectByPostInitiativeIdStmt, $values);
//		return new app_mapper_MeetingCollection(self::$DB->query($query), $this);
		return $this->load(self::$DB->query($query));

	}

	/** Find all current meetings by post initiative id.
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public function findCurrentByPostInitiativeId($post_initiative_id)
	{
		// Select by post initiative id
		$query = 'SELECT m.*, ms.description AS status ' .
					'FROM tbl_meetings m JOIN tbl_lkp_communication_status ms ON m.status_id = ms.id ' .
					'WHERE post_initiative_id = ? ' .
					'AND is_current = 1 ' .
					'ORDER BY date DESC';
		$this->selectCurrentByPostInitiativeIdStmt = self::$DB->prepare($query);

		$values = array($post_initiative_id);
		$result = $this->doStatement($this->selectCurrentByPostInitiativeIdStmt, $values);
		return new app_mapper_MeetingCollection($result, $this);
	}

	/**
	 * Find status description from $status_id
	 * @return app_mapper_MeetingCollection raw array - single item
	 */
	public function lookupStatusById($status_id)
	{
		$query = 'SELECT description FROM tbl_lkp_communication_status WHERE id = ?';
		$data = array($status_id);
		$result = $this->doStatement(self::$DB->prepare($query), $data);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Get all status descriptions
	 * @return app_mapper_MeetingCollection raw array
	 */
	public function getStatusAll()
	{
		$query = 'SELECT * FROM tbl_lkp_communication_status ORDER BY sort_order';
		$result = $this->doStatement(self::$DB->prepare($query), array());
		$coll = new app_mapper_MeetingCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find type description from $type_id
	 * @return app_mapper_MeetingCollection raw array - single item
	 */
	public function lookupTypeById($type_id)
	{
		$query = 'SELECT description FROM tbl_lkp_meeting_types WHERE id = ?';
		$data = array($type_id);
		$result = $this->doStatement(self::$DB->prepare($query), $data);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find all type descriptions
	 * @return app_mapper_MeetingCollection raw array
	 */
	public function getTypesAll()
	{
		$query = 'SELECT * FROM tbl_lkp_meeting_types ORDER BY sort_order';
		$result = $this->doStatement(self::$DB->prepare($query), array());
		$coll = new app_mapper_MeetingCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find the audit history for a given meeting.
	 * @param integer $id the meeting ID
	 * @return array where each item of the array is an associative array of
	 *         field => value mappings
	 */
	public function findHistory($id)
	{
		$values = array($id);
		// factor this out

		$query = 'SELECT ms.*, lms.description AS status, lmt.description AS type, ' .
					'ru1.handle AS created_by_handle, ru2.handle AS updated_by_handle, ' .
					'p.job_title AS post_job_title, ' .
					'i.name AS initiative ' .
					'FROM tbl_meetings_shadow AS ms ' .
					'INNER JOIN tbl_lkp_communication_status AS lms ON ms.status_id = lms.id ' .
					'INNER JOIN tbl_lkp_meeting_types AS lmt ON ms.type_id = lmt.id ' .
					'INNER JOIN tbl_rbac_users AS ru1 ON ms.created_by = ru1.id ' .
					'INNER JOIN tbl_rbac_users AS ru2 ON ms.shadow_updated_by = ru2.id ' .
					'INNER JOIN tbl_post_initiatives AS pi ON ms.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_posts AS p ON pi.post_id = p.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'WHERE ms.id = ? ' .
					'ORDER BY ms.shadow_timestamp DESC';
		$data = array($id);

		// Returns an MDB2_Result object
		$result = $this->doStatement(self::$DB->prepare($query), $values);

		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$raw[] = $row;
			$result->nextResult();
		}

		return $raw;
	}

	/**
	 * Find those for a given user in a given range.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findByUserId($user_id, $start_datetime, $end_datetime)
	{
		$query = 'SELECT m.id, m.date, m.notes, pc.propensity, ' .
					'pc.id AS post_id, pc.job_title, pc.full_name, ' .
					'comp.id AS company_id, comp.name AS company_name, comp.website, ' .
					'i.name AS initiative, client.name AS client ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN vw_posts_contacts AS pc ON pi.post_id = pc.id ' .
					'INNER JOIN tbl_companies AS comp ON pc.company_id = comp.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS camp ON i.campaign_id = camp.id ' .
					'INNER JOIN tbl_clients AS client ON camp.client_id = client.id ' .
					'WHERE m.created_by = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND m.date >= ' . self::$DB->quote($start_datetime, 'date') . ' ' .
					'AND m.date <= ' . self::$DB->quote($end_datetime, 'date') . ' ' .
					'ORDER BY m.date';
		$result = self::$DB->query($query);
		return app_mapper_Collection::mdb2ResultToArray($result);
	}

	/**
	 * Return the current meetings of a given status for a given user.
	 * @param integer $user_id
	 * @param integer $status_id
	 * @return app_mapper_MeetingCollection collection of app_domain_Meeting objects
	 */
	public function findByUserIdStatusId($user_id, $status_id)
	{
		$query = 'SELECT m.id, m.date, m.notes, pc.propensity, ' .
					'pc.id AS post_id, pc.job_title, pc.full_name, ' .
					'comp.id AS company_id, comp.name AS company_name, comp.website, ' .
					'i.name AS initiative, client.name AS client ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN vw_posts_contacts AS pc ON pi.post_id = pc.id ' .
					'INNER JOIN tbl_companies AS comp ON pc.company_id = comp.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS camp ON i.campaign_id = camp.id ' .
					'INNER JOIN tbl_clients AS client ON camp.client_id = client.id ' .
					'WHERE m.created_by = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND m.is_current = 1 ' .
					'AND m.status_id = ' . self::$DB->quote($status_id, 'integer') . ' ' .
					'ORDER BY m.date';
//		echo $query;
		$result = self::$DB->query($query);
		return app_mapper_Collection::mdb2ResultToArray($result);
	}

	/**
	 * Return the ID of the company a given meeting is associated with.
	 * @param integer $meeting_id
	 * @return integer
	 */
	public function findCompanyId($meeting_id)
	{
		$query = 'SELECT p.company_id FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
 					'INNER JOIN tbl_posts AS p ON pi.post_id = p.id ' .
 					'WHERE m.id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($meeting_id);
		$result = $this->doStatement($stmt, $data);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		return $row['company_id'];
	}

	/**
	 * Return the current meetings of a given status.
	 * @param integer $status_id
	 * @return array
	 */
	public function findByStatusId($status_id)
	{
		$query = 'SELECT m.id, m.date, m.notes, pc.propensity, ' .
					'pc.id AS post_id, pc.job_title, pc.full_name, ' .
					'comp.id AS company_id, comp.name AS company_name, comp.website, ' .
					'i.name AS initiative, client.name AS client ' .
					'FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'INNER JOIN vw_posts_contacts AS pc ON pi.post_id = pc.id ' .
					'INNER JOIN tbl_companies AS comp ON pc.company_id = comp.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS camp ON i.campaign_id = camp.id ' .
					'INNER JOIN tbl_clients AS client ON camp.client_id = client.id ' .
					'WHERE m.is_current = 1 ' .
					'AND m.status_id = ' . self::$DB->quote($status_id, 'integer') . ' ' .
					'ORDER BY m.date';
		$result = self::$DB->query($query);
		return app_mapper_Collection::mdb2ResultToArray($result);
	}

	/**
	 * Return an associated array of status values.
	 * @return array
	 */
	public static function lookupStatuses()
	{
		$statuses = array();
		$query = 'SELECT * FROM tbl_lkp_communication_status WHERE id >= 12 ORDER BY sort_order';
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $result;
	}

	/**
	 * Return an associated array of status values.
	 * @return array
	 */
	public static function lookupLocationAll()
	{
		$statuses = array();
		$query = 'SELECT * FROM tbl_lkp_meeting_locations ORDER BY sort_order';
		$result = self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		return $result;
	}



}

?>