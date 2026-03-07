<?php

/**
 * Defines the app_mapper_TieredCharacteristicMapper class. 
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
 * @package alchemis
 */
class app_mapper_TieredCharacteristicMapper extends app_mapper_Mapper implements app_domain_TieredCharacteristicFinder
{
	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_TieredCharacteristic($array['id']);
		$obj->setValue($array['value']);
		$obj->setCategoryId($array['category_id']);
		$obj->setParentId($array['parent_id']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_tiered_characteristics');
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
			$query = 'INSERT INTO tbl_tiered_characteristics (id, category_id, value, parent_id) ' .
						'VALUES (?, ?, ?, ?)';
			$types = array('integer', 'integer', 'text', 'integer');
			$this->insert_stmt = self::$DB->prepare($query, $types);
		}
		$data = array($object->getId(), $object->getCategoryId(), $object->getValue(), $object->getParentId());
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
			$query = 'UPDATE tbl_tiered_characteristics ' .
						'SET category_id = ?, value = ?, parent_id = ? ' .
						'WHERE id = ?';
			$types = array('integer', 'text', 'integer', 'integer');
			$this->update_stmt = self::$DB->prepare($query, $types);
		}
		$data = array($object->getCategoryId(), $object->getValue(), $object->getParentId(), $object->getId());
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
			$query = 'DELETE FROM tbl_tiered_characteristics WHERE id = ?';
			$types = array('integer');
			$this->delete_stmt = self::$DB->prepare($query, $types);
		}
		$data = array($object->getId());
		$this->doStatement($this->delete_stmt, $data);
	}

	/**
	 * Responsible for constructing and running any $data that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper()
	 */
	public function doFind($id)
	{
		if (!isset($this->select_stmt))
		{
			$query = 'SELECT * FROM tbl_tiered_characteristics WHERE id = ?';
			$types = array('integer');
			$this->select_stmt = self::$DB->prepare($query, $types);
		}
		$data = array($id); 
		$result = $this->doStatement($this->select_stmt, $data);
		return $this->load($result);
	}

	/**
	 * Find all tiered characteristics.
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public function findAll()
	{
		$query = 'SELECT tc1.*, tc2.value AS category, CONCAT(tc1.parent_id, \'-\', tc1.value) AS sort ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'ORDER BY IFNULL(tc2.value, tc1.value), sort';
		$result = self::$DB->query($query);
		return new app_mapper_TieredCharacteristicCollection($result, $this);
	}


	/**
	 * Find all tiered characteristics.
	 * @return array
	 */
	public function findAllArray()
	{
		$query = 'SELECT tc1.*, tc2.value AS category, CONCAT(tc1.parent_id, \'-\', tc1.value) AS sort ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'ORDER BY IFNULL(tc2.value, tc1.value), sort';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
	/**
	 * Return the tiered characteristics which do not have a parent.
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public function findRootTieredCharacteristics($category_id = null)
	{
		$query = 'SELECT tc1.*, tc2.value AS category ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'WHERE tc1.parent_id = 0 ';

					 if (!is_null($category_id)) {
					 	$query .= 'AND tc1.category_id = ' . self::$DB->quote($category_id, 'integer') . ' ';
					 }
					$query .= 'ORDER BY tc2.value, tc1.value';
		$result = self::$DB->query($query);
		return new app_mapper_TieredCharacteristicCollection($result, $this);
	}

	/**
	 * Return the tiered characteristics which do not have a parent.
	 * @return array
	 */
	public function findRootTieredCharacteristicsArray()
	{
		$query = 'SELECT tc1.*, tc2.value AS category ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'WHERE tc1.parent_id = 0 ' .
					'ORDER BY tc2.value, tc1.value';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
	
	/** Find tiered characteristics by $parent_id
	 * @return app_mapper_TieredCharacteristicCollection raw data - single item
	 */
	public function findByParentId($parent_id)
	{
		$query = 'SELECT tc1.* ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'WHERE tc1.parent_id = ' . self::$DB->quote($parent_id, 'integer') . ' ' .
					'ORDER BY tc1.value';
		$result = self::$DB->query($query);
		return new app_mapper_TieredCharacteristicCollection($result, $this);
	}
	
	/** Find parent of tiered characteristics by $id
	 * @return app_mapper_TieredCharacteristicCollection raw data - single item
	 */
	public function findParentId($id)
	{
		$query = 'SELECT tc1.parent_id ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'WHERE id = ' . self::$DB->quote($id, 'integer');
		return self::$DB->queryOne($query);
	}
	
	/**
	 * Find tiered characteristic description from $id
	 * @return app_mapper_TieredCharacteristicCollection raw data - single item
	 */
	public function lookupValue($id)
	{
		$query = 'SELECT value FROM tbl_tiered_characteristics WHERE id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $result->fetchOne(0, 0);
	}

	
	/**
	 * Find tiered characteristic category description from $category_id
	 * @return app_mapper_TieredCharacteristicCollection raw data - single item
	 */
	public function lookupCategory($id)
	{
		$query = 'SELECT name FROM tbl_tiered_characteristic_categories WHERE id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $result->fetchOne(0, 0);
	}

	/**
	 * Find tiered characteristic category description from $category_id
	 * @return app_mapper_TieredCharacteristicCollection raw data - single item
	 */
	public function lookupCategories()
	{
		$query = 'SELECT * FROM tbl_tiered_characteristic_categories ORDER BY name';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Find the tiered characteristics associated with a given company.
	 * @param integer $company_id
	 * @return app_mapper_TieredCharacteristicCollection collection of app_mapper_TieredCharacteristic objects
	 */
	public function findByCompanyId($company_id)
	{

		$query = 'SELECT otc.*, tc1.value, tc1.category_id, tc1.parent_id, tc2.value AS parent_value, ' .
					'CONCAT(tc1.parent_id, \'-\', tc1.value) AS sort ' .
					'FROM tbl_object_tiered_characteristics AS otc ' .
					'INNER JOIN tbl_tiered_characteristics AS tc1 ON otc.tiered_characteristic_id = tc1.id ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'WHERE otc.company_id = ' . self::$DB->quote($company_id, 'integer') . ' ' .
					'ORDER BY IFNULL(tc2.value, tc1.value), sort';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}
	
	/**
	 * Find the tiered characteristics associated with a given parent company.
	 * @param integer $company_id
	 * @return app_mapper_TieredCharacteristicCollection collection of app_mapper_TieredCharacteristic objects
	 */
	public function findByParentCompanyId($parent_company_id)
	{

		$query = 'SELECT otc.*, tc1.value, tc1.category_id, tc1.parent_id, tc2.value AS parent_value, ' .
					'CONCAT(tc1.parent_id, \'-\', tc1.value) AS sort ' .
					'FROM tbl_object_tiered_characteristics AS otc ' .
					'INNER JOIN tbl_tiered_characteristics AS tc1 ON otc.tiered_characteristic_id = tc1.id ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'WHERE otc.parent_company_id = ' . self::$DB->quote($parent_company_id, 'integer') . ' ' .
					'ORDER BY IFNULL(tc2.value, tc1.value), sort';
		$result = self::$DB->query($query);
		return self::mdb2ResultToArray($result);
	}

	/**
	 * Returns a collection of characteristics which are not associated with a 
	 * company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection 
	 */
	public function selectAvailableByCompanyId($company_id, $category_id = null)
	{
		$query = 'SELECT tc1.*, tc2.value AS category ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'WHERE tc1.id NOT IN (SELECT tiered_characteristic_id FROM tbl_object_tiered_characteristics ' .
						'WHERE company_id = ' . self::$DB->quote($company_id, 'integer') . ') ';
						
					if (!is_null($category_id)) {
						$query .= 'AND tc1.category_id = ' . self::$DB->quote($category_id, 'integer') . ' ';
					}
		
					$query .= 'ORDER BY ISNULL(tc2.sort),tc2.sort, tc2.value, tc1.value';
					
		$result = self::$DB->query($query);
		return new app_mapper_TieredCharacteristicCollection($result, $this);
	}

	/**
	 * Returns a collection of characteristics which are not associated with a 
	 * company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection 
	 */
	public function selectAllForDropdown($category_id = null)
	{
		$query = 'SELECT tc1.*, tc2.value AS category ' .
			'FROM tbl_tiered_characteristics AS tc1 ' .
			'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ';
		
		
		if (!is_null($category_id)) {
			$query .= 'WHERE tc1.category_id = ' . self::$DB->quote($category_id, 'integer') . ' ';
		}
		
		$query .= 'ORDER BY ISNULL(tc2.sort),tc2.sort, tc2.value, tc1.value';
		
		$result = self::$DB->query($query);
		return new app_mapper_TieredCharacteristicCollection($result, $this);
	}
}

?>