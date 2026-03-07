<?php

/**
 * Defines the app_mapper_NoteMapper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/base/Exceptions.php');
require_once('app/domain.php');
require_once('app/mapper.php');
require_once('app/mapper/Mapper.php');
require_once('app/mapper/Collections.php');

/**
 * @package Alchemis
 */
abstract class app_mapper_NoteMapper extends app_mapper_Mapper
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
	protected function doLoad($array) {}

	/**
	 * @TODO docs
	 * Returns the target class name, i.e. 
	 * @return string
	 */
	protected function targetClass() {}

	/**
	 * Get a new ID to use from the database.
	 * @return integer
	 */
    public function newId() {}

	/**
	 * Insert a new database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function doInsert(app_domain_DomainObject $object)
	{
		if (!is_null($object->getParentId()))
		{
			$data = array($object->getId(), $object->getParentId(), $object->getCreatedAt(), 
							$object->getCreatedBy(), $object->getNote());
			$this->doStatement($this->insert_stmt, $data);
		}
		else
		{
			throw new Exception('Parent ID is null');
		}
	}

	/**
	 * Update the existing database record for the object.
	 * @param app_domain_DomainObject $object
	 */
	function update(app_domain_DomainObject $object)
	{
		if (!is_null($object->getParentId()))
		{
			$data = array($object->getParentId(), $object->getCreatedAt(), 
							$object->getCreatedBy(), $object->getNote(), $object->getId());
			$this->doStatement($this->update_stmt, $data);
		}
		else
		{
			throw new Exception('Parent ID is null');
		}
	}

	/**
	 * Responsible for constructing and running any queries that are needed.
	 * @param integer $id
	 * @return app_domain_DomainObject
	 * @see app_mapper_Mapper::load()
	 */
	public function doFind($id)
	{
		$data = array($id);
		$result = $this->doStatement($this->select_stmt, $id);
		return $this->load($result);
	}

}

?>