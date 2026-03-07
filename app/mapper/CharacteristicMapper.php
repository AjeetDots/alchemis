<?php

/**
 * Defines the app_mapper_CharacteristicMapper class. 
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
class app_mapper_CharacteristicMapper extends app_mapper_Mapper implements app_domain_CharacteristicFinder
{
	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_Characteristic($array['id']);
		$obj->setName($array['name']);
		$obj->setDescription($array['description']);
		$obj->setType($array['type']);
		$obj->setAttributes((bool)$array['attributes']);
		$obj->setOptions((bool)$array['options']);
		$obj->setMultipleSelect((bool)$array['multiple_select']);
		$obj->setDataType($array['data_type']);
		
		if ($es = app_domain_CharacteristicElement::findByCharacteristicId($array['id']))
		{
			$obj->setElements($es);
		}
		
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_characteristics');
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
			$query = 'INSERT INTO tbl_characteristics (id, type, name, description, attributes, options, multiple_select, data_type) ' .
						'VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
			$types = array('integer', 'text', 'text', 'text', 'integer', 'integer', 'integer', 'text');
			$this->insert_stmt = self::$DB->prepare($query, $types);
		}
		$data = array($object->getId(), $object->getType(), $object->getName(), $object->getDescription(),
						$object->hasAttributes(), $object->hasOptions(), $object->hasMultipleSelect(), $object->getDataType());
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
			$query = 'UPDATE tbl_characteristics ' .
						'SET type = ?, name = ?, description = ?, attributes = ?, options = ?, multiple_select = ?, data_type = ? ' .
						'WHERE id = ?';
			$types = array('text', 'text', 'text', 'integer', 'integer', 'integer', 'text', 'integer');
			$this->update_stmt = self::$DB->prepare($query, $types);
		}
		$data = array($object->getType(), $object->getName(), $object->getDescription(), 
						$object->hasAttributes(), $object->hasOptions(), $object->hasMultipleSelect(), 
						$object->getDataType(), $object->getId());
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
			$query = 'DELETE FROM tbl_characteristics WHERE id = ?';
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
		if (!isset($this->select_stmt))
		{
			$query = 'SELECT * FROM tbl_characteristics WHERE id = ?';
			$types = array('integer');
			$this->select_stmt = self::$DB->prepare($query, $types);
		}
		$data = array($id); 
		$result = $this->doStatement($this->select_stmt, $data);
		return $this->load($result);
	}

	/**
	 * Find all tags.
	 * @return app_mapper_CharacteristicCollection collection of app_domain_Characteristic objects
	 */
	public function findAll()
	{
		if (!isset($this->select_all_stmt))
		{
			$this->select_all_stmt = self::$DB->prepare('SELECT * FROM tbl_characteristics ORDER BY name');
		}
		$result = $this->doStatement($this->select_all_stmt, array());
		return new app_mapper_CharacteristicCollection($result, $this);
	}

	/**
	 * Get the text characteristic elements for a given characteristic.
	 * @param integer $characteristic_id
	 * @returnarray
	 */
	public function getElements($characteristic_id)
	{
		$data = array($characteristic_id);
		
		$query = 'SELECT * FROM tbl_characteristic_elements_text WHERE characteristic_id = ?';
		$stmt = self::$DB->prepare($query);
		$result_0 = $this->doStatement($stmt, $data);
		$results = app_mapper_Collection::mdb2ResultToArray($result_0);		
		
		$objs = array();
		foreach ($results as $array)
		{
			$obj = new app_domain_CharacteristicElement($array['id']);
			$obj->setValue($array['value']);
			$obj->setSort($array['sort']);
			$obj->markClean();
			$objs[] = $obj;
		}
		return $objs;
	}

	/**
	 * Returns a collection of charateristics which are associated with a 
	 * company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection 
	 */
	public function selectAssociatedWithCompanyId($company_id)
	{
		$data = array($company_id);
		$query = 'SELECT c1.* FROM tbl_characteristics AS c1 ' .
					'LEFT JOIN (SELECT characteristic_id FROM tbl_company_characteristics WHERE company_id = ?) AS c2 ON c1.id = c2.characteristic_id ' .
					'WHERE c2.characteristic_id IS NOT NULL ORDER BY c1.Name';
		$stmt = self::$DB->prepare($query);
		$result = $this->doStatement($stmt, $data);
		return new app_mapper_CharacteristicCollection($result, $this);
	}

	/**
	 * Returns a collection of charateristics which are not associated with a company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection 
	 */
	public function selectAvailableByCompanyId($company_id)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE type = \'company\' ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics WHERE company_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_boolean WHERE company_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_date WHERE company_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_text WHERE company_id = ?) ' .
					'ORDER BY c.name';
		$data = array($company_id, $company_id, $company_id, $company_id);

		$stmt = self::$DB->prepare($query);
		
		$result = $this->doStatement($stmt, $data);
		return new app_mapper_CharacteristicCollection($result, $this);
	}


	/**
	 * Returns a collection of characteristics which are not associated with a 
	 * post.
	 * @param integer $post_id
	 * @return app_mapper_CharacteristicCollection 
	 */
	public function selectAvailableByPostId($post_id)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE type = \'post\' ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics WHERE post_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_boolean WHERE post_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_date WHERE post_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_text WHERE post_id = ?) ' .
					'ORDER BY c.name';
		$data = array($post_id, $post_id, $post_id, $post_id);

		$stmt = self::$DB->prepare($query);
		
		$result = $this->doStatement($stmt, $data);
		return new app_mapper_CharacteristicCollection($result, $this);
	}
	
	
	/**
	 * Returns a collection of characteristics which are not associated with a 
	 * post initiative.
	 * @param integer $post_initiative_id
	 * @return app_mapper_CharacteristicCollection 
	 */
	public function selectAvailableByPostInitiativeId($post_initiative_id)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE type = \'post initiative\' ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics WHERE post_initiative_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_boolean WHERE post_initiative_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_date WHERE post_initiative_id = ?) ' .
					'AND id NOT IN (SELECT characteristic_id FROM tbl_object_characteristics_text WHERE post_initiative_id = ?) ' .
					'ORDER BY c.name';
		$data = array($post_initiative_id, $post_initiative_id, $post_initiative_id, $post_initiative_id);

		$stmt = self::$DB->prepare($query);
		
		$result = $this->doStatement($stmt, $data);
		return new app_mapper_CharacteristicCollection($result, $this);
	}
	
	/**
	 * 
	 * @param integer $characteristic_id
	 */
	public function lookupDataType($characteristic_id)
	{
		$query = 'SELECT data_type FROM tbl_characteristics WHERE id = ?';
		$stmt = self::$DB->prepare($query);
		$data = array($characteristic_id);
		$result = $this->doStatement($stmt, $data);
		
		// Move to first item in row
		if ($array = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			return $array['data_type'];
		}
		throw new Exception('Data type not found');
	}

	public function lookupType($characteristic_id)
	{
		$query = 'SELECT type FROM tbl_characteristics WHERE id = ?';
		$stmt = self::$DB->prepare($query);
		$data = array($characteristic_id);
		$result = $this->doStatement($stmt, $data);
		
		// Move to first item in row
		if ($array = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if (isset($array['type']))
			{
				return $array['type'];
			}
		}
		throw new Exception('Type not found');
	}


	/**
	 * Find characteristics for a given company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public function findByCompanyId($company_id)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE type = \'company\' ' .
					'AND (' .
					'id IN (SELECT characteristic_id FROM tbl_object_characteristics WHERE company_id = ' . self::$DB->quote($company_id, 'integer') . ') ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_boolean WHERE company_id = ' . self::$DB->quote($company_id, 'integer') . ') ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_date WHERE company_id = ' . self::$DB->quote($company_id, 'integer') . ') ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_text WHERE company_id = ' . self::$DB->quote($company_id, 'integer') . ')) ' .
					'ORDER BY c.name';
		$result = self::$DB->query($query);
		return new app_mapper_CharacteristicCollection($result, $this);
	}

	/**
	 * Find characteristics for a given post.
	 * @param integer $post_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public function findByPostId($post_id)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE type = \'post\' ' .
					'AND (' .
					'id IN (SELECT characteristic_id FROM tbl_object_characteristics WHERE post_id = ?) ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_boolean WHERE post_id = ?) ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_date WHERE post_id = ?) ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_text WHERE post_id = ?)) ' .
					'ORDER BY c.name';
		$stmt = self::$DB->prepare($query);
		$data = array($post_id, $post_id, $post_id, $post_id);
		$result = $this->doStatement($stmt, $data);
		return new app_mapper_CharacteristicCollection($result, $this);
	}


	/**
	 * Find characteristics for a given post initiative.
	 * @param integer $post_initiative_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public function findByPostInitiativeId($post_initiative_id)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE type = \'post initiative\' ' .
					'AND (' .
					'id IN (SELECT characteristic_id FROM tbl_object_characteristics WHERE post_initiative_id = ?) ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_boolean WHERE post_initiative_id = ?) ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_date WHERE post_initiative_id = ?) ' .
					'OR id IN (SELECT characteristic_id FROM tbl_object_characteristics_text WHERE post_initiative_id = ?)) ' .
					'ORDER BY c.name';
		$stmt = self::$DB->prepare($query);
		$data = array($post_initiative_id, $post_initiative_id, $post_initiative_id, $post_initiative_id);
		$result = $this->doStatement($stmt, $data);
		return new app_mapper_CharacteristicCollection($result, $this);
	}
	
	/**
	 * Find characteristics for a given type (eg company).
	 * @param string $type (eg company, post)
	 * @return app_mapper_CharacteristicCollection
	 */
	public function findByType($type)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE type = ? ' .
					'order by c.name';
		$stmt = self::$DB->prepare($query);
		$data = array($type);
		$result = $this->doStatement($stmt, $data);
		return new app_mapper_CharacteristicCollection($result, $this);
	}

	/**
	 * Find a characteristic by name and type.
	 * @param string $name
	 * @param string $type
	 * @return app_domain_Characteristic
	 */
	public function findByNameAndType($name, $type)
	{
		$query = 'SELECT * FROM tbl_characteristics AS c ' .
					'WHERE name = ? AND type = ? ' .
					'order by c.name';
		$stmt = self::$DB->prepare($query);
		$data = array($name, $type);
		$result = $this->doStatement($stmt, $data);
		
		return $this->load($result);
	}

}

?>