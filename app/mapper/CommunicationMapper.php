<?php

/**
 * Defines the app_mapper_CommunicationMapper class. 
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
require_once('app/domain/PostInitiativeNote.php');

/**
 * @package Alchemis
 */
class app_mapper_CommunicationMapper extends app_mapper_Mapper implements app_domain_CommunicationFinder
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Communication($array['id']);
		$obj->setPostInitiativeId($array['post_initiative_id']);
		$obj->setUserId($array['user_id']);
		$obj->setLeadSourceId($array['lead_source_id']);
		$obj->setTypeId($array['type_id']);
		$obj->setStatusId($array['status_id']);
		$obj->setNextActionBy($array['next_action_by']);
		$obj->setCommunicationDate($array['communication_date']);
		$obj->setDirection($array['direction']);
		$obj->setEffective($array['effective']);
		$obj->setIsEffective($array['is_effective']);
		$obj->setOTE($array['ote']);
		$obj->setTargetingId($array['targeting_id']);
		$obj->setReceptivenessId($array['receptiveness_id']);
		$obj->setDecisionMakerTypeId($array['decision_maker_type_id']);
		$obj->setNextCommunicationDate($array['next_communication_date']);
		$obj->setPriorityCallBack($array['priority_callback']);
		$obj->setNextCommunicationDateReasonId($array['next_communication_date_reason_id']);
		$obj->setComments($array['comments']);
		$obj->setNoteId($array['note_id']);
		$obj->setNotes($array['note']);
		$obj->setHasAttachment($array['has_attachment']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_communications');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		// Insert the note if not null
		if (!is_null($object->getNotes()) && $object->getNotes() != '')
		{
			$note = new app_domain_PostInitiativeNote();
			$note->setPostInitiativeId($object->getPostInitiativeId());
			$note->setCreatedAt($object->getCommunicationDate());
			$note->setCreatedBy($object->getUserId());
			$note->setNote($object->getNotes());
			$note->commit();
			$object->setNoteId($note->getId());
		}
		
		
		
		// Insert
		$query = 'INSERT INTO tbl_communications (id, post_initiative_id, user_id, lead_source_id, type_id, status_id, next_action_by, ' .
					'communication_date, direction, effective, ote, targeting_id, receptiveness_id, decision_maker_type_id, ' .
					'next_communication_date, next_communication_date_reason_id, comments, note_id, is_effective, has_attachment, priority_callback) ' .
					'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		
		$types = array('integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 
						'date', 'text', 'text', 'boolean', 'integer', 
						'text', 'integer', 'date', 'integer', 'text', 'integer', 'integer', 'integer', 'integer');
		
		$insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		
		$data = array($object->getId(), $object->getPostInitiativeId(), $object->getUserId(), $object->getLeadSourceId(), 
						$object->getTypeId(), $object->getStatusId(), $object->getNextActionBy(), $object->getCommunicationDate(), 
						$object->getDirection(), $object->getEffective(), $object->getOTE(), 
						$object->getTargetingId(), $object->getReceptivenessId(), 
						$object->getDecisionMakerTypeId(), $object->getNextCommunicationDate(), 
						$object->getNextCommunicationDateReasonId(), $object->getComments(), 
						$object->getNoteId(), $object->getIsEffective(), $object->getHasAttachment(), $object->getPriorityCallBack());
		$this->doStatement($insertStmt, $data);
			
		if ($attachments = $object->getAttachments())
		{
			foreach ($attachments as $attachment)
			{
				$attachment->commit();
			}
		}
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		// Insert the note
		if (!is_null($object->getNotes()) && $object->getNotes() != '')
		{
			$note = new app_domain_PostInitiativeNote($object->getNoteId());
			$note->setPostInitiativeId($object->getPostInitiativeId());
			$note->setCreatedAt($object->getCommunicationDate());
			$note->setCreatedBy($object->getUserId());
			$note->setNote($object->getNotes());
			$note->commit();
			$object->setNoteId($note->getId());
		}
		
		// Update
		$query = 'UPDATE tbl_communications SET post_initiative_id = ?, user_id = ?, lead_source_id = ?, type_id = ?, ' .
				'status_id = ?, next_action_by = ?, communication_date = ?, direction = ?, effective = ?, ' .
				'ote = ?, targeting_id = ?, receptiveness_id = ?, ' .
				'decision_maker_type_id = ?, next_communication_date = ?, ' .
				'next_communication_date_reason_id = ?, comments = ?, note_id = ?, is_effective = ?, has_attachment =?, priority_callback=? ' .
				'WHERE id = ?';
		
		$types = array('integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'date', 
						'text', 'text', 'integer', 'integer', 'integer', 'integer', 'date', 
						'integer', 'text', 'integer', 'integer', 'integer', 'integer', 'integer');
		
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		$data = array($object->getPostInitiativeId(), $object->getUserId(), $object->getLeadSourceId(),  
						$object->getTypeId(), $object->getStatusId(), $object->getNextActionBy(), $object->getCommunicationDate(), 
						$object->getDirection(), $object->getEffective(), $object->getOTE(), 
						$object->getTargetingId(), $object->getReceptivenessId(), 
						$object->getDecisionMakerTypeId(), $object->getNextCommunicationDate(), 
						$object->getNextCommunicationDateReasonId(), $object->getComments(), 
						$object->getNoteId(), $object->getIsEffective(), $object->getHasAttachment(), $object->getPriorityCallBack(), $object->getId());
		$this->doStatement($updateStmt, $data);
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_communications WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
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
		$types = array('integer');
		
		$query = 'SELECT c.*, n.note ' .
				'FROM tbl_communications AS c ' .
				'LEFT JOIN tbl_post_initiative_notes AS n ON c.note_id = n.id ' .
				'WHERE c.id = ?';
		$data = array($id);
		
		$selectStmt = self::$DB->prepare($query, $types);
		
		$result = $this->doStatement($selectStmt, $data);
		
		return $this->load($result);
	}

	/**
	 * Finds the last communication id for a post initiative id.
	 * @param integer $post_initiative_id
	 * @return app_domain_DomainObject
	 */
	public function doFindLastByPostInitiativeId($post_initiative_id)
	{
		// Select max communication_id by post_initiative_id
		$query = 'SELECT max(id) ' .
				'FROM tbl_communications ' .
				'WHERE post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer');
		
		$id = self::$DB->queryOne($query);
		
		if (is_null($id))
		{
			return false;
		}
		else
		{
			return $this->doFind($id);
		}
