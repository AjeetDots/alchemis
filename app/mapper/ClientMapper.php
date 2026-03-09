<?php

require_once('app/mapper/Mapper.php');

/**
 * @package alchemis
 */
class app_mapper_ClientMapper extends app_mapper_Mapper implements app_domain_ClientFinder
{
	protected static $DB;
	protected $selectAllStmt;
	protected $selectAllClientInitiativesStmt;
	protected $selectStmt;
	protected $selectSetStmt;
	protected $selectClientByInitiativeIdStmt;
	protected $selectClientByPostInitiativeIdStmt;
	protected $countStmt;
	protected $id;

	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select all
		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_clients ORDER BY name');
		
		
		// Select all client intiatives
		$this->selectAllClientInitiativesStmt = self::$DB->prepare('SELECT ' .
				'vw.initiative_id, vw.client_name, vw.initiative_name, concat(vw.client_name, \': \', vw.initiative_name) as client_initiative_display ' .
				'FROM vw_client_initiatives vw ORDER BY client_name, initiative_name');
		
		// Select single
		$query = 'SELECT * FROM tbl_clients WHERE id = :id';
		$types = array('id' => 'integer');
		$this->selectStmt = self::$DB->prepare($query, $types);
		
		// Select set
		$query = 'SELECT * FROM tbl_clients LIMIT :offset,:limit';
		$types = array('offset' => 'integer', 'limit' => 'integer');
		$this->selectSetStmt = self::$DB->prepare($query, $types);
		
		// Select by initiative id
		$query = 'SELECT cl.* FROM tbl_clients cl join tbl_campaigns cam on cl.id = cam.client_id ' .
				'join tbl_initiatives i on cam.id = i.campaign_id ' .
				'where i.id = :initiative_id';
		$types = array('initiative_id' => 'integer');
		$this->selectClientByInitiativeIdStmt = self::$DB->prepare($query, $types);
		
		// Select by post initiative id
		$query = 'select client_id as id, client_name as name from vw_client_initiatives vw_ci ' .
				'join tbl_post_initiatives pi on vw_ci.initiative_id = pi.initiative_id ' .
				'where pi.id = :post_initiative_id';
		$types = array('post_initiative_id' => 'integer');
		$this->selectClientByPostInitiativeIdStmt = self::$DB->prepare($query, $types);
		
