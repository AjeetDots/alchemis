<?php

/**
 * Defines the app_mapper_ObjectCharacteristicMapper class. 
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
class app_mapper_ObjectCharacteristicMapper extends app_mapper_Mapper
{
	protected static $DB;
	
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		$this->init();
	}
	
	protected function init()
	{
		// Select single
		$query = 'SELECT * FROM tbl_object_characteristics WHERE id = ?';
		$this->select_stmt = self::$DB->prepare($query);
		
		// Insert
		$query = 'INSERT INTO tbl_object_characteristics ' .
					'(id, characteristic_id, company_id, post_id, post_initiative_id) ' .
					'VALUES (?, ?, ?, ?, ?)';
		$this->insert_stmt = self::$DB->prepare($query);
		
		// Update
		$query = 'UPDATE tbl_object_characteristics ' .
					'SET characteristic_id = ?, company_id = ?, post_id = ?, post_initiative_id = ? ' .
					'WHERE id = ?';
		$this->update_stmt = self::$DB->prepare($query);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array) {}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_object_characteristics');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		switch ($object->getParentObjectType())
		{
			case 'app_domain_Company';
				$data = array($object->getId(), $object->getCharacteristicId(), 
								$object->getParentObjectId(), NULL, NULL);
				break;
			
			case 'app_domain_Post';
				$data = array($object->getId(), $object->getCharacteristicId(), 
								NULL, $object->getParentObjectId(), NULL);
				break;
			
			case 'app_domain_PostInitiative';
				$data = array($object->getId(), $object->getCharacteristicId(), 
								NULL, NULL, $object->getParentObjectId());
				break;
		}
		$this->doStatement($this->insert_stmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		switch ($object->getParentObjectType())
		{
			case 'app_domain_Company';
				$data = array($object->getCharacteristicId(), $object->getParentObjectId(), NULL, NULL, 
								$object->getValue(), $object->getId());
				break;
			
			case 'app_domain_Post';
				$data = array($object->getCharacteristicId(), NULL, $object->getParentObjectId(), NULL, 
								$object->getValue(), $object->getId());
				break;
			
			case 'app_domain_PostInitiative';
				$data = array($object->getCharacteristicId(), NULL, NULL, $object->getParentObjectId(), 
								$object->getValue(), $object->getId());
				break;
		}
		$this->doStatement($this->update_stmt, $data);
	}

	
	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		// Get characteristic ID
		$sql = 'SELECT characteristic_id FROM tbl_object_characteristics WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		$characteristic_id = self::$DB->queryOne($sql);
		
		// Get company ID
		$sql = 'SELECT company_id FROM tbl_object_characteristics WHERE id = ' . self::$DB->quote($object->getId(), 'integer');
		$company_id = self::$DB->queryOne($sql);

		// If it is a boolean, date or text characteristic, we also need to delete the record from the relevant child table, 
		// e.g. tbl_object_characteristics_boolean, tbl_object_characteristics_date, tbl_object_characteristics_text
		
		$class_name = get_class($object);
		if ($class_name == 'app_domain_ObjectCharacteristicBoolean')
		{
			$query = 'DELETE FROM tbl_object_characteristics_boolean WHERE characteristic_id = ? AND company_id = ?';
			$types = array('integer', 'integer');
			$stmt = self::$DB->prepare($query, $types);
			$data = array($characteristic_id, $company_id);
			$this->doStatement($stmt, $data);
		}
		elseif ($class_name == 'app_domain_ObjectCharacteristicDate')
		{
			$query = 'DELETE FROM tbl_object_characteristics_date WHERE characteristic_id = ? AND company_id = ?';
			$types = array('integer', 'integer');
			$stmt = self::$DB->prepare($query, $types);
			$data = array($characteristic_id, $company_id);
			$this->doStatement($stmt, $data);
		}
		elseif ($class_name == 'app_domain_ObjectCharacteristicText')
		{
			$query = 'DELETE FROM tbl_object_characteristics_text WHERE characteristic_id = ? AND company_id = ?';
			$types = array('integer', 'integer');
			$stmt = self::$DB->prepare($query, $types);
			$data = array($characteristic_id, $company_id);
			$this->doStatement($stmt, $data);
		}

		// Now do the delete from the main linking table
		$query = 'DELETE FROM tbl_object_characteristics WHERE id = ?'; 
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getId());
		$this->doStatement($stmt, $data);
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper()
	 */
	public function doFind($id)
	{
		$data = array($id); 
		$result = $this->doStatement($this->select_stmt, $data);
		return $this->load($result);
	}

	/**
	 * Gets the value for a given characteristic / company combination.
	 * @param integer $characteristic_id
	 * @param integer $company_id
	 * return boolean
	 */
	public function getValueByCompanyId($characteristic_id, $company_id)
	{
		$data = array($characteristic_id, $company_id);
		$result = $this->doStatement($this->select_by_company_id_stmt, $data);
		$raw = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$raw[] = $row;
			$result->nextResult();
		}
		return $raw;
	}

	/**
	 * Gets the value for a given characteristic / post combination.
	 * @param integer $characteristic_id
	 * @param integer $post_id
	 * return boolean
	 */
	public function getValueByPostId($characteristic_id, $post_id)
	{
		$data = array($characteristic_id, $post_id);
		$result = $this->doStatement($this->select_by_post_id_stmt, $data);
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$raw[] = $row;
			$result->nextResult();
		}
		return $raw; 
	}

	/**
	 * Gets the value for a given characteristic / post initiative combination.
	 * @param integer $characteristic_id
	 * @param integer $post_initiative_id
	 * return boolean
	 */
	public function getValueByPostInitiativeId($characteristic_id, $post_initiative_id)
	{
		$data = array($characteristic_id, $post_initiative_id);
		$result = $this->doStatement($this->select_by_post_initiative_id_stmt, $data);
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$raw[] = $row;
			$result->nextResult();
		}
		return $raw; 
	}

}

?>