//		return $this->doFind ($result->fetchOne(0,0));		
	}

	/**
	 * Finds the last communication id for a post initiative id and type id (eg telephone, mailer etc)
	 * @param integer $post_initiative_id
	 * @param integer $type_id
	 * @return app_domain_DomainObject
	 */
	public function doFindByPostInitiativeIdAndTypeId($post_initiative_id, $type_id)
	{
		// Select max communication_id by post_initiative_id
		$query = 'SELECT max(id) ' .
				'FROM tbl_communications ' .
				'WHERE post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' . 
				'AND type_id = ' . self::$DB->quote($type_id, 'integer');
		return self::$DB->queryOne($query);
//		return $this->doFind ($result->fetchOne(0,0));		
	}

	/**
	 * Finds the communication id of the previous communication for $post_initiative_id and $communication_id.
	 * @param integer $post_initiative_id
	 * @param integer $communication_id
	 * @return integer 
	 */
	public function findPreviousByPostInitiativeIdAndCommunicationId($post_initiative_id, $communication_id)
	{
		$query = 'SELECT max(id) ' .
				'FROM tbl_communications ' .
				'WHERE id < ' . self::$DB->quote($communication_id, 'integer') . ' ' .
				'AND post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer');
		return self::$DB->queryOne($query);
	}
	
