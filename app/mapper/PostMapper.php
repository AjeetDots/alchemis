<?php

/**
 * Defines the app_mapper_PostMapper class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_PostMapper extends app_mapper_ShadowMapper implements app_domain_PostFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		// $this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_posts ORDER BY job_title');

		// Select single
		// $query = 'SELECT * FROM tbl_posts WHERE id = ?';
		// $types = array('integer');
		// $this->selectStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Post($array['id']);
		$obj->setCompanyId($array['company_id']);
		$obj->setJobTitle($array['job_title']);
		$obj->setPropensity($array['propensity']);
		$obj->setTelephone1($array['telephone_1']);
		$obj->setTelephone2($array['telephone_2']);
		$obj->setTelephoneSwitchboard($array['telephone_switchboard']);
		$obj->setTelephoneFax($array['telephone_fax']);
        $obj->setDeleted($array['deleted']);
        $obj->setDataSourceId($array['data_source_id']);
        $obj->setDataSource($array['data_source']);
        $obj->setDataSourceChangedDate($array['data_source_updated']);
		$obj->setAdditionalInfo($array['additional_info']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_posts');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		if (!isset($this->insertStmt))
		{
			$query = 'INSERT INTO tbl_posts (id, company_id, job_title, telephone_1, telephone_2, ' .
						'telephone_switchboard, telephone_fax, data_source_id, data_source_updated, data_owner_id, additional_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
			$types = array('integer', 'integer', 'text', 'text', 'text', 'text', 'text', 'integer', 'timestamp', 'integer', 'text');
			$this->insertStmt = self::$DB->prepare($query, $types);
		}

		$data = array($object->getId(), $object->getCompanyId(), $object->getJobTitle(),
						$object->getTelephone1(), $object->getTelephone2(),
                        $object->getTelephoneSwitchboard(), $object->getTelephoneFax(),
                        $object->getDataSourceId(), $object->getDataSourceChangedDate(), $object->getDataOwnerId(), $object->getAdditionalInfo());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		if (!isset($this->updateStmt))
		{
			$query = 'UPDATE tbl_posts SET company_id = ?, job_title = ?, propensity = ?, ' .
						'telephone_1 = ?, telephone_2 = ?, telephone_switchboard = ?, ' .
                        'telephone_fax = ?, deleted = ?, '.
                        'data_source_id = ?, data_source_updated = ?, additional_info = ? '.
						'WHERE id = ?';
			$types = array('integer', 'text', 'integer', 'text', 'text', 'text', 'text', 'integer', 'integer', 'timestamp', 'text', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types);
		}

		$data = array($object->getCompanyId(), $object->getJobTitle(),
						$object->getPropensity(), $object->getTelephone1(),
						$object->getTelephone2(), $object->getTelephoneSwitchboard(),
                        $object->getTelephoneFax(), $object->getDeleted(),
                        $object->getDataSourceId(), $object->getDataSourceChangedDate(), 
                        $object->getAdditionalInfo(), $object->getId());
		$this->doStatement($this->updateStmt, $data);
	}

	/** Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_posts SET deleted = 1 WHERE id = ?';
		$types = array('integer');
		$updateStmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($updateStmt, $data);
	}

	/**
	 * Return the given post object.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
        $query = 'SELECT p.*, lkp_ds.description AS data_source FROM tbl_posts AS p '.
            'LEFT JOIN tbl_lkp_data_sources lkp_ds ON p.data_source_id = lkp_ds.id '.
            'WHERE p.id = ?';
		$types = array('integer');
        $selectStmt = self::$DB->prepare($query, $types);

		$data = array($id);
        $result = $this->doStatement($selectStmt, $data);
		return $this->load($result);
	}

	/**
	 * Find all posts.
	 * @return app_mapper_CompanyCollection collection of app_domain_Company objects
	 */
	public function findAll()
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();
        
        if (!empty($user['client_id'])) {
            $query = 'SELECT p.*, lkp_ds.description AS data_source FROM tbl_posts AS p ' . 
                'LEFT JOIN tbl_lkp_data_sources lkp_ds ON p.data_source_id = lkp_ds.id' .
                'WHERE p.data_owner_id = ? ORDER BY p.job_title';
            $types = array('integer');
            $values = array($user['client_id']);
        } else {
            $query = 'SELECT p.*, lkp_ds.description AS data_source FROM tbl_posts AS p ' . 
                'LEFT JOIN tbl_lkp_data_sources lkp_ds ON p.data_source_id = lkp_ds.id' .
                'WHERE p.data_owner_id IS NULL ORDER BY p.job_title';
            $types = array();
            $values = array();
        }

		$selectAllStmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($selectAllStmt, $values);
		return new app_mapper_PostCollection($result, $this);
	}

	/**
	 * Find posts by company_id
	 * @param integer $company_id company_id
	 * @return app_mapper_PostCollection collection of app_domain_Post objects
	 */
	public function findByCompanyId($company_id)
	{

        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();
        
        if (!empty($user['client_id'])) {
            $query = 'SELECT * FROM vw_posts_contacts WHERE company_id = ? AND data_owner_id = ? ORDER BY propensity DESC';
            $types = array('integer', 'integer');
            $values = array($company_id, $user['client_id']);
        } else {
            $query = 'SELECT * FROM vw_posts_contacts WHERE company_id = ? AND data_owner_id IS NULL ORDER BY propensity DESC';
            $types = array('integer');
            $values = array($company_id);
        }
		$this->selectByCompanyStmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($this->selectByCompanyStmt, $values);
		return new app_mapper_PostCollection($result, $this);
	}

	/**
	 * Find posts by post initiative ID
	 * @param integer $post_initiative_id
	 * @return app_domain_Post object
	 */
	public function findByPostInitiativeId($post_initiative_id)
	{
        $query = 'SELECT vw_p.* FROM tbl_posts AS vw_p ' .
                    'LEFT JOIN tbl_lkp_data_sources lkp_ds ON vw_p.data_source_id = lkp_ds.id '.
					'INNER JOIN tbl_post_initiatives AS pi ON vw_p.id = pi.post_id ' .
					'WHERE pi.id = ?';
		$types = array('integer');
		$this->selectByPostInitiativeIdStmt = self::$DB->prepare($query, $types);

		$values = array($post_initiative_id);
		$result = $this->doStatement($this->selectByPostInitiativeIdStmt, $values);
		return $this->load($result);
	}

	/**
	 * Find client intiatives for a given post ID
	 * @param integer $id post id
	 * @return associative array of raw array mapper info
	 */
	public function findPostInitiatives($id)
	{
		$query = 'SELECT pi.id AS post_initiative_id, vw_ci.client_id, client_name, pi.initiative_id, ' .
					'vw_ci.initiative_name, lkp_cs.description as status ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'WHERE post_id = ? ' .
					'ORDER BY client_name, initiative_name';
		$types = array('integer');
		$this->selectPostInitiativesStmt = self::$DB->prepare($query, $types);

		$values = array($id);
		$result = $this->doStatement($this->selectPostInitiativesStmt, $values);

		$coll = new app_mapper_PostCollection($result, $this);
		return $coll->toRawArray();
	}

 	/**
 	 * Find client initiatives for a given post ID available to the current user
	 * @param integer $id post id
	 * @return associative array of raw array mapper info
	 */
	public function findPostInitiativesForCurrentUser($id)
	{
		$query = 'SELECT pi.id AS post_initiative_id, vw_ci.client_id, client_name, pi.initiative_id, ' .
					'vw_ci.initiative_name, lkp_cs.description as status ' .
					'FROM tbl_post_initiatives AS pi ' .
					'INNER JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'INNER JOIN tbl_campaign_nbms AS cn_access ON vw_ci.campaign_id = cn_access.campaign_id ' .
					'WHERE post_id = ' . self::$DB->quote($id, 'integer') . ' ' .
					'AND cn_access.deactivated_date = ' . self::$DB->quote('0000-00-00', 'text') . ' ' .
					'AND cn_access.user_id = ' . self::$DB->quote(self::getCurrentUserId(), 'integer') . ' ' .
					'ORDER BY client_name, initiative_name';
		$result = self::$DB->query($query);
		$coll = new app_mapper_PostCollection($result, $this);
		return $coll->toRawArray();
	}

	/**
	 * Find posts by company ID and initiative ID
	 * @param integer $company_id
	 * @param integer $initiative_id
	 * @param integer $post_id optional - if included, puts this post_id to the top of the array
	 * @return associative array of raw array mapper info
	 */
	public function findPostsByCompanyAndInitiative($company_id, $initiative_id, $post_id = null)
	{
		$query = 'SELECT pi.id AS post_initiative_id, pi.status_id, lkp_cs.description AS status, pi.next_communication_date, ' .
					'vw_ci.*, vw_pc.*, vw_pc.id AS post_id, ' .
					'com1.communication_date AS last_communication_date, ' .
					'com2.communication_date AS last_effective_communication_date, ' .
					'f_next_comm_date_period(pi.next_communication_date, 3) AS next_comm_date_period, ' .
					'f_get_post_meeting_count(pi.post_id) AS meeting_count ' .
					'FROM vw_posts_contacts AS vw_pc ' .
					'INNER JOIN tbl_post_initiatives AS pi ON vw_pc.id = pi.post_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'LEFT JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'LEFT JOIN tbl_communications AS com1 ON pi.last_communication_id = com1.id ' .
					'left join tbl_communications AS com2 ON pi.last_effective_communication_id = com2.id ' .
					'WHERE vw_pc.company_id = ? ' .
					'AND vw_ci.initiative_id = ? ' .
					'ORDER BY vw_pc.first_name, vw_pc.surname';
		$types = array('integer', 'integer');
		$this->selectPostsByCompanyAndInitiativeStmt = self::$DB->prepare($query, $types);

		$values = array($company_id, $initiative_id);
		$result = $this->doStatement($this->selectPostsByCompanyAndInitiativeStmt, $values);

		// Note: leave this section as because we do some work with the array before returning it
		$cols = array_keys($result->getColumnNames());

		$results = array();

		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();

			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}

			//if we find post_id input param then push the current $output array to beginning of $results array
			//echo '<br />$output["post_id"] =  ' . $output['post_id'] . ' : post_id = ' . $post_id . '<br />';
			if ($output['post_id'] == $post_id)
			{
				array_unshift($results, $output);
			}
			else
			{
				array_push($results, $output);
			}
		}
		return $results;
	}

	/**
	 * Find posts by company ID and initiative ID
	 * @param integer $company_id
	 * @param integer $initiative_id
	 * @param integer $post_id optional - if included, puts this post_id to the top of the array
	 * @return associative array of raw array mapper info
	 */
	public function findPostsByCompanyAndInitiativeForCurrentUser($company_id, $initiative_id, $post_id = null)
	{
		$query = 'SELECT pi.id AS post_initiative_id, pi.status_id, lkp_cs.description AS status, pi.next_communication_date, ' .
					'vw_ci.*, vw_pc.*, vw_pc.id AS post_id, ' .
					'com1.communication_date AS last_communication_date, ' .
					'com2.communication_date AS last_effective_communication_date, ' .
					'f_next_comm_date_period(pi.next_communication_date, 3) AS next_comm_date_period, ' .
					'f_get_post_meeting_count(pi.post_id) AS meeting_count ' .
					'FROM vw_posts_contacts AS vw_pc ' .
					'INNER JOIN tbl_post_initiatives AS pi ON vw_pc.id = pi.post_id ' .
					'INNER JOIN tbl_lkp_communication_status AS lkp_cs ON pi.status_id = lkp_cs.id ' .
					'LEFT JOIN vw_client_initiatives AS vw_ci ON pi.initiative_id = vw_ci.initiative_id ' .
					'LEFT JOIN tbl_communications AS com1 ON pi.last_communication_id = com1.id ' .
					'left join tbl_communications AS com2 ON pi.last_effective_communication_id = com2.id ' .
					'INNER JOIN tbl_campaign_nbms cn_access ON vw_ci.campaign_id = cn_access.campaign_id ' .
					'WHERE vw_pc.company_id = ' . self::$DB->quote($company_id, 'integer') . ' ' .
					'AND vw_ci.initiative_id = ' . self::$DB->quote($initiative_id, 'integer') . ' ' .
//					'AND cn_access.deactivated_date = ' . self::$DB->quote('0000-00-00', 'date') . ' ' .
					'AND cn_access.deactivated_date = \'0000-00-00\' ' .
					'AND cn_access.user_id = ' . self::getCurrentUserId() . ' ' .
					'ORDER BY vw_pc.first_name, vw_pc.surname';
//		echo $query;
		$result = self::$DB->query($query);

		// Note: leave this section as because we do some work with the array before returning it
		$cols = array_keys($result->getColumnNames());

		$results = array();

		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();

			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}

			//if we find post_id input param then push the current $output array to beginning of $results array
			//echo '<br />$output["post_id"] =  ' . $output['post_id'] . ' : post_id = ' . $post_id . '<br />';
			if ($output['post_id'] == $post_id)
			{
				array_unshift($results, $output);
			}
			else
			{
				array_push($results, $output);
			}
		}
		return $results;
	}

	/**
	 * Find client intiatives for a given post_id
	 * @param integer $id post id
	 * @return associative array of raw array mapper info
	 */
	public function findMeetingCountByPostId($post_id)
	{
		$query = 'select count(m.id) ' .
				'from tbl_meetings m ' .
				'join tbl_post_initiatives pi on m.post_initiative_id = pi.id ' .
				'where pi.post_id = :id ';
		$types = array('id' => 'integer');
		$selectPostMeetingCountStmt = self::$DB->prepare($query, $types);

		$values = array('id' => $post_id);
		$result = $this->doStatement($selectPostMeetingCountStmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}

	/**
	 * Find client intiatives for a given post_id
	 * @param integer $id post id
	 * @return associative array of raw array mapper info
	 */
	public function findByMeetingId($meeting_id)
	{
		$query = 'SELECT pi.post_id FROM tbl_meetings AS m ' .
					'INNER JOIN tbl_post_initiatives AS pi ON m.post_initiative_id = pi.id ' .
					'WHERE m.id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($meeting_id);
		$result = $this->doStatement($stmt, $data);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		return $this->doFind($row['post_id']);
	}

	/**
	 * Find post decision maker and agency user information for a given post id for the disciplines 'grid'.
	 * @param integer $post_id
	 * @return string
	 */
	public function findDisciplinesGridByPostIdAndCampaignId($post_id, $campaign_id)
	{

		$query = 'select tc.id as discipline_id, tc.value as discipline, ' .
				'pdm.type_id as decison_maker_type_id, pdm.last_updated_at as dm_last_updated , ' .
				'pau.type_id as agency_user_type_id, pau.last_updated_at as agency_user_last_updated, ' .
				'STR_TO_DATE(concat(pdrd.`year_month`, \'01\'), \'%Y%m%d\') as review_date, ' .
				'pdrd.last_updated_at as review_date_last_updated, cd.id as is_campaign, ' .
				'pia.incumbent_count ' .
				'from tbl_tiered_characteristics tc ' .
				'left join tbl_post_decision_makers pdm on tc.id = pdm.discipline_id ' .
				'and pdm.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_post_agency_users pau on tc.id = pau.discipline_id ' .
				'and pau.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_post_discipline_review_dates pdrd on tc.id = pdrd.discipline_id ' .
				'and pdrd.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join ' .
				'(select count(pia.id) as incumbent_count, post_id, discipline_id from tbl_post_incumbent_agencies pia ' .
				'where pia.post_id = ' . self::$DB->quote($post_id, 'integer') . ' group by post_id, discipline_id) ' .
				'as pia ' .
				'on tc.id = pia.discipline_id ' .
				'and pia.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_campaign_disciplines cd on cd.tiered_characteristic_id = tc.id ' .
				'and cd.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'where cd.id is not null ' .
				'ORDER BY tc.value, cd.id desc';

		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);

	}

	/**
	 * Find post decision maker and agency user information for a given campaign id for the disciplines 'grid'.
	 * @param integer $campaign_id
	 * @return string
	 */
	public function findDisciplinesGridByCampaignId($campaign_id)
	{
		$query = 'select cd.tiered_characteristic_id as discipline_id, tc.value as discipline, ' .
				'pdm.type_id as decison_maker_type_id, pdm.last_updated_at as dm_last_updated , ' .
				'pau.type_id as agency_user_type_id, pau.last_updated_at as agency_user_last_updated, ' .
				'STR_TO_DATE(concat(pdrd.`year_month`, \'01\'), \'%Y%m%d\') as review_date, pdrd.last_updated_at as review_date_last_updated ' .
				'from tbl_campaign_disciplines cd ' .
				'join tbl_tiered_characteristics tc on cd.tiered_characteristic_id = tc.id ' .
				'left join tbl_post_decision_makers pdm on cd.tiered_characteristic_id = pdm.discipline_id ' .
				'left join tbl_post_agency_users pau on cd.tiered_characteristic_id = pau.discipline_id ' .
				'left join tbl_post_discipline_review_dates pdrd on cd.tiered_characteristic_id = pdrd.discipline_id ' .
				'where cd.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'ORDER BY tc.value';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);

	}

	/**
	 * Find post decision maker and agency user information for a given post id which are not in the campaign for the disciplines 'grid'.
	 * @param integer $post_id
	 * @return string
	 */
	public function findNonCampaignDisciplinesGridByPostId($post_id, $campaign_id)
	{

		$query = 'select tc.id as discipline_id, tc.value as discipline, ' .
				'pdm.type_id as decison_maker_type_id, pdm.last_updated_at as dm_last_updated , ' .
				'pau.type_id as agency_user_type_id, pau.last_updated_at as agency_user_last_updated, ' .
				'STR_TO_DATE(concat(pdrd.`year_month`, \'01\'), \'%Y%m%d\') as review_date, pdrd.last_updated_at as review_date_last_updated, ' .
				'cd.id as is_campaign, pia.incumbent_count ' .
				'from tbl_tiered_characteristics tc ' .
				'left join tbl_post_decision_makers pdm on tc.id = pdm.discipline_id and pdm.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_post_agency_users pau on tc.id = pau.discipline_id and pau.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_post_discipline_review_dates pdrd on tc.id = pdrd.discipline_id and pdrd.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join ' .
				'(select count(pia.id) as incumbent_count, post_id, discipline_id ' .
				'from tbl_post_incumbent_agencies pia where pia.post_id = ' . self::$DB->quote($post_id, 'integer') . ' group by post_id, discipline_id) ' .
				'as pia on tc.id = pia.discipline_id and pia.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_campaign_disciplines cd on cd.tiered_characteristic_id = tc.id ' .
				'and cd.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'where cd.id is null ' .
				'and ' .
				'(pdm.discipline_id is not null ' .
				'or pau.discipline_id is not null ' .
				'or pdrd.discipline_id is not null) ' .
				'ORDER BY tc.value, cd.id desc';

//		echo $query;
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);

	}

	/**
	 * Find post decision maker and agency user information for a given post id for the disciplines 'grid'.
	 * @param integer $post_id
	 * @return string
	 */
	public function findDisciplinesGridByPostId($post_id)
	{
		$query = 'select tc.id as discipline_id, tc.value as discipline, ' .
				'pdm.type_id as decison_maker_type_id, pdm.last_updated_at as dm_last_updated , ' .
				'pau.type_id as agency_user_type_id, pau.last_updated_at as agency_user_last_updated, ' .
				'STR_TO_DATE(concat(pdrd.`year_month`, \'01\'), \'%Y%m%d\') as review_date, ' .
				'pdrd.last_updated_at as review_date_last_updated, ' .
				'pia.incumbent_count ' .
				'from tbl_tiered_characteristics tc ' .
				'left join tbl_post_decision_makers pdm on tc.id = pdm.discipline_id ' .
				'and pdm.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_post_agency_users pau on tc.id = pau.discipline_id ' .
				'and pau.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join tbl_post_discipline_review_dates pdrd on tc.id = pdrd.discipline_id ' .
				'and pdrd.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'left join ' .
				'(select count(pia.id) as incumbent_count, post_id, discipline_id from tbl_post_incumbent_agencies pia ' .
				'where pia.post_id = ' . self::$DB->quote($post_id, 'integer') . ' group by post_id, discipline_id) ' .
				'as pia ' .
				'on tc.id = pia.discipline_id ' .
				'and pia.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'where pdm.discipline_id is not null ' .
				'or pau.discipline_id is not null ' .
				'or pdrd.discipline_id is not null ' .
				'ORDER BY tc.value';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Count the number of calls in a given period, filtered by client ID.
	 * @param integer $post_id
	 * @param string $start date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $client_id
	 */
	public function countCallsInPeriod($post_id, $start, $end, $client_id)
	{
		$query = 'SELECT COUNT(comm.id) ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE pi.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
					'AND comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer');
		return self::$DB->queryOne($query);
	}

	/**
	 * Count the number of effectives in a given period, filtered by client ID.
	 * @param integer $post_id
	 * @param string $start date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end date in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $client_id
	 * @return integer
	 */
	public function countEffectivesInPeriod($post_id, $start, $end, $client_id)
	{
		$query = 'SELECT COUNT(comm.id) ' .
					'FROM tbl_communications AS comm ' .
					'INNER JOIN tbl_post_initiatives AS pi ON comm.post_initiative_id = pi.id ' .
					'INNER JOIN tbl_initiatives AS i ON pi.initiative_id = i.id ' .
					'INNER JOIN tbl_campaigns AS cam ON i.campaign_id = cam.id ' .
					'WHERE pi.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
					'AND comm.is_effective = 1 ' .
					'AND communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date >= ' . self::$DB->quote($start, 'timestamp') . ' ' .
					'AND comm.communication_date <= ' . self::$DB->quote($end, 'timestamp') . ' ' .
					'AND cam.client_id = ' . self::$DB->quote($client_id, 'integer');
		return self::$DB->queryOne($query);
	}

    /**
     * Has tag
     */
    public function hasTag($postId, $tagId)
    {
        $query = "SELECT * FROM tbl_post_tags WHERE tag_id = '{$tagId}' AND post_id = '{$postId}'";
		return (bool) self::$DB->queryOne($query);
    }

}

?>