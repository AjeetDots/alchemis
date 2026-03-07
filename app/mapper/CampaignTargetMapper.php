<?php

/**
 * Defines the app_mapper_CampaignTargetMapper class. 
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
class app_mapper_CampaignTargetMapper extends app_mapper_ShadowMapper implements app_domain_CampaignTargetFinder
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT * FROM tbl_campaign_targets WHERE id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_campaign_targets';
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
		$obj = new app_domain_CampaignTarget($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setYearMonth($array['year_month']);
		$obj->setCalls($array['calls']);
		$obj->setEffectives($array['effectives']);
		$obj->setMeetingsSet($array['meetings_set']);
		$obj->setMeetingsAttended($array['meetings_attended']);
		$obj->setOpportunities($array['opportunities']);
		$obj->setWins($array['wins']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_campaign_targets');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_campaign_targets (id, campaign_id, `year_month`, calls, effectives, meetings_set, meetings_attended, opportunities, wins) ' .
					'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
				
		$types = array('integer', 'integer', 'text', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types);

		$data = array(	$object->getId(), 
						$object->getCampaignId(), 
						$object->getYearMonth(), 
						$object->getCalls(), 
						$object->getEffectives(), 
						$object->getMeetingsSet(), 
						$object->getMeetingsAttended(),
						$object->getOpportunities(),
						$object->getWins());
		$this->doStatement($this->insertStmt, $data);	
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_targets SET campaign_id = ?, `year_month` = ?, calls = ?, effectives = ?, meetings_set = ?, meetings_attended = ?, opportunities = ?, wins = ? ' .
					'WHERE id = ?';
				
		$types = array('integer', 'text', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types);

		$data = array(	$object->getCampaignId(), 
						$object->getYearMonth(), 
						$object->getCalls(), 
						$object->getEffectives(), 
						$object->getMeetingsSet(), 
						$object->getMeetingsAttended(),
						$object->getOpportunities(),
						$object->getWins(),
						$object->getId());
		$this->doStatement($updateStmt, $data);	
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_campaign_targets WHERE id = ?';
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
	 * Find all campaign_nbms.
	 * @return app_mapper_CampaignNbmCollection collection of app_domain_CampaignNbm objects
	 */
	public function findAll()
	{
		$result = $this->doStatement($this->selectAllStmt, array());
		return new app_mapper_CampaignTargetCollection($result, $this);
	}

	/**
 	 * Find all campaign targets by campaign ID
 	 * @param integer $id campaign ID
	 * @return app_mapper_CampaignTargetCollection collection of app_domain_CampaignTarget objects
	 */
	public function findByCampaignId($campaign_id)
	{
		$query = 'SELECT * FROM tbl_campaign_targets ' .
					'WHERE campaign_id = ' . self::$DB->quote($campaign_id, 'integer') . ' ' .
					'ORDER BY `year_month` DESC';
		$result = self::$DB->query($query);
		return new app_mapper_CampaignTargetCollection($result, $this);
//		$values = array($campaign_id);
//		$query = 'SELECT * FROM tbl_campaign_targets WHERE campaign_id = ? order by `year_month` desc';
//		$types = array('integer');
//		$stmt = self::$DB->prepare($query, $types);
//		$result = $this->doStatement($stmt, $values);
//		return new app_mapper_CampaignTargetCollection($result, $this);
	}

}

?>