//	/**
//	 * Finds the last communication id for a post initiative id.
//	 * @param integer $post_initiative_id
//	 * @return app_domain_DomainObject
//	 */
//	public function findPreviousByPostInitiativeIdAndCommunicationId($post_initiative_id, $communication_id)
//	{
//		// Select max communication_id by post_initiative_id
//		$query = 'SELECT id ' .
//				'FROM tbl_communications ' .
//				'WHERE post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
//				'ORDER BY id DESC LIMIT 2';
//		$result = self::$DB->queryCol($query);
//		if (count($result) > 1)
//		{
//			return $result[1];
//		}
//		else
//		{
//			return null;
//		}
////		return $this->doFind ($result->fetchOne(0,0));		
//	}

	/**
	 * Finds the communication id of the previous effective for $post_initiative_id and $communication_id.
	 * @param integer $post_initiative_id
	 * @param integer $communication_id
	 * @return integer 
	 */
	public function findPreviousEffectiveByPostInitiativeIdAndCommunicationId($post_initiative_id, $communication_id)
	{
		$query = 'SELECT max(id) ' .
				'FROM tbl_communications ' .
				'WHERE id < ' . self::$DB->quote($communication_id, 'integer') . ' ' .
				'AND post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer') . ' ' .
				'AND is_effective = 1 '; 
		return self::$DB->queryOne($query);
	}
	
	/**
	 * Finds the communication id of the previous mailer communication for $communication_id.
	 * @param integer $communication_id
	 * @param integer $type_id
	 * @return integer 
	 */
	public function findPreviousByCommunicationIdAndTypeId($communication_id, $type_id)
	{
		$query = 'SELECT max(id) ' .
				'FROM tbl_communications ' .
				'WHERE id < ' . self::$DB->quote($communication_id, 'integer') . ' ' .
				'AND type_id = ' . self::$DB->quote($type_id, 'integer');
		return self::$DB->queryOne($query);
	}
	
	
	/**
	 * Finds the last communication id for a post id and an initiative id.
	 * @param integer $post_id
	 * @param integer $initiative_id
	 * @return app_domain_DomainObject
	 */
	public function findLastByPostIdAndInitiativeId($post_id, $initiative_id)
	{
		// Select post_initiative_id by post_id and initiative_id
		$query = 'SELECT id ' .
				'FROM tbl_post_initiatives ' .
				'WHERE post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'AND initiative_id  = ' . self::$DB->quote($initiative_id, 'integer');
//		$result = self::$DB->query($query);
//		$id = $result->fetchOne(0,0);
		$id = self::$DB->queryOne($query);
		
		if (!is_null($id))
		{
			return $this->doFindLastByPostInitiativeId($id);
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Find all communication status'.
	 * @return array of communication status'
	 */
	public function findStatusAll()
	{
		$data = array();
		$query = 'select * from tbl_lkp_communication_status order by sort_order';
//		$stmt = self::$DB->prepare($query);
		$result =  self::$DB->query($query);
		$col = new app_mapper_CommunicationCollection($result, $this);
		return $col->toRawArray();
	}


	/**
	 * Find all communication types.
	 * @return array of communication types'
	 */
	public function findTypesAllActive()
	{
		$data = array();
		$query = 'select * from tbl_lkp_communication_types where is_active = 1 order by sort_order';
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt, $data);
		$col = new app_mapper_CommunicationCollection($result, $this);
		return $col->toRawArray();
	}
	
	/**
	 * Find communication status lookup information
	 * @param integer $status_id
	 * @return app_mapper_CommunicationCollection raw array
	 */
	public function lookupStatusForHtmlSelect($status_id = null)
	{
		if (is_null($status_id))
		{
//			$values = array();
//			$types = array();
			$query = 'select id, lower_value, upper_value, is_auto_calculate, description ' .
					'from tbl_lkp_communication_status ' .
					'order by sort_order'; 
		}
		else
		{
//			$values = array('status_id' => $status_id);
//			$types = array('status_id' => 'integer');
			$query = 	'select lkp_cs.id, lower_value, upper_value, is_auto_calculate, description ' .
					'from tbl_lkp_communication_status lkp_cs ' .
					'join tbl_lkp_communication_status_rules lkp_csr on lkp_cs.id = lkp_csr.child_status_id ' .
					'where lkp_csr.status_id = ' . self::$DB->quote($status_id, 'integer') . ' ' .
					'order by lkp_csr.sort_order'; 
		}
		
//		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$result = self::$DB->query($query);
		$coll = new app_mapper_CommunicationCollection($result, $this);
		return $coll->toRawArray();
//		return 
	}

 	/**
 	 * Find communication targeting lookup information
	 * @return app_mapper_CommunicationCollection raw array
	 */
	public function lookupCommunicationTargeting()
	{
		$query = 'SELECT id, description FROM tbl_lkp_communication_targeting ORDER BY sort_order';
		$result = $this->doStatement(self::$DB->prepare($query));
		$coll = new app_mapper_CommunicationCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find targeting description from $targeting_id
	 * @param integer $targeting_id
	 * @return raw data - single row
	 */
	public function lookupTargetingDescription($targeting_id)
	{
		$values = array('targeting_id' => $targeting_id);
		$types = array('targeting_id' => 'integer');
		$query = 'select description from tbl_lkp_communication_targeting where id = :targeting_id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

 	/**
 	 * Find communication targeting status score lookup information
	 * @param integer $id
	 * @return raw data - single row
	 */
	public function lookupTargetingStatusScore($id)
	{
		$values = array('id' => $id);
		$types = array('id' => 'integer');
		$query = 'select status_score from tbl_lkp_communication_targeting where id = :id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find communication receptiveness lookup information
	 * @return app_mapper_CommunicationCollection raw array
	 */
	public function lookupCommunicationReceptiveness()
	{
		$values = array();
		$types = array();
		$query = 'select id, description from tbl_lkp_communication_receptiveness order by sort_order'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$coll = new app_mapper_CommunicationCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find receptiveness description from $receptiveness_id
	 * @param integer $receptiveness_id
	 * @return raw data - single row
	 */
	public function lookupReceptivenessDescription($receptiveness_id)
	{
		$values = array('receptiveness_id' => $receptiveness_id);
		$types = array('receptiveness_id' => 'integer');
		$query = 'select description from tbl_lkp_communication_receptiveness where id = :receptiveness_id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find receptiveness status_score from $receptiveness_id 
	 * @param integer $receptiveness_id
	 * @return raw data - single row
	 */
	public function lookupReceptivenessStatusScore($receptiveness_id)
	{
		$values = array('receptiveness_id' => $receptiveness_id);
		$types = array('receptiveness_id' => 'integer');
		$query = 'select status_score from tbl_lkp_communication_receptiveness where id = :receptiveness_id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
	/**
	 * Find decision maker type description from decision_maker_type_id 
	 * @param integer $decision_maker_type_id
	 * @return raw data - single row
	 */
	public function lookupDecisionMakerTypeDescription($decision_maker_type_id)
	{
		$values = array('decision_maker_type_id' => $decision_maker_type_id);
		$types = array('decision_maker_type_id' => 'integer');
		$query = 'select description from tbl_lkp_decision_maker_types where id = :decision_maker_type_id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find next communication reasons lookup information
	 * @return app_mapper_CommunicationCollection raw array
	 */
	public function lookupNextCommunicationReasons()
	{
		$query = 'select id, description from tbl_lkp_next_communication_reasons order by sort_order';
		$result = $this->doStatement(self::$DB->prepare($query));
		$coll = new app_mapper_CommunicationCollection($result, $this);
		return $coll->toRawArray();
	}
	
	/**
	 * Find next communication description from $next_communication_reason_id 
	 * @param integer $next_communication_reason_id
	 * @return raw data - single row
	 */
	public function lookupNextCommunicationReasonDescription($next_communication_reason_id)
	{
		$values = array('id' => $next_communication_reason_id);
		$types = array('id' => 'integer');
		$query = 'select description from tbl_lkp_next_communication_reasons where id = :id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find next communication status_score from $targeting_id
	 * @param integer $next_communication_reason_id
	 * @return raw data - single row
	 */
	public function lookupNextCommunicationStatusScore($next_communication_reason_id)
	{
		$values = array('id' => $next_communication_reason_id);
		$types = array('id' => 'integer');
		$query = 'select status_score from tbl_lkp_next_communication_reasons where id = :id'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find communication status description by status score
	 * @param integer $status_score
	 * @return raw data - single item
	 */
	public function lookupCommunicationStatusDescriptionByStatusScore($status_score)
	{
		$values = array('status_score' => $status_score);
		$types = array('status_score' => 'integer');
		$query = 'select description from tbl_lkp_communication_status where upper_value >= :status_score and lower_value <= :status_score'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find communication status id by status score
	 * @param integer $status_score
	 * @return raw data - single item
	 */
	public function lookupCommunicationStatusIdByStatusScore($status_score)
	{
		$values = array('status_score' => $status_score);
		$types = array('status_score' => 'integer');
		$query = 'select id from tbl_lkp_communication_status where upper_value >= :status_score and lower_value <= :status_score'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find communication count by user id
	 * @param integer $user_id
	 * @return raw data - single item
	 */
	public function lookupTodayCountByUserId($user_id)
	{
		$values = array('user_id' 	=> $user_id,
						'start_date'=> date('Y-m-d') . '00:00:00',
						'end_date'	=> date('Y-m-d') . '23:59:59');
						
		$types = array('user_id' => 'integer',
						'start_date' => 'date',
						'end_date' => 'date');
						
		$query = 'select count(id) from tbl_communications where user_id = :user_id ' .
				'and communication_date >= :start_date ' .
				'and communication_date <= :end_date'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
	/**
	 * Find communication effective count by user id
	 * @param integer $user_id
	 * @return raw data - single item
	 */
	public function lookupTodayEffectiveCountByUserId($user_id)
	{
		$values = array('user_id' 	=> $user_id,
						'start_date'=> date('Y-m-d') . '00:00:00',
						'end_date'	=> date('Y-m-d') . '23:59:59');
						
		$types = array('user_id' => 'integer',
						'start_date' => 'date',
						'end_date' => 'date');
						
		$query = 'select count(id) from tbl_communications where user_id = :user_id ' .
				'and communication_date >= :start_date ' .
				'and communication_date <= :end_date ' .
				'and is_effective = 1'; 
		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
		$row = $result->fetchRow();
		return $row[0];
	}

 	/** Find communication count by post initiative id
	 * @param integer $post_initiative_id
	 * @return raw data - single item
	 */
	public function lookupCountByPostInitiativeId($post_initiative_id)
	{
		$query = 'select count(id) from tbl_communications where post_initiative_id = ' . self::$DB->quote($post_initiative_id, 'integer');
		return self::$DB->queryOne($query);
	}
	
	/**
	 * Find decision maker lookup information
	 * @return array
	 */
	public function lookupDecisonMakerOptions()
	{
		$query = 'select id, description from tbl_lkp_decision_maker_types order by sort_order'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find decision maker type description from $decision_maker_type_id
	 * @param integer $decision_maker_type_id
	 * @return raw data - single item
	 */
	public function lookupDecisionMakerDescription($decision_maker_type_id)
	{
		$query = 'select description ' .
				'from tbl_lkp_decision_maker_types ' .
				'where id = ' . self::$DB->quote($decision_maker_type_id, 'integer');
		$result = self::$DB->query($query);
		return $result->getOne();
	}
	
		/**
	 * Find decision maker lookup information
	 * @return array
	 */
	public function lookupAgencyUserOptions()
	{
		$query = 'select id, description from tbl_lkp_agency_user_types order by sort_order'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
	/**
	 * Find agency user type description from $agency_user_type_id
	 * @param integer $agency_user_type_id
	 * @return raw data - single item
	 */
	public function lookupAgencyUserDescription($agency_user_type_id)
	{
		$query = 'select description ' .
				'from tbl_lkp_agency_user_types ' .
				'where id = ' . self::$DB->quote($agency_user_type_id, 'integer');
		$result = self::$DB->query($query);
		return $result->getOne();
	}	
	
	/** Find discipline lookup information
	 * @return array
	 */
	public function lookupDisciplineOptions()
	{
		$query = 'select id, value as description ' .
				'from tbl_tiered_characteristics ' .
				'where parent_id = 1 ' .
				'order by value'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
		
	/** Find discipline ids 
	 * @return array
	 */
	public function lookupDisciplineIds()
	{
		$query = 'select id ' .
				'from tbl_tiered_characteristics ' .
				'where parent_id = 1 ' .
				'order by value'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
		
	/** Find discipline ids 
	 * NOTE: we select from the shadow tables because the communication_id field in the 'normal' tables
	 * could be over-written at any time - even between logging a communication and calling this function
	 * @return array
	 */
	public function findCampaignDisciplineRecordsByCommunicationId($campaign_id, $communication_id)
	{
		$query = 'select cd.id, cd.tiered_characteristic_id, tc.value as discipline, ' .
				'lkp_pdm.description as decision_maker_type, ' .
				'lkp_pau.description as agency_user_type, ' .
				'STR_TO_DATE(concat(pdrd.`year_month`, \'01\'), \'%Y%m%d\') as review_date ' .
				'from tbl_campaign_disciplines cd ' .
				'LEFT JOIN tbl_tiered_characteristics tc ON tc.id = cd.tiered_characteristic_id ' .
				'LEFT JOIN tbl_post_decision_makers_shadow pdm ON cd.tiered_characteristic_id = pdm.discipline_id ' .
				'AND pdm.communication_id = ' . self::$DB->quote($communication_id, 'integer') . ' ' .
				'LEFT JOIN tbl_lkp_decision_maker_types lkp_pdm ON lkp_pdm.id = pdm.type_id ' .
				'LEFT JOIN tbl_post_agency_users_shadow pau ON cd.tiered_characteristic_id = pau.discipline_id ' .
				'AND pau.communication_id = ' . self::$DB->quote($communication_id, 'integer') . ' ' .
				'LEFT JOIN tbl_lkp_agency_user_types lkp_pau ON lkp_pau.id = pau.type_id ' .
				'LEFT JOIN tbl_post_discipline_review_dates_shadow pdrd ON cd.tiered_characteristic_id = pdrd.discipline_id ' .
				'AND pdrd.communication_id = ' . self::$DB->quote($communication_id, 'integer') . ' ' .
				'where cd.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'order by cd.tiered_characteristic_id'; 
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
}

?>