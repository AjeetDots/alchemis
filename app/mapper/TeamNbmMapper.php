<?php

/**
 * Defines the app_mapper_TeamNbmMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
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
class app_mapper_TeamNbmMapper extends app_mapper_Mapper implements app_domain_TeamNbmFinder
{
	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_TeamNbm($array['id']);
		$obj->setTeamId($array['team_id']);
		$obj->setUserId($array['user_id']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
    {
    	$this->id = self::$DB->nextID('tbl_team_nbms');
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
			$query = 'INSERT INTO tbl_team_nbms (id, team_id, user_id) VALUES (?, ?, ?)';
			$types = array('integer', 'integer', 'integer');
			$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		}
		$data = array($object->getId(), $object->getTeamId(), $object->getUserId());
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
			$query = 'UPDATE tbl_team_nbms SET team_id = ?, user_id = ? WHERE id = ?';
			$types = array(	'integer', 'integer', 'integer');
			$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		}
		$data = array($object->getTeamId(), $object->getUserId(), $object->getId());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		if (!isset($this->deleteStmt))
		{
			$query = 'DELETE FROM tbl_team_nbms WHERE id = ?';
			$types = array('integer');
			$this->deleteStmt = self::$DB->prepare($query, $types);
		}
		$data = array($object->getId());
		$this->doStatement($this->deleteStmt, $data);
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		if (!isset($this->selectStmt))
		{
			$query = 'SELECT tn.*, r.name FROM tbl_team_nbms AS tn JOIN tbl_rbac_users AS r ON tn.user_id = r.id WHERE tn.id = ?';
			$types = array('integer');
			$this->selectStmt = self::$DB->prepare($query, $types);
		}
		$data = array($id);
		$result = $this->doStatement($this->selectStmt, $data);
		return $this->load($result);
	}

	/**
	 * Find all campaign_nbms.
	 * @return app_mapper_CampaignNbmCollection collection of app_domain_CampaignNbm objects
	 */
	public function findAll()
	{
		$query = 'SELECT * FROM tbl_teamampaign_nbms';
		$result = self::$DB->query($query);
		return new app_mapper_TeamNbmCollection($result);
	}

	/**
	 * Find all contacts.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findByTeamId($team_id)
	{
		$values = array($team_id);
		$query = 'SELECT c.*, r.name FROM tbl_campaign_nbms c JOIN tbl_rbac_users r on c.user_id = r.id WHERE c.campaign_id = ? ' .
				'ORDER BY is_active desc, is_lead_nbm desc, r.name';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		return new app_mapper_CampaignNbmCollection($result, $this);
	}

	/**
	 * Find all contacts.
	 * @return app_mapper_ContactCollection collection of app_domain_Contact objects
	 */
	public function findIdByUserId($user_id)
	{
		$query = 'SELECT id FROM tbl_team_nbms WHERE user_id = ' . self::$DB->quote($user_id, 'integer');
		$result = self::$DB->query($query);
		return $result->fetchOne(0, 0);
	}

	/**
	 * Get count of all nbms in a campaign
	 * @return raw data - single item
	 */
	public function findCountByCampaignId($campaign_id)
	{
		$values = array($campaign_id);
		$query = 'SELECT count(*) FROM tbl_campaign_nbms ' .
				'WHERE campaign_id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$result = $this->doStatement($stmt, $values);
		$row = $result->fetchRow();
		return $row[0];
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
}

?>