<?php

/**
 * Defines the app_mapper_CampaignNbmMapper class. 
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
class app_mapper_CampaignNbmMapper extends app_mapper_Mapper implements app_domain_CampaignNbmFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT c.*, r.name ' .
				'FROM tbl_campaign_nbms c ' .
				'JOIN tbl_rbac_users r on c.user_id = r.id ' .
				'WHERE c.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_campaign_nbms';
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
		$obj = new app_domain_CampaignNbm($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setUserId($array['user_id']);
		$obj->setIsLeadNbm($array['is_lead_nbm']);
		$obj->setDeactivatedDate($array['deactivated_date']);
		$obj->setName($array['name']);
		$obj->setUserAlias($array['user_alias']);
		$obj->setUserEmail($array['user_email']);
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
		return 'app_domain_CampaignNbm';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_campaign_nbms');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_campaign_nbms (id, campaign_id, user_id, user_alias, user_email, is_lead_nbm, deactivated_date) VALUES ' .
				'(?, ?, ?, ?, ?, ?, ?)';
				
		$types = array('integer', 'integer', 'integer', 'text', 'text', 'integer', 'date');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	$object->getId(), 
						$object->getCampaignId(), 
						$object->getUserId(),
						$object->getUserAlias(),  
						$object->getUserEmail(),  
						$object->getIsLeadNbm(), 
						$object->getDeactivatedDate());
						
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_nbms SET campaign_id = ?, user_id = ?, user_alias = ?, user_email = ?, is_lead_nbm = ?, deactivated_date = ? ' .
				'WHERE id = ?';
				
		$types = array(	'integer', 'integer', 'text', 'text', 'integer', 'date', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array(	$object->getCampaignId(), 
						$object->getUserId(), 
						$object->getUserAlias(), 
						$object->getUserEmail(), 
						$object->getIsLeadNbm(), 
						$object->getDeactivatedDate(), 
						$object->getId());
		$this->doStatement($updateStmt, $data);	
	}
	
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_nbms SET deactivated_date = ? WHERE id = ?';
		$types = array('date', 'integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getDeactivatedDate(), $object->getId());
		$this->doStatement($stmt, $data);
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
		return new app_mapper_CampaignNbmCollection($result, $this);
	}

	/**
	 * Find all campaign nbms by campaign id.
	 * @return app_mapper_CampaignNbmCollection collection of app_mapper_CampaignNbm objects
	 */
	public function findByCampaignId($campaign_id)
	{
		$query = 'SELECT c.*, r.name ' .
				'FROM tbl_campaign_nbms c ' .
				'JOIN tbl_rbac_users r on c.user_id = r.id ' .
				'WHERE c.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'ORDER BY deactivated_date, is_lead_nbm desc, r.name';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CampaignNbmCollection($result, $this);
	}
	
	/**
	* Find all current campaign nbms
	* @return raw array
	*/
	public function findCurrentCampaignUserIdsByCampaign($campaign_id)
	{
		$query = 'SELECT user_id FROM tbl_campaign_nbms cn ' .
						'JOIN tbl_rbac_users r on cn.user_id = r.id ' .
						'WHERE cn.campaign_id = ' .self::$DB->quote($campaign_id, 'integer') . ' ' .
						'AND cn.deactivated_date = \'0000-00-00\' ' .
						'AND r.is_active = 1 ' .  
						'group by cn.user_id ' . 
						'order by cn.user_id';
		$result = self::$DB->query($query);
		// 		echo $query;die();
		return self::mdb2ResultToArray($result);
	}
	
	/**
	 * Find all campaign nbms by campaign id.
	 * @return app_mapper_CampaignNbmCollection collection of app_mapper_CampaignNbm objects
	 */
	public function findByUserId($user_id)
	{
		$query = 'SELECT c.*, r.name ' .
				'FROM tbl_campaign_nbms c ' .
				'JOIN tbl_rbac_users r on c.user_id = r.id ' .
				'WHERE r.id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'ORDER BY deactivated_date, is_lead_nbm desc, r.name';
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CampaignNbmCollection($result, $this);
	}
	
	/**
	 * Get count of all nbms in a campaign
	 * @return raw data - single item
	 */
	public function findCountByCampaignId($campaign_id)
	{
		$query = 'SELECT count(id) FROM tbl_campaign_nbms ' .
				'WHERE campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		return $result = self::$DB->queryOne($query);
	}
	
	/**
	 * find Campaign NBM object by user id and campaign_id?
	 * @return app_domain_CampaignNBM object
	 */
	public function findByUserIdAndCampaignId($user_id, $campaign_id)
	{
		$query = 'SELECT c.*, r.name ' .
				'FROM tbl_campaign_nbms c ' .
				'JOIN tbl_rbac_users r on c.user_id = r.id ' .
				'WHERE c.user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND c.campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}
	
	/**
	 * Is a user_id aleady assigned to a campaign_id?
	 * @return raw data - single item
	 */
	public function findCountByUserIdAndCampaignId($user_id, $campaign_id)
	{
		$query = 'SELECT count(id) FROM tbl_campaign_nbms ' .
				'WHERE user_id = ' . self::$DB->quote($user_id, 'integer') . ' ' .
				'AND campaign_id = ' . self::$DB->quote($campaign_id, 'integer');
		return $result = self::$DB->queryOne($query);
	}
	
	public function findLeadNbmByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT c.*, r.name FROM tbl_campaign_nbms c JOIN tbl_rbac_users r on c.user_id = r.id ' .
				'WHERE c.campaign_id = ? and is_lead_nbm = 1';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return $this->load($result);
	}

	/** Find all campaigns by user.
	 * @return app_mapper_CampaignCollection collection of app_domain_Campaign objects
	 */
	public function findCampaignInitiativesByUserId($user_id)
	{
		$values = array($user_id);
		$query = 'SELECT i.id as initiative_id, concat(cl.name, \': \', i.name) as client_initiative_display ' .
				'FROM tbl_campaigns c ' .
				'JOIN tbl_clients cl on c.client_id = cl.id ' .
				'JOIN tbl_campaign_nbms cn ON c.id = cn.campaign_id ' .
				'JOIN tbl_rbac_users r ON cn.user_id = r.id ' .
				'JOIN tbl_initiatives i on c.id = i.campaign_id ' .
				'WHERE r.id = ? ' .
				'AND cn.deactivated_date = \'0000-00-00\' ' .
				'ORDER BY cl.name';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return self::mdb2ResultToArray($result);
	}
	
	/** Find all campaigns by current user
	 * @return app_mapper_CampaignCollection collection of app_domain_Campaign objects
	 */
	public function findCampaignInitiativesByCurrentUser()
	{
		return $this->findCampaignInitiativesByUserId(self::getCurrentUserId());
	}
	
// 	/**
// 	* Lookup a row based on an email address - assumes only one unique email in the table
// 	* @return raw data - single row
// 	*/
// 	public function findByCampaignNbmEmail($email)
// 	{
// 		$query = 'SELECT * FROM tbl_campaign_nbms ' .
// 					'WHERE user_email = ' . self::$DB->quote($email, 'text');
					
// 		return $result = self::$DB->queryRow($query, null, MDB2_FETCHMODE_ASSOC);
		
		
// 	}
			
}

?>