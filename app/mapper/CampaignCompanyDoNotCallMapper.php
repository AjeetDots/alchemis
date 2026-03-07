<?php

require_once('app/mapper/ShadowMapper.php');

/**
 * 
 */
class app_mapper_CampaignCompanyDoNotCallMapper extends app_mapper_ShadowMapper implements app_domain_CampaignCompanyDoNotCallFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select all
		$query = 'SELECT ccdnc.*, c.name AS company_name, u.name as created_by_name ' .
					'FROM tbl_campaign_companies_do_not_call ccdnc ' .
					'INNER JOIN tbl_companies c ON c.id = ccdnc.company_id ' .
					'INNER JOIN tbl_rbac_users u on u.id = ccdnc.created_by ' .
					'ORDER BY c.name';
		$this->selectAllStmt = self::$DB->prepare($query);

		// Select single
		$query = 'SELECT ccdnc.*, c.name AS company_name ' .
					'FROM tbl_campaign_companies_do_not_call ccdnc ' .
					'INNER JOIN tbl_companies c ON c.id = ccdnc.company_id ' .
					'WHERE ccdnc.id = :id';
					
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
		$obj = new app_domain_CampaignCompanyDoNotCall($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setCompanyId($array['company_id']);
		$obj->setCompanyName($array['company_name']);
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
		return 'app_domain_CampaignCompanyDoNotCall';
	}
	
		
	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_campaign_companies_do_not_call');
		return $this->id;
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		
		// Insert
		$query = 'INSERT INTO tbl_campaign_companies_do_not_call ' .
				'(id, campaign_id, company_id, created_at, created_by) ' .
				'VALUES ' .
				'(?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'integer', 'date', 'integer');
		$result_types = MDB2_PREPARE_MANIP;
		$insertStmt = self::$DB->prepare($query, $types, $result_types);
		
		$values = array($object->getId(),
						$object->getCampaignId(), 
						$object->getCompanyId(), 
						$object->getCreatedAt(),
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
			
		// Update
		$query = 	'UPDATE tbl_campaign_companies_do_not_call ' .
					'SET ' .
					'campaign_id = ? ' .
					'company_id = ? ' .
					'created_at = ? ' .
					'created_by = ? ' .
					'WHERE id = ?';
		$types = array('integer', 'integer', 'date', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types);
		$values = array($object->getCampaignId(), 
						$object->getCompanyId(), 
						$object->getCreatedAt(),
						$object->getCreatedBy(), 
						$object->getId());
						
		$this->doStatement($updateStmt, $values);
		
	}
		
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_campaign_companies_do_not_call WHERE id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($stmt, $data);
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
		return new app_mapper_CampaignCompanyDoNotCallCollection($result, $this);
	}
	
	/**
	 * 
	 * @param integer $clientId the client ID
	 */
	public function findByCampaignId($campaign_id)
	{
		$query = 'SELECT ccdnc.*, c.name AS company_name ' .
					'FROM tbl_campaign_companies_do_not_call ccdnc ' .
					'INNER JOIN tbl_companies c ON c.id = ccdnc.company_id ' .
					'WHERE ccdnc.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
					'ORDER BY c.name, c.id';
		$result = self::$DB->query($query);
		return new app_mapper_CampaignCompanyDoNotCallCollection($result, $this);
	}

	/**
	 * 
	 * @param integer $clientId the client ID
	 */
	public function findByClientId($client_id)
	{
		$query = 'SELECT ccdnc.*, c.name AS company_name ' .
					'FROM tbl_campaign_companies_do_not_call ccdnc ' .
					'INNER JOIN tbl_companies c ON c.id = ccdnc.company_id ' .
					'INNER JOIN tbl_campaigns cam ON cam.id = ccdnc.campaign_id ' .
					'WHERE cam.client_id = ' . self::$DB->quote($client_id, 'integer') . ' ' .
					'ORDER BY c.name, c.id';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CampaignCompanyDoNotCallCollection($result, $this);
	}
	
	/**
	 * @param integer $user_id 
	 */
	public function findCountByCampaignId($campaign_id)
	{
		$query = 'SELECT COUNT(ccdmc.id) ' .
					'FROM tbl_campaign_companies_do_not_call ccdnc ' .
					'WHERE ccdnc.campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
					
		$result = self::$DB->query($query);
		return new app_mapper_CampaignCompanyDoNotCallCollection($result, $this);
	}
	
	/**
	 * Find the list of marketing services available for the parent campaign of this initiative 
	 * (ie not yet assigned to the parent campaign).
	 * @param integer $initiative_id
	 * @param integer $company_id
	 * @return array
	 */
	public static function isCompanyDoNotCall($initiative_id, $company_id)
	{
		$query = 'SELECT count(cdnc.id) ' .
				'FROM tbl_campaign_companies_do_not_call AS cdnc ' .
				'JOIN tbl_initiatives i on i.campaign_id = cdnc.campaign_id ' .
//				'WHERE cdnc.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'WHERE i.id = ' . self::$DB->quote($initiative_id, 'integer') . ' ' .
				'AND cdnc.company_id = ' . self::$DB->quote($company_id, 'integer');
//		echo ($query);
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
	
	
}

?>