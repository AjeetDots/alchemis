<?php

/**
 * Defines the app_mapper_CharacteristicElementMapper class. 
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
class app_mapper_CharacteristicElementMapper extends app_mapper_Mapper implements app_domain_CharacteristicElementFinder
{
	public function __construct()
	{
		if (!self::$DB)
		{
			self::$DB = app_controller_ApplicationHelper::instance()->DB();
		}
		
		// Select single
		$query = 'SELECT * FROM tbl_characteristic_elements WHERE id = ?';
//		$types = array('integer');
//		$this->select_stmt = self::$DB->prepare($query, $types);
		$this->select_stmt = self::$DB->prepare($query);
	}

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_CharacteristicElement($array['id']);
		$obj->setCharacteristic(new app_domain_Characteristic($array['characteristic_id']));
		$obj->setDataType($array['data_type']);
		$obj->setName($array['name']);
		$obj->setSort($array['sort']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_characteristic_elements');
		return $this->id;
	}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		if (!isset($this->insert_stmt))
		{
			$query = 'INSERT INTO tbl_characteristic_elements (id, characteristic_id, data_type, name, sort) ' .
						'VALUES (?, ?, ?, ?, ?)';
			$types = array('integer', 'integer', 'text', 'text', 'integer');
			$this->insert_stmt = self::$DB->prepare($query, $types);
		}
		
		$data = array($object->getId(), $object->getCharacteristic()->getId(), $object->getDataType(), 
						$object->getName(), $object->getSort());
		$this->doStatement($this->insert_stmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		if (!isset($this->update_stmt))
		{
			$query = 'UPDATE tbl_characteristic_elements SET characteristic_id = ?, data_type = ?, ' .
						'name = ?, sort= ? WHERE id = ?';
			$types = array('integer', 'text', 'text', 'integer', 'integer');
			$this->update_stmt = self::$DB->prepare($query, $types);
		}

		$data = array($object->getCharacteristic()->getId(), $object->getDataType(), $object->getName(), 
						$object->getSort(), $object->getId());
		$this->doStatement($this->update_stmt, $data);
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		if (!isset($this->delete_stmt))
		{
			$query = 'DELETE FROM tbl_characteristic_elements WHERE id = ?';
			$types = array('integer');
			$this->delete_stmt = self::$DB->prepare($query, $types);
		}
		
		$data = array($object->getId());
		$this->doStatement($this->delete_stmt, $data);
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

//	/**
//	 * Find all tags.
//	 * @return app_mapper_CharacteristicCollection collection of app_domain_Characteristic objects
//	 */
//	public function findAll()
//	{
//		$result = $this->doStatement($this->selectAllStmt, array());
//		return new app_mapper_CharacteristicElementCollection($result, $this);
//	}

	/**
	 * Find all elements for a given characteristic.
	 * @param integer $characteristic_id characteristic ID
	 * @return app_mapper_CharacteristicElementCollection
	 */
	public function findByCharacteristicId($characteristic_id)
	{
		if (!isset($this->select_by_characteristic_stmt))
		{
			$query = 'SELECT e.*, c.data_type AS characteristic_data_type ' .
						'FROM tbl_characteristic_elements AS e ' .
						'INNER JOIN tbl_characteristics AS c ON e.characteristic_id = c.id ' .
						'WHERE e.characteristic_id = ? ORDER BY e.sort, e.name';
			$types = array('integer');
			$this->select_by_characteristic_stmt = self::$DB->prepare($query, $types);
		}
		
		$data = array($characteristic_id);
		$result = $this->doStatement($this->select_by_characteristic_stmt, $data);
		return new app_mapper_CharacteristicElementCollection($result, $this);
	}

	/**
	 * Find an element by characteristic_id and name.
	 * @param integer $characteristic_id characteristic ID
	 * @param string $name
	 * @return app_domain_CharacteristicElement
	 */
	public function findByCharacteristicIdAndName($characteristic_id, $name)
	{
		if (!isset($this->select_by_characteristic_id_and_name_stmt))
		{
			$query = 'SELECT e.* ' .
					'FROM tbl_characteristic_elements AS e ' .
					'WHERE e.characteristic_id = ? ' .
					'AND name = ?';
			$types = array('integer', 'text');
			$this->select_by_characteristic_id_and_name_stmt = self::$DB->prepare($query, $types);
		}
		
		$data = array($characteristic_id, $name);
		$result = $this->doStatement($this->select_by_characteristic_id_and_name_stmt, $data);
		
		if ($result->numRows() > 0)
		{
			return $this->load($result);
		}
		else
		{
			return null;
		}
	}


	/**
	 * Lookup the name of a given characteristic element.
	 * @param integer $id
	 * @return string
	 */
	public function lookupName($id)
	{
		if (!isset($this->lookup_name_stmt))
		{
			$query = 'SELECT name FROM tbl_characteristic_elements WHERE id = ?';
			$types = array('integer');
			$this->lookup_name_stmt = self::$DB->prepare($query, $types);
		}
		
		$data = array($id);
		$result = $this->doStatement($this->lookup_name_stmt, $data);
		if ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			return $row['name'];
		}
		else
		{
			return null;
		}
	}

}

?>