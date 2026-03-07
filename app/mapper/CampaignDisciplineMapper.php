<?php

/**
 * Defines the app_mapper_CampaignDisciplineMapper class. 
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
class app_mapper_CampaignDisciplineMapper extends app_mapper_Mapper implements app_domain_CampaignDisciplineFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT cd.*, tc.value as discipline_name ' .
				'FROM tbl_campaign_disciplines cd ' .
				'JOIN tbl_tiered_characteristics tc ON cd.tiered_characteristic_id = tc.id ' .
				'WHERE cd.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_campaign_disciplines';
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
		$obj = new app_domain_CampaignDiscipline($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setDisciplineId($array['tiered_characteristic_id']);
		$obj->setDisciplineName($array['discipline_name']);
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
		return 'app_domain_CampaignDiscipline';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_campaign_disciplines');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_campaign_disciplines (' .
				'id, campaign_id, tiered_characteristic_id) ' .
				'VALUES ' .
				'(?, ?, ?)';
				
		$types = array('integer', 'integer', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getId(), $object->getCampaignId(), $object->getDisciplineId());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_disciplines ' .
				'SET ' .
				'campaign_id = ?, ' .
				'tiered_characteristic_id = ? ' .
				'WHERE id = ?';
				
		$types = array(	'integer', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getCampaignId(), $object->getDisciplineId(), $object->getId());
		$this->doStatement($updateStmt, $data);	
	
	}
	
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_campaign_disciplines WHERE id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
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
	 * Find all campaign regions.
	 * @return app_mapper_CampaignRegionCollection collection of app_domain_CampaignRegion objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_CampaignDisciplineCollection($result, $this);
	}

	/**
	 * Find all disciplines for a campaign.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT cd.*, tc.value as discipline_name ' .
				'FROM tbl_campaign_disciplines cd ' .
				'JOIN tbl_tiered_characteristics tc ON cd.tiered_characteristic_id = tc.id ' .
				'WHERE cd.campaign_id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CampaignDisciplineCollection($result, $this);
	}
	
	/**
	 * Get count of all disciplines in a campaign
	 * @return raw data - single item
	 */
	public function findCountByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT count(*) FROM tbl_campaign_disciplines ' .
				'WHERE campaign_id = ?'; 
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
		
	/**
	 * Find the list of marketing services from tbl_tiered_characteristics.
	 * @return array
	 */
	public static function findAllDisciplines()
	{
		
		$query = 'SELECT * ' .
				'FROM tbl_tiered_characteristics AS tc ' .
				'WHERE tc.parent_id =  18 ' .
				'ORDER BY tc.value';
		
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
			
	/**
	 * Find the list of marketing services available for an campaign (ie not yet assigned to a campaign).
	 * @param integer $campaign_id
	 * @return array
	 */
	public static function findAvailableDisciplinesByCampaignId($campaign_id)
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
	
}

?>