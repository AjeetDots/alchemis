<?php

/**
 * Defines the app_mapper_ObjectCharacteristicTextMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ObjectCharacteristicMapper.php');

/**
 * @package Alchemis
 */
class app_mapper_ObjectCharacteristicTextMapper extends app_mapper_ObjectCharacteristicMapper implements app_domain_ObjectCharacteristicTextFinder
{
	protected function init()
	{
		// Select single
		$query = 'SELECT * FROM tbl_object_characteristics_text WHERE id = ?';
		$this->select_stmt = self::$DB->prepare($query);
		
		// Select by company ID
		$query = 'SELECT id, value FROM tbl_object_characteristics_text WHERE characteristic_id = ? AND company_id = ?';
		$this->select_by_company_id_stmt = self::$DB->prepare($query);
		
		// Select by post ID
		$query = 'SELECT id, value FROM tbl_object_characteristics_text WHERE characteristic_id = ? AND post_id = ?';
		$this->select_by_post_id_stmt = self::$DB->prepare($query);

		// Select by post initiative ID
		$query = 'SELECT id, value FROM tbl_object_characteristics_text WHERE characteristic_id = ? AND post_initiative_id = ?';
		$this->select_by_post_initiative_id_stmt = self::$DB->prepare($query);
		
		// Insert
		$query = 'INSERT INTO tbl_object_characteristics_text ' .
					'(id, characteristic_id, company_id, post_id, post_initiative_id, value) ' .
					'VALUES (?, ?, ?, ?, ?, ?)';
		$this->insert_stmt = self::$DB->prepare($query);
		
		// Update
		$query = 'UPDATE tbl_object_characteristics_text ' .
					'SET characteristic_id = ?, company_id = ?, post_id = ?, post_initiative_id = ?, value = ? ' .
					'WHERE id = ?';
		$this->update_stmt = self::$DB->prepare($query);
		
		// Delete
		$query = 'DELETE FROM tbl_object_characteristics_text WHERE id = ?'; 
		$this->delete_stmt = self::$DB->prepare($query);
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
		$this->id = self::$DB->nextID('tbl_object_characteristics_text');
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
								$object->getParentObjectId(), NULL, NULL, $object->getValue());
				break;
			
			case 'app_domain_Post';
				$data = array($object->getId(), $object->getCharacteristicId(), 
								NULL, $object->getParentObjectId(), NULL, $object->getValue());
				break;
			
			case 'app_domain_PostInitiative';
				$data = array($object->getId(), $object->getCharacteristicId(), 
								NULL, NULL, $object->getParentObjectId(), $object->getValue());
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

}

?>