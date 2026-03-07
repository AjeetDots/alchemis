<?php

/**
 * Defines the app_mapper_PostIncumbentAgencyMapper class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ShadowMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_PostIncumbentAgencyMapper extends app_mapper_ShadowMapper implements app_domain_PostIncumbentAgencyFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}

		// Select single
		$query = 'SELECT pia.*, c.name as agency_company_name FROM tbl_post_incumbent_agencies pia JOIN tbl_companies c on pia.agency_company_id = c.id ' .
				'WHERE pia.id = ?';
		$types = array('integer');
		$this->selectStmt = self::$DB->prepare($query, $types);
		
		// Select by post id
//		$query = 'SELECT * from tbl_post_incumbent_agencies WHERE post_id = ?';
		$query = 'SELECT pia.*, c.name as agency_company_name FROM tbl_post_incumbent_agencies pia JOIN tbl_companies c on pia.agency_company_id = c.id ' .
				'WHERE post_id = ?';
		$types = array('integer');
		$this->select_by_post_id_stmt = self::$DB->prepare($query, $types);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_PostIncumbentAgency($array['id']);
		$obj->setPostId($array['post_id']);
		$obj->setDisciplineId($array['discipline_id']);
		$obj->setAgencyCompanyId($array['agency_company_id']);
		$obj->setAgencyCompanyName($array['agency_company_name']);
		$obj->setCommunicationId($array['communication_id']);
		$obj->setLastUpdatedAt($array['last_updated_at']);
		$obj->setLastUpdatedBy($array['last_updated_by']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new id to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_post_incumbent_agencies');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_post_incumbent_agencies (id, post_id, discipline_id, agency_company_id, communication_id, ' .
					'last_updated_at, last_updated_by) ' .
					'VALUES ' .
					'(?, ?, ?, ?, ?, ?, ?)';
		$types = array('integer', 'integer', 'integer', 'integer', 'integer', 'date', 'integer');
		$insertStmt = self::$DB->prepare($query, $types);

		$data = array(	$object->getId(), $object->getPostId(), $object->getDisciplineId(), 
						$object->getAgencyCompanyId(), $object->getCommunicationId(), $object->getLastUpdatedAt(), $object->getLastUpdatedBy());
		$this->doStatement($insertStmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$query = 'UPDATE tbl_post_incumbent_agencies ' .
					'SET post_id = ?, discipline_id = ?, agency_company_id = ?, communication_id = ?, last_updated_at = ?, last_updated_by = ? ' .
					'WHERE id = ?';
		$types = array('integer', 'integer', 'integer', 'integer', 'date', 'integer', 'integer');
		$updateStmt = self::$DB->prepare($query, $types);
		
		$data = array(	$object->getPostId(), $object->getDisciplineId(), 
						$object->getAgencyCompanyId(), $object->getCommunicationId(), $object->getLastUpdatedAt(), 
						$object->getLastUpdatedBy(), $object->getId());
		$this->doStatement($updateStmt, $data);
	}


	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_post_incumbent_agencies WHERE id = ?';
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
		$result = $this->doStatement($this->selectStmt, $values);
		return $this->load($result);
	}


	/** Return app_domain_PostIncumbentAgency object by post_id and discipline_id
	 * @param integer $post_id
	 * @param integer $discipline_id
	 * @return app_domain_PostIncumbentAgency 
	 */
	public function findByPostIdAndDisciplineId($post_id, $discipline_id)
	{
		$query = 'SELECT pia.*, c.name as agency_company_name ' .
				'FROM tbl_post_incumbent_agencies pia ' .
				'JOIN tbl_companies c on pia.agency_company_id = c.id ' .
				'WHERE post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'AND discipline_id = ' . self::$DB->quote($discipline_id, 'integer');
		echo $query;
		$result = self::$DB->query($query);
		return $this->load($result);
	}
	
	/** Return app_domain_PostIncumbentAgency collection object by post_id and discipline_id
	 * @param integer $post_id
	 * @param integer $discipline_id
	 * @return app_domain_PostIncumbentAgencyCollection
	 */
	public function findAllByPostIdAndDisciplineId($post_id, $discipline_id)
	{
		$query = 'SELECT pia.*, c.name as agency_company_name ' .
				'FROM tbl_post_incumbent_agencies pia ' .
				'JOIN tbl_companies c on pia.agency_company_id = c.id ' .
				'WHERE pia.post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'AND pia.discipline_id = ' . self::$DB->quote($discipline_id, 'integer');
		$result = self::$DB->query($query);
		return new app_mapper_PostIncumbentAgencyCollection($result, $this);
//		return $this->load($result);
		
	}
	
	/** Return app_domain_PostIncumbentAgency object by post_id, discipline_id and agency company id
	 * @param integer $post_id
	 * @param integer $discipline_id
	 * @param integer $agency_company_id
	 * @return app_domain_PostIncumbentAgency 
	 */
	public function findByPostIdDisciplineIdAndAgencyCompanyId($post_id, $discipline_id, $agency_company_id)
	{
		$query = 'SELECT pia.*, c.name as agency_company_name ' .
				'FROM tbl_post_incumbent_agencies pia ' .
				'JOIN tbl_companies c on pia.agency_company_id = c.id ' .
				'WHERE post_id = ' . self::$DB->quote($post_id, 'integer') . ' ' .
				'AND discipline_id = ' . self::$DB->quote($discipline_id, 'integer') . ' ' .
				'AND agency_company_id = ' . self::$DB->quote($agency_company_id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	
	/** Return app_domain_PostIncumbentAgency collection object by discipline_id and communication_id
	 * @param integer $post_id
	 * @param integer $communication_id
	 * @return app_domain_PostIncumbentAgencyCollection
	 */
	public function findAllByDisciplineIdAndCommunicationId($discipline_id, $communication_id)
	{
		$query = 'SELECT pia.*, c.name as agency_company_name ' .
				'FROM tbl_post_incumbent_agencies pia ' .
				'JOIN tbl_companies c on pia.agency_company_id = c.id ' .
				'WHERE pia.discipline_id = ' . self::$DB->quote($discipline_id, 'integer') . ' ' .
				'AND pia.communication_id = ' . self::$DB->quote($communication_id, 'integer');
		$result = self::$DB->query($query);
		return new app_mapper_PostIncumbentAgencyCollection($result, $this);
		
	}
	
	/** Sets the communication_id field to null for a given communication_id
	 * @param integer $communication_id
	 */
	public function setCommunicationIdNullByCommunicationId($communication_id)
	{
		
		$query = 'UPDATE tbl_post_incumbent_agencies ' .
					'SET communication_id = null ' .
					'WHERE communication_id = ?';
		$types = array('integer');
		$updateStmt = self::$DB->prepare($query, $types);
		
		$data = array($communication_id);
		$this->doStatement($updateStmt, $data);
	}
}

?>