		// Count
		$this->countStmt = self::$DB->prepare('SELECT COUNT(*) FROM tbl_clients');
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Client($array['id']);
		$obj->setName($array['name']);
		$obj->setIsCurrent($array['is_current']);
		$obj->setAddress1($array['address_1']);
		$obj->setAddress2($array['address_2']);
		$obj->setAddress3($array['address_3']);
		$obj->setTown($array['town']);
		$obj->setPostcode($array['postcode']);
		$obj->setCountyId($array['county_id']);
		$obj->setCountryId($array['country_id']);
		$obj->setTelephone($array['telephone']);
		$obj->setFax($array['fax']);
		$obj->setWebsite($array['website']);
		$obj->setFinancialYearStart($array['financial_year_start']);
		$obj->setPrimaryContactName($array['primary_contact_name']);
		$obj->setPrimaryContactJobTitle($array['primary_contact_job_title']);
		$obj->setPrimaryContactTelephone($array['primary_contact_telephone']);
		$obj->setPrimaryContactEmail($array['primary_contact_email']);
		$obj->setSecondaryContactName($array['secondary_contact_name']);
		$obj->setSecondaryContactJobTitle($array['secondary_contact_job_title']);
		$obj->setSecondaryContactTelephone($array['secondary_contact_telephone']);
		$obj->setSecondaryContactEmail($array['secondary_contact_email']);
		$obj->setPublishDiary($array['publish_diary']);
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
		return 'app_domain_Client';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_clients');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
		function doInsert(app_domain_DomainObject $object)
	{
		// Insert
		$query = 'INSERT INTO tbl_clients ' .
				'(id, name, is_current, address_1, ' .
				'address_2, address_3, town, ' .
				'county_id, postcode, ' .
				'country_id, telephone, fax, ' .
				'website, financial_year_start, primary_contact_name, ' .
				'primary_contact_job_title, primary_contact_telephone, primary_contact_email, ' .
				'secondary_contact_name, secondary_contact_job_title, secondary_contact_telephone, ' .
				'secondary_contact_email, publish_diary) ' .
				'VALUES ' .
				'(' .
				self::$DB->quote($object->getId(), 'integer') . ', ' .
				self::$DB->quote($object->getName(), 'text') . ', ' .
				self::$DB->quote($object->getIsCurrent(), 'integer') . ', ' .
				self::$DB->quote($object->getAddress1(), 'text') . ', ' .
				self::$DB->quote($object->getAddress2(), 'text') . ', ' .
				self::$DB->quote($object->getAddress3(), 'text') . ', ' .
				self::$DB->quote($object->getTown(), 'text') . ', ' .
				self::$DB->quote($object->getCountyId(), 'integer') . ', ' .
				self::$DB->quote($object->getPostcode(), 'text') . ', ' .
				self::$DB->quote($object->getCountryId(), 'integer') . ', ' .
				self::$DB->quote($object->getTelephone(), 'text') . ', ' .
				self::$DB->quote($object->getFax(), 'text') . ', ' .
				self::$DB->quote($object->getWebsite(), 'text') . ', ' .
				self::$DB->quote($object->getFinancialYearStart(), 'date') . ', ' .
				self::$DB->quote($object->getPrimaryContactName(), 'text') . ', ' .
				self::$DB->quote($object->getPrimaryContactJobTitle(), 'text') . ', ' .
				self::$DB->quote($object->getPrimaryContactTelephone(), 'text') . ', ' .
				self::$DB->quote($object->getPrimaryContactEmail(), 'text') . ', ' .
				self::$DB->quote($object->getSecondaryContactName(), 'text') . ', ' .
				self::$DB->quote($object->getSecondaryContactJobTitle(), 'text') . ', ' .
				self::$DB->quote($object->getSecondaryContactTelephone(), 'text') . ', ' .
				self::$DB->quote($object->getSecondaryContactEmail(), 'text') . ', ' .
				self::$DB->quote($object->getPublishDiary(), 'integer') . ')' ;
		
		self::$DB->query($query);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		// Update
		$query = 	'UPDATE tbl_clients ' .
					'SET ' .
					'name = ?, is_current = ?, address_1 = ?, address_2 = ?, ' .
					'address_3 = ?, town = ?, county_id = ?, ' .
					'postcode = ?, country_id = ?, ' .
					'telephone = ?, fax = ?, website = ?, ' .
					'financial_year_start = ?, primary_contact_name = ?, primary_contact_job_title = ?, ' .
					'primary_contact_telephone = ?, primary_contact_email = ?, secondary_contact_name = ?, ' .
					'secondary_contact_job_title = ?, secondary_contact_telephone = ?, secondary_contact_email = ?, ' .
					'publish_diary = ? ' .
					'WHERE id = ?';
		$types = array('text', 'integer', 'text', 'text', 
						'text', 'text',	'integer',
						'text', 'integer', 
						'text', 'text',	'text', 
						'date', 'text', 'text', 
						'text', 'text',	'text',	
						'text', 'text',	'text', 
						'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types);
		$values = array($object->getName(), $object->getIsCurrent(), $object->getAddress1(),	$object->getAddress2(), 
						$object->getAddress3(),  $object->getTown(), $object->getCountyId(), 
						$object->getPostcode(), $object->getCountryId(), 
						$object->getTelephone(), $object->getFax(),	$object->getWebsite(), 
						$object->getFinancialYearStart(), $object->getPrimaryContactName(),	$object->getPrimaryContactJobTitle(), 
						$object->getPrimaryContactTelephone(), $object->getPrimaryContactEmail(), $object->getSecondaryContactName(), 
						$object->getSecondaryContactJobTitle(), $object->getSecondaryContactTelephone(), $object->getSecondaryContactEmail(),
						$object->getPublishDiary(),
						$object->getId());
		$this->doStatement($updateStmt, $values);
		
		// if user has been marked as in_active then unset any related client permissions
		$campaign_nbms = app_domain_CampaignNbm::findByCampaignId($object->getCampaignId());
		foreach ($campaign_nbms as $campaign_nbm)
		{
			if (!$object->getIsCurrent())
			{
				// make this next check so that we don't lose any information
				// about records where the deactivated date has already been set
				if ($campaign_nbm->getDeactivatedDate() == '0000-00-00')
				{
					$campaign_nbm->setDeactivatedDate(date('Y-m-d H:i:s'));
					$campaign_nbm->commit();
				}
			}
			
		}
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
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
	 * Find all clients.
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_ClientCollection($result, $this);
	}

	/**
	 * Find all active clients.
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public function findAllActive()
	{
		$query = 'SELECT cli.* FROM tbl_clients AS cli ' .
					'INNER JOIN tbl_campaigns AS cam ON cli.id = cam.client_id ' .
					'WHERE cli.is_current = 1 ' .
					'ORDER BY name';
		$result = self::$DB->query($query);
		return new app_mapper_ClientCollection($result, $this);
	}

 	/**
 	 * Find all client initiatives.
	 * @return array
	 */
	public function findAllClientInitiatives()
	{
		$result = $this->doStatement($this->selectAllClientInitiativesStmt, array());
		$cols = array_keys($result->getColumnNames());
		$results = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();
			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}
			$results[] = $output;
		}
		return $results;
	}
	
	/**
 	 * Find initiatives for a given client.
 	 * @param integer $client_id
 	 * @return array
	 */
	public function findClientInitiatives($client_id)
	{
		$sql = 'SELECT vw.initiative_id, vw.client_name, vw.initiative_name, CONCAT(vw.client_name, \': \', vw.initiative_name) AS client_initiative_display ' .
				'FROM vw_client_initiatives AS vw ' .
				'WHERE vw.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
				'ORDER BY vw.client_name, vw.initiative_name';
		$result = self::$DB->query($sql);
		$cols = array_keys($result->getColumnNames());
		$results = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$output = array();
			foreach ($cols as $col)
			{
				$output[$col] = $row[$col];
			}
			$results[] = $output;
		}
		return $results;
	}

	/**
	 * Find all clients limited to a offset.
	 * @param integer $limit the maximum number of rows to return
	 * @param integer $offset the offset of the first row to return (initial row is 0 not 1)
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public function findSet($limit, $offset)
	{
		$data = array('offset' => $offset, 'limit' => $limit);
		$result = $this->doStatement($this->selectSetStmt, $data);
		return new app_mapper_ClientCollection($result, $this);
	}


	/** Find client by initiative id.
	 * @param integer $initiative_id
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public function findByInitiativeId($initiative_id)
	{
		$data = array('initiative_id' => $initiative_id);
		$result = $this->doStatement($this->selectClientByInitiativeIdStmt, $data);
		return $this->load($result);
//		$coll = new app_mapper_ClientCollection($result, $this);
//		return $coll->toRawArray();
//		return new app_mapper_ClientCollection($result, $this);
	}
	
	
	/** Find client by post initiative id.
	 * @param integer $post_initiative_id
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public function findByPostInitiativeId($post_initiative_id)
	{
		$data = array('post_initiative_id' => $post_initiative_id);
		$result = $this->doStatement($this->selectClientByPostInitiativeIdStmt, $data);
		return $this->load($result);
	}
	
	/**
	 * Returns the total number of clients.
	 * @param integer
	 */
	public function count()
	{
		$result = $this->doStatement($this->countStmt, array());
		$row = $result->fetchRow();
		return $row[0];
	}

	
	/**
	 * Return a client name for a given ID.
	 * @param integer $client_id
	 * @return string
	 */
	public function lookupClientNameById($client_id)
	{
		$query = 'SELECT name FROM tbl_clients where id = ' . self::$DB->quote($client_id, 'integer');
		return self::$DB->queryOne($query);
	}

	/**
	 * Find clients associated with a given user.
	 * @param integer $user_id
	 * @return app_mapper_ClientCollection collection of app_domain_Client objects
	 */
	public function findByUserId($user_id)
	{
		$query = 'SELECT cam.*, cli.name AS client_name ' .
					'FROM tbl_campaigns AS cam ' .
					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
					'INNER JOIN tbl_campaign_nbms AS cn ON cam.id = cn.campaign_id ' .
					'WHERE cn.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
					'AND cli.is_current = 1 ' .
					'ORDER BY cli.name';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Lookup top line summary statistics
	 * @param integer $client_id
	 * @param string $year_month
	 * @return array
	 */
	public function findTopLineSummaryStatistics($client_id, $year_month = null)
	{
		if (is_null($year_month))
		{
			$year_month = date('Ym');
		}
		$query = 'SELECT ds.campaign_id, ds.`year_month`, ' .
					'SUM(ds.call_count) AS call_count, ' .
					'SUM(ds.call_effective_count) AS call_effective_count, ' .
					'SUM(ds.meeting_set_count) AS meeting_set_count, ' .
					'SUM(ds.meeting_attended_count) AS meeting_attended_count, ' .
					'((SUM(ds.meeting_set_count) / SUM(ds.call_effective_count)) * 100) AS conversion ' .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
					'WHERE cli.id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'AND ds.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
					'GROUP BY ds.campaign_id';
		$result = self::$DB->query($query);
//		return self::mdb2ResultToArray($result);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		return $row;
	}

	/**
	 * Lookup targets for a client in a given month
	 * @param integer $client_id
	 * @param string $year_month
	 * @return array
	 */
	public function findTargetsByClientIdAndYearmonth($client_id, $year_month = null)
	{
		if (is_null($year_month))
		{
			$year_month = date('Ym');
}
		$sql = 'SELECT tar.campaign_id, tar.`year_month`, ' .
				'SUM(tar.calls) AS call_target, ' .
				'SUM(tar.effectives) AS call_effective_target, ' .
				'SUM(tar.meetings_set) AS meeting_set_target, ' .
				'SUM(tar.meetings_attended) AS meeting_attended_target, ' .
				'((SUM(tar.meetings_set) / SUM(tar.effectives)) * 100) AS conversion ' .
				'FROM tbl_campaign_targets AS tar ' .
				'WHERE tar.campaign_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
				'AND tar.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
				'GROUP BY tar.campaign_id, tar.`year_month`';
		return self::$DB->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	 * Lookup actual results for a client in a given month
	 * @param integer $client_id
	 * @param string $year_month
	 * @return array
	 */
	public function findActualsByClientIdAndYearmonth($client_id, $year_month = null)
	{
		if (is_null($year_month))
		{
			$year_month = date('Ym');
		}
		$sql = 'SELECT ds.campaign_id, ds.`year_month`, ' .
					'SUM(ds.call_count) AS call_count, ' .
					'SUM(ds.call_effective_count) AS call_effective_count, ' .
					'SUM(ds.meeting_set_count) AS meeting_set_count, ' .
					'SUM(ds.meeting_attended_count) AS meeting_attended_count, ' .
					'((SUM(ds.meeting_set_count) / SUM(ds.call_effective_count)) * 100) AS conversion ' .
					'FROM tbl_data_statistics AS ds ' .
					'INNER JOIN tbl_campaigns AS cam ON ds.campaign_id = cam.id ' .
					'INNER JOIN tbl_clients AS cli ON cam.client_id = cli.id ' .
					'WHERE cli.id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'AND ds.`year_month` = ' . self::$DB->quote($year_month, 'text') . ' ' .
					'GROUP BY ds.campaign_id';
		return self::$DB->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
	}

	/**
	* find client ids where publish_diary = 1
	* @return array
	*/
	public function findClientIdsByPublishDiary()
	{
		$sql = 'SELECT id ' .
				'FROM tbl_clients AS cli ' .
				'ORDER BY cli.id';
		return self::$DB->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	}
}

?>