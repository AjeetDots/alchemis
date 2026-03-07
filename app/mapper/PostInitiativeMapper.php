<?php

/**
 * Defines the app_mapper_PostInitiativeMapper class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_PostInitiativeMapper extends app_mapper_ShadowMapper implements app_domain_PostInitiativeFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_PostInitiative($array['id']);
		$obj->setPostId($array['post_id']);
		$obj->setInitiativeId($array['initiative_id']);
//		$obj->setPropensity($array['propensity']);
		$obj->setStatusId($array['status_id']);
		$obj->setStatus($array['status']);
		$obj->setComment($array['comment']);
		$obj->setNextActionBy($array['next_action_by']);
		$obj->setNextActionByName($array['next_action_by_name']);
		$obj->setLastEffectiveCommunicationDate($array['last_effective_communication_date']);
		$obj->setLastEffectiveCommunicationId($array['last_effective_communication_id']);
		$obj->setLastCommunicationDate($array['last_communication_date']);
		$obj->setLastCommunicationId($array['last_communication_id']);
		$obj->setLastMailerCommunicationId($array['last_mailer_communication_id']);
		$obj->setLastCommunicationUserClientAlias($array['last_communication_user_client_alias']);
		$obj->setNextCommunicationDate($array['next_communication_date']);
		$obj->setLeadSourceId($array['lead_source_id']);
        $obj->setLeadSource($array['lead_source']);
        $obj->setDataSourceId($array['data_source_id']);
        $obj->setDataSource($array['data_source']);
        $obj->setDataSourceChangedDate($array['data_source_updated']);
		$obj->setPriorityCallBack($array['priority_callback']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_post_initiatives');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_post_initiatives (id, post_id, initiative_id, status_id, comment, next_action_by, last_effective_communication_id, ' .
					'last_communication_id, last_mailer_communication_id, next_communication_date, lead_source_id, data_source_id, data_source_updated, priority_callback) VALUES ' .
					'(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'integer', 'integer', 'text', 'integer', 'integer', 'integer', 'integer', 'date', 'integer', 'integer', 'timestamp', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types);
		$data = array($object->getId(), $object->getPostId(), $object->getInitiativeId(), $object->getStatusId(),
						$object->getComment(), $object->getNextActionBy(),
						$object->getLastEffectiveCommunicationId(), $object->getLastCommunicationId(), $object->getLastMailerCommunicationId(),
						$object->getNextCommunicationDate(), $object->getLeadSourceId(), $object->getDataSourceId(), $object->getDataSourceChangedDate(), $object->getPriorityCallBack());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_post_initiatives ' .
					'SET post_id = ?, initiative_id = ?, status_id = ?, comment = ?, next_action_by = ?, last_effective_communication_id = ?, ' .
					'last_communication_id = ?, last_mailer_communication_id = ?, next_communication_date = ?, lead_source_id = ?, data_source_id = ?, data_source_updated = ?, priority_callback = ? ' .
					'WHERE id = ?';
		$types = array('integer', 'integer', 'integer', 'text', 'integer', 'integer', 'integer', 'integer', 'date', 'integer', 'integer', 'timestamp', 'integer', 'integer');
		$this->updateStmt = self::$DB->prepare($query, $types);

		$data = array($object->getPostId(), $object->getInitiativeId(), $object->getStatusId(),
						$object->getComment(), $object->getNextActionBy(),
						$object->getLastEffectiveCommunicationId(), $object->getLastCommunicationId(), $object->getLastMailerCommunicationId(),
						$object->getNextCommunicationDate(), $object->getLeadSourceId(), $object->getDataSourceId(), $object->getDataSourceChangedDate(), $object->getPriorityCallBack(), $object->getId());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$query = 'SELECT pi.id, pi.initiative_id, pi.post_id, pi.status_id, pi.next_action_by, vw_ci_1.client_name as next_action_by_name, ' .
					'pi.next_communication_date, pi.priority_callback, ' .
					'pi.last_effective_communication_id, pi.last_communication_id, pi.last_mailer_communication_id, pi.lead_source_id, pi.data_source_id, pi.data_source_updated, pi.comment, ' .
					'com1.communication_date AS last_communication_date, ' .
					'com2.communication_date AS last_effective_communication_date, ' .
					'vw_ci.client_name, lkp_cs.description AS status, cn.user_alias AS last_communication_user_client_alias, ' .
                    'lkp_ls.description as lead_source, ' .
                    'lkp_ds.description as data_source ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'LEFT JOIN tbl_communications AS com1 ON pi.last_communication_id = com1.id ' .
					'LEFT JOIN tbl_communications AS com2 ON pi.last_effective_communication_id = com2.id ' .
					'LEFT JOIN tbl_campaign_nbms cn ON vw_ci.campaign_id = cn.campaign_id AND com1.user_id = cn.user_id ' .
                    'LEFT JOIN tbl_lkp_lead_source lkp_ls ON pi.lead_source_id = lkp_ls.id ' .
                    'LEFT JOIN tbl_lkp_data_sources lkp_ds ON pi.data_source_id = lkp_ds.id ' .
					'LEFT JOIN vw_client_initiatives AS vw_ci_1 ON pi.next_action_by = vw_ci_1.client_id ' .
					'WHERE pi.id = ?';
		$types = array('integer');
		$selectStmt = self::$DB->prepare($query, $types);

		$values = array($id);
        $result = $this->doStatement($selectStmt, $values);
		return $this->load($result);
	}

 	/**
 	 * Find post_initiative by post_id and initiative_id.
 	 * @param integer $post_id
 	 * @param integer $initiative_id
	 * @return app_domain_PostInitiativeMapper
	 */
	public function findByPostAndInitiative($post_id, $initiative_id)
	{
		$query = 'SELECT pi.id, pi.initiative_id, pi.post_id, pi.status_id, pi.next_action_by, vw_ci_1.client_name as next_action_by_name, ' .
					'pi.next_communication_date, pi.priority_callback, ' .
					'pi.last_effective_communication_id, pi.last_communication_id, pi.last_mailer_communication_id, pi.lead_source_id, pi.data_source_id, pi.data_source_updated, pi.comment, ' .
					'com1.communication_date AS last_communication_date, ' .
					'com2.communication_date AS last_effective_communication_date, ' .
					'vw_ci.client_name, lkp_cs.description AS status, cn.user_alias AS last_communication_user_client_alias, ' .
                    'lkp_ls.description as lead_source, ' .
                    'lkp_ds.description as data_source ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'LEFT JOIN tbl_communications AS com1 ON pi.last_communication_id = com1.id ' .
					'LEFT JOIN tbl_communications AS com2 ON pi.last_effective_communication_id = com2.id ' .
					'LEFT JOIN tbl_campaign_nbms AS cn ON vw_ci.campaign_id = cn.campaign_id AND com1.user_id = cn.user_id ' .
                    'LEFT JOIN tbl_lkp_lead_source lkp_ls ON pi.lead_source_id = lkp_ls.id ' .
                    'LEFT JOIN tbl_lkp_data_sources lkp_ds ON pi.data_source_id = lkp_ds.id ' .
					'LEFT JOIN vw_client_initiatives AS vw_ci_1 ON pi.next_action_by = vw_ci_1.client_id ' .
					'WHERE pi.post_id = ? ' .
					'AND pi.initiative_id = ?';
		$types = array('integer', 'integer');
		$selectByPostAndInitiativeStmt = self::$DB->prepare($query, $types);

		$values = array($post_id, $initiative_id);
		$result = $this->doStatement($selectByPostAndInitiativeStmt, $values);
		return $this->load($result);
	}


 	/**
 	 * Find post_initiative by post_id and initiative_id available to current user
 	 * @param integer $post_id
 	 * @param integer $initiative_id
	 * @return app_domain_PostInitiativeMapper
	 */
	public function findByPostAndInitiativeForCurrentUser($post_id, $initiative_id)
	{
		$query = 'SELECT pi.id, pi.initiative_id, pi.post_id, pi.status_id, pi.next_action_by, vw_ci_1.client_name as next_action_by_name, ' .
					'pi.next_communication_date, pi.priority_callback, ' .
					'pi.last_effective_communication_id, pi.last_communication_id, pi.last_mailer_communication_id, pi.lead_source_id, pi.data_source_id, pi.data_source_updated, pi.comment, ' .
					'com1.communication_date AS last_communication_date, ' .
					'com2.communication_date AS last_effective_communication_date, ' .
					'vw_ci.client_name, lkp_cs.description AS status, cn.user_alias AS last_communication_user_client_alias, ' .
                    'lkp_ls.description as lead_source, ' .
                    'lkp_ds.description as data_source ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'LEFT JOIN tbl_communications AS com1 ON pi.last_communication_id = com1.id ' .
					'LEFT JOIN tbl_communications AS com2 ON pi.last_effective_communication_id = com2.id ' .
					'LEFT JOIN tbl_campaign_nbms AS cn ON vw_ci.campaign_id = cn.campaign_id AND com1.user_id = cn.user_id ' .
                    'LEFT JOIN tbl_lkp_lead_source lkp_ls ON pi.lead_source_id = lkp_ls.id ' .
                    'LEFT JOIN tbl_lkp_data_sources lkp_ds ON pi.data_source_id = lkp_ds.id ' .
					'INNER JOIN tbl_campaign_nbms AS cn_access ON vw_ci.campaign_id = cn_access.campaign_id ' .
					'LEFT JOIN vw_client_initiatives AS vw_ci_1 ON pi.next_action_by = vw_ci_1.client_id ' .
					'WHERE pi.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
					'AND pi.initiative_id = ' . self::$DB->quote($initiative_id, 'integer') . ' ' .
					'AND cn_access.deactivated_date = ' . self::$DB->quote('0000-00-00', 'text') . ' ' .
					'AND cn_access.user_id = ' . self::getCurrentUserId();
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/** Find post initiative id from post_id and initiative_id
	 * @return integer
	 */
	public function lookupIdByPostIdAndInitiativeId($post_id, $initiative_id)
	{
		$query = 'SELECT id ' .
				'FROM tbl_post_initiatives ' .
				'WHERE post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'AND initiative_id = ' . self::$DB->quote($initiative_id, 'integer');
		$result = self::$DB->query($query);
		$id = is_null($result->fetchOne(0,0));
		if (!is_null($id))
		{
			return $id;
		}
		else
		{
			return false;
		}
	}


// 	/**
// 	* Find post_initiative_id by email address
// 	* @param string $email
// 	* @return array
// 	*/
// 	public function findIdByPostEmailAndCampaignNbmEmail($post_email, $campaign_nbm_email)
// 	{
// 	$query = 'SELECT pi.id FROM tbl_post_initiatives AS pi ' .
// 							'INNER JOIN tbl_contacts AS con ON pi.post_id = con.post_id ' .
// 							'INNER JOIN tbl_initiatives AS i ON i.id = pi.initiative_id ' .
// 							'INNER JOIN tbl_campaign_nbms AS cn ON cn.campaign_id = i.campaign_id ' .
// 							'WHERE con.email = ' . self::$DB->quote($post_email, 'text') . ' ' .
// 							'AND cn.user_email = '. self::$DB->quote($campaign_nbm_email, 'text');
// 	return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
// 	}

	/**
	 * Find communication note information by post initiative ID.
	 * @param integer $id post initiative ID
	 * @return associative array of raw array mapper info
	 */
	public function findCommunicationNotes($id)
	{
		// Select communication notes
		$query = 'SELECT com.id, com.status_id, pi.id as post_initiative_id, lkp_cs.description AS status, old_status, lkp_u.name AS user_name, ' .
				'com.comments, pin.id as note_id, pin.note, com.communication_date, com.next_communication_date, com.effective, com.has_attachment, ' .
				'com.decision_maker_type_id, lkp_ct.description as communication_type, p.company_id, ' .
				'cn.user_alias AS user_client_alias, m.id AS meeting_id, m.date as meeting_date, ir.id AS information_request_id ' .
				'FROM tbl_communications AS com ' .
				'INNER JOIN tbl_rbac_users AS lkp_u ON com.user_id = lkp_u.id ' .
				'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON com.status_id = lkp_cs.id ' .
				'INNER JOIN tbl_lkp_communication_types AS lkp_ct ON com.type_id = lkp_ct.id ' .
				'INNER JOIN tbl_post_initiatives AS pi ON com.post_initiative_id = pi.id ' .
				'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
				'INNER JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'LEFT JOIN tbl_campaign_nbms AS cn ON vw_ci.campaign_id = cn.campaign_id AND com.user_id = cn.user_id ' .
				'LEFT JOIN tbl_meetings AS m ON com.id = m.communication_id ' .
				'LEFT JOIN tbl_actions AS ir ON com.id = ir.communication_id and ir.type_id = 2 ' .
				'LEFT JOIN tbl_post_initiative_notes AS pin ON com.note_id = pin.id ' .
				'WHERE com.post_initiative_id = ' . self::$DB->quote($id, 'integer') . ' ' .
				'AND com.type_id IN (1,3,5) ' .
				'ORDER BY com.communication_date DESC';
//		echo $query;
		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

	}


	/**
	 * Find communication note information by post initiative ID.
	 * @param integer $id post initiative ID
	 * @return associative array of raw array mapper info
	 */
	public function findEffectiveCommunicationNotes($id)
	{
		// Select communication notes
		$query = 'SELECT com.id, com.status_id, pi.id as post_initiative_id, lkp_cs.description AS status, old_status, lkp_u.name AS user_name, ' .
				'com.comments, pin.id as note_id, pin.note, com.communication_date, com.next_communication_date, com.effective, com.has_attachment, ' .
				'com.decision_maker_type_id, lkp_ct.description as communication_type, p.company_id, ' .
				'cn.user_alias AS user_client_alias, m.id AS meeting_id, m.date as meeting_date, ir.id AS information_request_id ' .
				'FROM tbl_communications AS com ' .
				'INNER JOIN tbl_rbac_users AS lkp_u ON com.user_id = lkp_u.id ' .
				'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON com.status_id = lkp_cs.id ' .
				'INNER JOIN tbl_lkp_communication_types AS lkp_ct ON com.type_id = lkp_ct.id ' .
				'INNER JOIN tbl_post_initiatives AS pi ON com.post_initiative_id = pi.id ' .
				'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
				'INNER JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'LEFT JOIN tbl_campaign_nbms AS cn ON vw_ci.campaign_id = cn.campaign_id AND com.user_id = cn.user_id ' .
				'LEFT JOIN tbl_meetings AS m ON com.id = m.communication_id ' .
				'LEFT JOIN tbl_actions AS ir ON com.id = ir.communication_id and ir.type_id = 2 ' .
				'LEFT JOIN tbl_post_initiative_notes AS pin ON com.note_id = pin.id ' .
				'WHERE com.post_initiative_id = ' . self::$DB->quote($id, 'integer') . ' ' .
				'AND com.type_id IN (1,3,5) ' .
				'AND com.is_effective = 1 ' .
				'ORDER BY com.communication_date DESC';

		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

	/** Find note information by post initiative ID.
	 * @param integer $id post initiative ID
	 * @return associative array of raw array mapper info
	 */
	public function findNotesByCompanyAndInitiative($company_id, $initiative_id)
	{
		// Select post initiative notes - need to do as a UNION because some communciations do not have notes associated with them (only comments)
		$query = 'SELECT com.id, com.status_id, pi.id as post_initiative_id, lkp_cs.description AS status, old_status, lkp_u.name AS user_name, com.comments, ' .
				'pin.id as note_id, pin.note, com.communication_date as note_created_at, com.next_communication_date, com.has_attachment, ' .
				'com.effective, com.decision_maker_type_id, lkp_ct.description as communication_type, p.company_id, cn.user_alias AS user_client_alias, ' .
				'm.id AS meeting_id, m.date as meeting_date, ir.id AS information_request_id ' .
				'FROM tbl_communications AS com ' .
				'LEFT JOIN tbl_post_initiative_notes AS pin ON com.note_id = pin.id ' .
				'LEFT JOIN tbl_post_initiatives AS pi ON pi.id = com.post_initiative_id ' .
				'LEFT JOIN tbl_rbac_users AS lkp_u ON com.user_id = lkp_u.id ' .
				'LEFT JOIN tbl_lkp_communication_status AS lkp_cs ON com.status_id = lkp_cs.id ' .
				'LEFT JOIN tbl_lkp_communication_types AS lkp_ct ON com.type_id = lkp_ct.id ' .
				'LEFT JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
				'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'LEFT JOIN tbl_campaign_nbms AS cn ON vw_ci.campaign_id = cn.campaign_id AND com.user_id = cn.user_id ' .
				'LEFT JOIN tbl_meetings AS m ON com.id = m.communication_id ' .
				'LEFT JOIN tbl_actions AS ir ON com.id = ir.communication_id and ir.type_id = 2 ' .
				'WHERE p.company_id = ' . self::$DB->quote($company_id, 'integer') . ' ' .
				'AND pi.initiative_id = ' . self::$DB->quote($initiative_id, 'integer') . ' ' .
				'AND com.id is not null ' .
				'UNION ' .
				'SELECT null, null, null, null, null, lkp_u.name AS user_name, null, pin.id as note_id, pin.note, pin.created_at AS note_created_at, ' .
				'null, null, null, null, null, null, p.company_id, null, null, null ' .
				'FROM tbl_post_initiative_notes AS pin ' .
				'LEFT JOIN tbl_post_initiatives pi ON pi.id = pin.post_initiative_id ' .
				'LEFT JOIN tbl_communications AS com ON com.note_id = pin.id ' .
				'LEFT JOIN tbl_rbac_users AS lkp_u ON pin.created_by = lkp_u.id ' .
				'LEFT JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
				'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'WHERE p.company_id = ' . self::$DB->quote($company_id, 'integer') . ' ' .
				'AND pi.initiative_id = ' . self::$DB->quote($initiative_id, 'integer') . ' ' .
				'AND com.note_id is null ' .
				'ORDER BY note_created_at DESC';

		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

	/** Find note information by post initiative ID.
	 * @param integer $id post initiative ID
	 * @return associative array of raw array mapper info
	 */
	public function findNotes($id)
	{
		// Select post initiative notes - need to do as a UNION because some communciations do not have notes associated with them (only comments)
		$query = 'SELECT com.id, com.status_id, pi.id as post_initiative_id, lkp_cs.description AS status, old_status, lkp_u.name AS user_name, com.comments, ' .
				'pin.id as note_id, pin.note, pin.summary, pint.type as post_initiative_note_type, com.communication_date as note_created_at, com.next_communication_date, com.has_attachment, ' .
				'com.effective, com.decision_maker_type_id, lkp_ct.description as communication_type, p.company_id, cn.user_alias AS user_client_alias, ' .
				'm.id AS meeting_id, m.date as meeting_date, ir.id AS information_request_id ' .
				'FROM tbl_communications AS com ' .
				'LEFT JOIN tbl_post_initiative_notes AS pin ON com.note_id = pin.id ' .
				'LEFT JOIN tbl_lkp_post_initiative_note_types AS pint ON pin.note_type_id = pint.id ' .
				'LEFT JOIN tbl_post_initiatives AS pi ON pi.id = com.post_initiative_id ' .
				'LEFT JOIN tbl_rbac_users AS lkp_u ON com.user_id = lkp_u.id ' .
				'LEFT JOIN tbl_lkp_communication_status AS lkp_cs ON com.status_id = lkp_cs.id ' .
				'LEFT JOIN tbl_lkp_communication_types AS lkp_ct ON com.type_id = lkp_ct.id ' .
				'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
				'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'LEFT JOIN tbl_campaign_nbms AS cn ON i.campaign_id = cn.campaign_id AND com.user_id = cn.user_id ' .
				'LEFT JOIN tbl_meetings AS m ON com.id = m.communication_id ' .
				'LEFT JOIN tbl_actions AS ir ON com.id = ir.communication_id and ir.type_id = 2 ' .
				'WHERE com.post_initiative_id = ' . self::$DB->quote($id, 'integer') . ' ' .
				'AND com.id is not null ' .
				'UNION ' .
				'SELECT null, null, null, null, null, lkp_u.name AS user_name, null, pin.id as note_id, pin.note, pin.summary, pint.type as post_initiative_note_type, pin.created_at AS note_created_at, ' .
				'null, null, null, null, null, null, p.company_id, null, null, null ' .
				'FROM tbl_post_initiative_notes AS pin ' .
				'LEFT JOIN tbl_lkp_post_initiative_note_types AS pint ON pin.note_type_id = pint.id ' .
				'LEFT JOIN tbl_post_initiatives pi ON pi.id = pin.post_initiative_id ' .
				'LEFT JOIN tbl_communications AS com ON com.note_id = pin.id ' .
				'LEFT JOIN tbl_rbac_users AS lkp_u ON pin.created_by = lkp_u.id ' .
				'LEFT JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
				'LEFT JOIN tbl_posts AS p ON pi.post_id = p.id ' .
				'WHERE pi.id = ' . self::$DB->quote($id, 'integer') . ' ' .
				'AND com.note_id is null ' .
				'ORDER BY note_created_at DESC';

		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);

	}

	/**
	 * Find tags by post initiative ID and category ID.
	 * @param integer $id
	 * @param integer $category_id
	 * @return associative array of raw array mapper info
	 */
	public function findTagsByPostInitiativeIdAndCategoryId($id, $category_id)
	{
		// Select tags by category_id (ordered by latest inserted tag id)
		$query = 'SELECT t.id, t.value, t.category_id ' .
					'FROM tbl_tags AS t ' .
					'INNER JOIN tbl_post_initiative_tags AS pit ON t.id = pit.tag_id ' .
					'WHERE pit.post_initiative_id = ? ' .
					'AND t.category_id = ? ' .
					'ORDER BY t.id DESC';
		$types = array('integer', 'integer');
		$this->selectTagsByCategoryIdStmt = self::$DB->prepare($query, $types);

		$values = array($id, $category_id);
		$result = $this->doStatement($this->selectTagsByCategoryIdStmt, $values);
		$coll = new app_mapper_PostInitiativeCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find all post initiatives associated with a given post.
	 * @param integer $post_id
	 * @return app_mapper_PostInitiativeCollection collection of app_domain_PostInitiative objects
	 */
	public function findByPostId($post_id)
	{
		$query = 'SELECT pi.id, pi.initiative_id, pi.post_id, pi.status_id, pi.next_action_by, vw_ci_1.client_name as next_action_by_name, ' .
					'pi.next_communication_date, pi.priority_callback, ' .
					'pi.last_effective_communication_id, pi.last_communication_id, pi.last_mailer_communication_id, pi.lead_source_id, pi.data_source_id, pi.data_source_updated, pi.comment, ' .
					'com1.communication_date AS last_communication_date, ' .
					'com2.communication_date AS last_effective_communication_date, ' .
					'vw_ci.client_name, lkp_cs.description AS status, cn.user_alias AS last_communication_user_client_alias, ' .
                    'lkp_ls.description as lead_source, ' .
                    'lkp_ds.description as data_source ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'LEFT JOIN tbl_communications AS com1 ON pi.last_communication_id = com1.id ' .
					'LEFT JOIN tbl_communications AS com2 ON pi.last_effective_communication_id = com2.id ' .
					'LEFT JOIN tbl_campaign_nbms cn ON vw_ci.campaign_id = cn.campaign_id AND com1.user_id = cn.user_id ' .
                    'LEFT JOIN tbl_lkp_lead_source lkp_ls ON pi.lead_source_id = lkp_ls.id ' .
                    'LEFT JOIN tbl_lkp_data_sources lkp_ds ON pi.data_source_id = lkp_ds.id ' .
					'LEFT JOIN vw_client_initiatives AS vw_ci_1 ON pi.next_action_by = vw_ci_1.client_id ' .
					'WHERE pi.post_id = ?';
		$types = array('integer');
		$select_by_post_id_stmt = self::$DB->prepare($query, $types);

		$data = array($post_id);
		$result = $this->doStatement($select_by_post_id_stmt, $data);
		return new app_mapper_PostInitiativeCollection($result, $this);
	}

	/**
	 * Find call backs due for a given user in a given date range.
	 * @param integer $user_id
	 * @param string $start_datetime the start of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @param string $end_datetime the end of the date range in the format YYYY-MM-DD HH:MM:SS
	 * @return array
	 */
	public function findCallBacksByUserId($user_id, $start_datetime, $end_datetime, $priority=false)
	{
		$query = 'SELECT comp.id AS company_id, comp.name AS company_name, comp.website, comp.telephone AS company_telephone,' .
					'pi.id as post_initiative_id, pi.initiative_id, pi.comment, pi.next_communication_date, pi.priority_callback, ' .
					'com.communication_date AS last_effective_communication_date, lkp_cs.description as status, ' .
					'pc.id AS post_id, pc.job_title, pc.full_name, pc.propensity, pc.telephone_1, cl.name AS client_name ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs on lkp_cs.id = pi.status_id ' .
					'INNER JOIN vw_posts_contacts AS pc ON pi.post_id = pc.id ' .
					'INNER JOIN tbl_companies AS comp ON pc.company_id = comp.id ' .
					'INNER JOIN tbl_initiatives AS i ON i.id = pi.initiative_id ' .
					'INNER JOIN tbl_campaigns AS cam ON cam.id = i.campaign_id ' .
					'INNER JOIN tbl_clients AS cl ON cl.id = cam.client_id ' .
					'INNER JOIN tbl_campaign_nbms AS cn_user_access ON i.campaign_id = cn_user_access.campaign_id ' .
					'LEFT JOIN tbl_communications AS com ON com.id = pi.last_communication_id ' .
					'WHERE pi.next_communication_date >= ' . self::$DB->quote($start_datetime, 'timestamp') . ' ' .
					'AND pi.next_communication_date <= ' . self::$DB->quote($end_datetime, 'timestamp') . ' ' .
					'AND com.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ';

					if ($priority) {
						$query .= 'AND pi.priority_callback = ' . self::$DB->quote($priority, 'boolean') . ' ';
					}

					$query .= 'AND cn_user_access.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND cn_user_access.deactivated_date = \'0000-00-00\' ' .
					'ORDER BY pi.next_communication_date';
// 		echo $query;
//		$result = self::$DB->query($query);
//		return app_mapper_Collection::mdb2ResultToArray($result);

		return self::$DB->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Find post initiative lead source lookup information
	 * @return app_mapper_PostInitiativeCollection raw array
	 */
	public function lookupLeadSourceAll()
	{
		$query = 'SELECT id, description FROM tbl_lkp_lead_source ORDER BY sort_order';
		$result = self::$DB->query($query);
		$coll = new app_mapper_CommunicationCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find if post initiative can be accessed by current user
	 * @return boolean
	 */
	public function isAccessibleByCurrentUser($id)
	{
		$values = array($id);
		$types = array('integer');
		$query = 'SELECT count(*) ' .
				'FROM tbl_post_initiatives pi ' .
				'JOIN vw_client_initiatives vw_ci ON vw_ci.initiative_id = pi.initiative_id ' .
				'JOIN tbl_campaign_nbms cn on cn.campaign_id = vw_ci.campaign_id ' .
				'WHERE pi.id = ? ' .
				'AND cn.deactivated_date = \'0000-00-00\' ' .
				'AND cn.user_id = ' . self::getCurrentUserId();
//		$result = $this->doStatement(self::$DB->prepare($query, $types), $values);
//		$row = $result->fetchRow();
		$result = self::$DB->queryOne($query);
//		if ($row[0] > 0)
		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

    /**
     * Has tag
     */
    public function hasTag($postId, $tagId)
    {
        $query = "SELECT * FROM tbl_post_initiative_tags WHERE tag_id = '{$tagId}' AND post_initiative_id = '{$postId}'";
        $result = self::$DB->queryOne($query);
				return (bool) $result;
    }

}

?>