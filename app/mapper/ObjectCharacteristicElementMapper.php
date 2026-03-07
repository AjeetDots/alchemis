<?php

/**
 * Defines the app_mapper_ObjectCharacteristicElementMapper class.
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
abstract class app_mapper_ObjectCharacteristicElementMapper extends app_mapper_Mapper
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

	abstract protected function init();

	/**
	 * Load an object from an associative array.
	 * @param array $array an associative array
	 * @return app_domain_DomainObject
	 */
//	abstract protected function doLoad($array);
	protected function doLoad($array) {}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e.
	 * @return string
	 */
//	abstract protected function targetClass();
	protected function targetClass() {}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
//	abstract public function newId();
	public function newId() {}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		$data = array($object->getId(), $object->getObjectCharacteristicId(), $object->getCharacteristicElementId(), $object->getValue());
		$this->doStatement($this->insert_stmt, $data);
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		$data = array($object->getObjectCharacteristicId(), $object->getCharacteristicElementId(), $object->getValue(), $object->getId());
		$this->doStatement($this->update_stmt, $data);
	}


	/**
	 * Delete the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function delete(app_domain_DomainObject $object)
	{
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

	public function getRecordByCompanyId($element_id, $company_id)
	{
		$data = array($element_id, $company_id);
		$result = $this->doStatement($this->select_company_value_stmt, $data);
		if ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			return $row;
		}
	}

	public function getRecordByPostId($element_id, $post_id)
	{
		$data = array($element_id, $post_id);
		$result = $this->doStatement($this->select_post_value_stmt, $data);
		if ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			return $row;
		}
	}

	public function getRecordByPostInitiativeId($element_id, $post_initiative_id)
	{
		$data = array($element_id, $post_initiative_id);
		$result = $this->doStatement($this->select_post_initiative_value_stmt, $data);
		if ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			return $row;
		}
	}

	public function getAllRecordsByObjectCharacteristicId($object_characteristic_id)
	{
		$data = array($object_characteristic_id);
		$result = $this->doStatement($this->select_records_by_obj_char_id_stmt, $data);
		return $result->fetchCol();
	}
}

?>