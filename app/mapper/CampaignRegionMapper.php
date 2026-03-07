<?php

/**
 * Defines the app_mapper_CampaignRegionMapper class. 
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
class app_mapper_CampaignRegionMapper extends app_mapper_Mapper implements app_domain_CampaignRegionFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT c.*, r.name FROM tbl_campaign_regions c JOIN tbl_lkp_regions r on c.region_id = r.id WHERE c.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_campaign_regions';
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
		$obj = new app_domain_CampaignRegion($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setRegionId($array['region_id']);
		$obj->setName($array['name']);
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
		return 'app_domain_CampaignRegion';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_campaign_regions');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_campaign_regions (id, campaign_id, region_id) VALUES ' .
				'(?, ?, ?)';
				
		$types = array('integer', 'integer', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getId(), $object->getCampaignId(), $object->getRegionId());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_regions SET campaign_id = ?, region_id = ? ' .
				'WHERE id = ?';
				
		$types = array(	'integer', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getCampaignId(), $object->getRegionId(), $object->getId());
		$this->doStatement($updateStmt, $data);	
	
	}
	
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_campaign_regions WHERE id = ?';
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
		return new app_mapper_CampaignRegionCollection($result, $this);
	}

	/**
	 * Find all contacts.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT c.*, r.name FROM tbl_campaign_regions c JOIN tbl_lkp_regions r on c.region_id = r.id WHERE c.campaign_id = ? ' .
				'ORDER BY r.name';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CampaignNbmCollection($result, $this);
	}
	
	/**
	 * Get count of all nbms in a campaign
	 * @return raw data - single item
	 */
	public function findCountByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT count(*) FROM tbl_campaign_regions ' .
				'WHERE campaign_id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
}

?>