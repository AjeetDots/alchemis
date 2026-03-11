<?php

/**
 * Defines the app_mapper_CampaignReportSummaryMapper class. 
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
class app_mapper_CampaignReportSummaryMapper extends app_mapper_Mapper implements app_domain_CampaignReportSummaryFinder
{
	protected static $DB;

	// Explicitly declare properties to avoid PHP 8.2 dynamic property deprecation
	protected $selectStmt;
	protected $selectAllStmt;
	protected $insertStmt;
	protected $id;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		// Select single
		$query = 'SELECT crs.*, u.name as user_name ' .
				'FROM tbl_campaign_report_summaries crs ' .
				'JOIN tbl_rbac_users u ON crs.user_id = u.id ' .
				'WHERE crs.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);

		// Select All 
		$query = 'SELECT * FROM tbl_campaign_report_summaries';
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
		$obj = new app_domain_CampaignReportSummary($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setSubject($array['subject']);
		$obj->setNote($array['note']);
		$obj->setUpdatedAt($array['updated_at']);
		$obj->setUserId($array['user_id']);
		$obj->setUserName($array['user_name']);
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
		return 'app_domain_CampaignReportSummary';
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_campaign_report_summaries');
		return $this->id;
    }

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_campaign_report_summaries (' .
				'id, campaign_id, subject, note, updated_at, user_id) ' .
				'VALUES ' .
				'(?, ?, ?, ?, ?, ?)';
				
		$types = array('integer', 'integer', 'text', 'text', 'date', 'integer');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getId(), $object->getCampaignId(), $object->getSubject(), 
						$object->getNote(), $object->getUpdatedAt(), $object->getUserId());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_campaign_report_summaries ' .
				'SET ' .
				'campaign_id = ?, ' .
				'subject = ?, ' .
				'note = ?, ' .
				'updated_at = ?, ' .
				'user_id = ? ' .
				'WHERE id = ?';
				
		$types = array('integer', 'text', 'text', 'date', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);

		$data = array($object->getCampaignId(), $object->getSubject(), 
						$object->getNote(), $object->getUpdatedAt(), $object->getUserId(), $object->getId());
		$this->doStatement($updateStmt, $data);	
	
	}
	
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_campaign_report_summaries WHERE id = ?';
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
		$query = 'SELECT crs.*, u.name as user_name ' .
				'FROM tbl_campaign_report_summaries crs ' .
				'JOIN tbl_rbac_users u ON crs.user_id = u.id ' .
				'WHERE crs.campaign_id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CampaignReportSummaryCollection($result, $this);
	}
	
	/**
	 * Get count of all disciplines in a campaign
	 * @return raw data - single item
	 */
	public function findCountByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT count(*) FROM tbl_campaign_report_summaries ' .
				'WHERE campaign_id = ?'; 
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
	}
	
	
	
}

?>