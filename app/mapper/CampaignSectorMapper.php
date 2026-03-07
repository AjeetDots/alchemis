<?php

/**
 * Defines the app_mapper_CampaignSectorMapper class. 
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
class app_mapper_CampaignSectorMapper extends app_mapper_Mapper implements app_domain_CampaignSectorFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT cs.*, IFNULL(concat(tc1.value, \' - \', tc.value), tc.value) as sector_name ' .
				'FROM tbl_campaign_sectors cs ' .
				'JOIN tbl_tiered_characteristics tc ON cs.tiered_characteristic_id = tc.id ' .
				'LEFT JOIN tbl_tiered_characteristics tc1 ON tc.parent_id = tc1.id ' .
				'WHERE cs.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_campaign_sectors';
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
		$obj = new app_domain_CampaignSector($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setSectorId($array['tiered_characteristic_id']);
		$obj->setSectorName($array['sector_name']);
		$obj->setWeighting($array['weighting']);
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
		return 'app_domain_CampaignSector';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_campaign_sectors');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_campaign_sectors (' .
				'id, campaign_id, tiered_characteristic_id, weighting) ' .
				'VALUES ' .
				'(?, ?, ?, ?)';
				
		$types = array('integer', 'integer', 'integer', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getId(), $object->getCampaignId(), $object->getSectorId(), $object->getWeighting());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_sectors ' .
				'SET ' .
				'campaign_id = ?, ' .
				'tiered_characteristic_id = ?, ' .
				'weighting = ? ' .
				'WHERE id = ?';
				
		$types = array(	'integer', 'integer', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getCampaignId(), $object->getSectorId(), $object->getWeighting(), $object->getId());
		$this->doStatement($updateStmt, $data);	
	
	}
	
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_campaign_sectors WHERE id = ?';
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
		return new app_mapper_CampaignSectorCollection($result, $this);
	}

	
	
	/**
	 * Find all campaign sectors for a campaign ordered by weighting (desc)
	 * @return app_mapper_CampaignSetorCollection collection of app_domain_CampaignSector objects
	 */
	public function findByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT cs.*, IFNULL(concat(tc1.value, \' - \', tc.value), tc.value) as sector_name ' .
				'FROM tbl_campaign_sectors cs ' .
				'JOIN tbl_tiered_characteristics tc ON cs.tiered_characteristic_id = tc.id ' .
				'LEFT JOIN tbl_tiered_characteristics tc1 ON tc.parent_id = tc1.id ' .
				'WHERE cs.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'order by tc1.value, tc.value';
				
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CampaignSectorCollection($result, $this);
	}
	
	/**
	 * Find all campaign sectors for a campaign ordered by weighting (desc)
	 * @return app_mapper_CampaignSetorCollection collection of app_domain_CampaignSector objects
	 */
	public function findByCampaignIdOrderByWeighting($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT cs.*, IFNULL(concat(tc1.value, \' - \', tc.value), tc.value) as sector_name ' .
				'FROM tbl_campaign_sectors cs ' .
				'JOIN tbl_tiered_characteristics tc ON cs.tiered_characteristic_id = tc.id ' .
				'LEFT JOIN tbl_tiered_characteristics tc1 ON tc.parent_id = tc1.id ' .
				'WHERE cs.campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
				'order by cs.weighting desc, tc1.value, tc.value';
				
//		echo $query;
		$result = self::$DB->query($query);
		return new app_mapper_CampaignSectorCollection($result, $this);
	}
	
	/**
	 * Get count of all nbms in a campaign
	 * @return raw data - single item
	 */
	public function findCountByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT count(*) FROM tbl_campaign_sectors ' .
				'WHERE campaign_id = ?'; 
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
}

?>