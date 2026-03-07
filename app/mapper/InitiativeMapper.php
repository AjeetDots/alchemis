<?php

/**
 * Defines the app_mapper_InitiativeMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_InitiativeMapper extends app_mapper_ShadowMapper implements app_domain_PostInitiativeFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
//		// Select all
//		$this->selectAllStmt = self::$DB->prepare('SELECT * FROM tbl_post_initiatives');

		// Select single
		$query = 'SELECT * FROM tbl_initiatives WHERE id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Initiative($array['id']);
		$obj->setCampaignId($array['campaign_id']);
		$obj->setName($array['name']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_initiatives');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_initiatives (id, campaign_id, name) VALUES (?, ?, ?)';
		$types = array('integer', 'integer', 'text');
		$this->insertStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		$data = array($object->getId(), $object->getCampaignId(), $object->getName());
		$this->doStatement($this->insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_initiatives SET campaign_id = ?, name = ? WHERE id = ?';
		$types = array('integer', 'text', 'integer');
		$this->updateStmt = self::$DB->prepare($query, $types, MDB2_PREPARE_MANIP);
		$data = array($object->getCampaignId(), $object->getName(), $object->getId());
		$this->doStatement($this->updateStmt, $data);
	}

	/**
	 * Find and instantiate an app_domain_Initiative object. 
	 * @param integer $id initiative ID
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$data = array($id);
		$result = $this->doStatement($this->selectStmt, $data);
		return $this->load($result);
	}
	
	
	/**
	 * Find initiative name in format `client: initiative`
	 * @param integer $initiative_id
	 * @return string
	 */
	public function findClientInitiativeNameById($initiative_id)
	{
		$query = 'SELECT concat(client_name, \': \' , initiative_name) ' .
				'FROM vw_client_initiatives WHERE initiative_id = ' . self::$DB->quote($initiative_id, 'integer');
		$result = self::$DB->query($query);
		$row = $result->fetchRow();
		return $row[0];
	}
	
	
	
	
	
}

?>