<?php

require_once('app/base/Exceptions.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');
require_once('app/domain.php');

/**
 * @package alchemis
 */
class app_mapper_ObjectTieredCharacteristicMapper extends app_mapper_Mapper implements app_domain_ObjectTieredCharacteristicFinder
{

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
	protected function doLoad($array)
	{
		$obj = new app_domain_ObjectTieredCharacteristic($array['id']);
		$obj->setTieredCharacteristicId($array['tiered_characteristic_id']);
		$obj->setTier($array['tier']);
		$obj->markClean();
		return $obj;
	}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId()
	{
		$this->id = self::$DB->nextID('tbl_object_tiered_characteristics');
		return $this->id;
	}
	
	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$query = 'INSERT INTO tbl_object_tiered_characteristics (tiered_characteristic_id, tier, company_id, parent_company_id) ' .
				'VALUES (?, ?, ?, ?)';
		$types = array('integer', 'integer', 'integer', 'integer');
		$stmt = self::$DB->prepare($query, $types);
		$data = array($object->getTieredCharacteristicId(), $object->getTier(), $object->getParentObjectId(), $object->getParentCompanyId());
		$this->doStatement($stmt, $data);
	}
	
	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		// 03/10/07 - at this stage the data in tbl_'object'_tiered_characteristics will not be updated.
		// Data will be either added or delete, but not updated
	}

	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
		$query = 'DELETE FROM tbl_object_tiered_characteristics WHERE id = ?';
		$types = array('integer');
		$stmt = self::$DB->prepare($query, $types);
		$values = array($object->getId());
		$this->doStatement($stmt, $values);
	}
	
	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper()
	 */
	public function doFind($id)
	{
		$query = 'SELECT * FROM tbl_object_tiered_characteristics WHERE id = ' . self::$DB->quote($id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}

	/**
	 * Find all tiered characteristics.
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public function findAll()
	{
//		$query = 'SELECT * FROM tbl_object_tiered_characteristics';
//		$result = self::$DB->query($query);
//		return new app_mapper_ObjectTieredCharacteristicCollection($result, $this);
	}

	/**
	 * Find all tiered characteristics.
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public function findByCompanyIdAndTieredCharacterisicId($company_id, $tiered_characteristic_id)
	{
		$query = 'SELECT * FROM tbl_object_tiered_characteristics ' .
				'WHERE company_id = ' . self::$DB->quote($company_id, 'integer') . ' ' .
				'AND tiered_characteristic_id = ' . self::$DB->quote($tiered_characteristic_id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}
	
	/**
	 * Find all tiered characteristics.
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public function findByParentCompanyIdAndTieredCharacterisicId($parent_company_id, $tiered_characteristic_id)
	{
		$query = 'SELECT * FROM tbl_object_tiered_characteristics ' .
				'WHERE parent_company_id = ' . self::$DB->quote($parent_company_id, 'integer') . ' ' .
				'AND tiered_characteristic_id = ' . self::$DB->quote($tiered_characteristic_id, 'integer');
		$result = self::$DB->query($query);
		return $this->load($result);
	}
	
	/**
	 * Determine if a given object (company) is associated with a given tiered characteristic. 
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return boolean
	 */
	public function isAssociated($object_id, $tiered_characteristic_id)
	{
		$query = 'SELECT * FROM tbl_object_tiered_characteristics ' .
					'WHERE tiered_characteristic_id = ' . self::$DB->quote($tiered_characteristic_id, 'integer') . ' ' .
					'AND company_id = ' . self::$DB->quote($object_id, 'integer');
		$result = self::$DB->query($query);
		return $result->numRows() > 0; 
	}
	
	/**
	 * Determine if a given object (parent company) is associated with a given tiered characteristic. 
	 * @param integer $parent_company_id
	 * @param integer $tiered_characteristic_id
	 * @return boolean
	 */
	public function isAssociatedParent($parent_company_id, $tiered_characteristic_id)
	{
		$query = 'SELECT * FROM tbl_object_tiered_characteristics ' .
					'WHERE tiered_characteristic_id = ' . self::$DB->quote($tiered_characteristic_id, 'integer') . ' ' .
					'AND parent_company_id = ' . self::$DB->quote($parent_company_id, 'integer');
		$result = self::$DB->query($query);
		return $result->numRows() > 0; 
	}

	/**
	 * Determine how many sub-cats a top level cat has for a given object (company). 
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return integer
	 */
	public function countTieredCharacteristicByCompanyIdAndTieredCharacteristicId($object_id, $tiered_characteristic_id)
	{
		$query = 'SELECT count(otc.id) ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'LEFT JOIN tbl_object_tiered_characteristics AS otc ON otc.tiered_characteristic_id = tc1.id ' .
					'WHERE tc1.parent_id = ' . self::$DB->quote($tiered_characteristic_id, 'integer') . ' ' .
					'AND otc.company_id = ' . self::$DB->quote($object_id, 'integer');  
		return self::$DB->queryOne($query);
	}
	
	/**
	 * Determine how many sub-cats a top level cat has for a given object (company). 
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return integer
	 */
	public function countTieredCharacteristicByParentCompanyIdAndTieredCharacteristicId($parent_company_id, $tiered_characteristic_id)
	{
		$query = 'SELECT count(otc.id) ' .
					'FROM tbl_tiered_characteristics AS tc1 ' .
					'LEFT JOIN tbl_tiered_characteristics AS tc2 ON tc1.parent_id = tc2.id ' .
					'LEFT JOIN tbl_object_tiered_characteristics AS otc ON otc.tiered_characteristic_id = tc1.id ' .
					'WHERE tc1.parent_id = ' . self::$DB->quote($tiered_characteristic_id, 'integer') . ' ' .
					'AND otc.parent_company_id = ' . self::$DB->quote($parent_company_id, 'integer');  
		return self::$DB->queryOne($query);
	}
	
	
}

?>