<?php

/**
 * Defines the app_domain_ObjectCharacteristic class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/domain/Company.php');
require_once('app/domain/Post.php');
require_once('app/domain/PostInitiative.php');

/**
 * @package Alchemis
 */
class app_domain_ObjectCharacteristic extends app_domain_DomainObject 
{
	/**
	 * Holds database connection resource
	 * @var resource
	 */
	protected static $valid_types = array(	'app_domain_Company', 
											'app_domain_Post', 
											'app_domain_PostInitiative'); 

	/**
	 * The ID of the parent object against which the characteristic is linked.
	 * @var integer
	 */
	protected $parent_object_id;

	/**
	 * The class name of the parent object type against which the 
	 * characteristic is linked.
	 * @var string
	 */
	protected $parent_object_type;
	
	/**
	 * The ID of the characteristic.
	 * @var integer
	 */
	protected $characteristic_id;
	
	/**
	 * The object characteristic value.
	 * @var mixed
	 */
	protected $value;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Sets the parent object ID.
	 * @param integer $parent_object_id
	 */
	public function setParentObjectId($id)
	{
		$this->parent_object_id = $id;
		$this->markDirty();
	}

	/**
	 * Returns the parent object ID.
	 * @return app_domain_DomainObject
	 */
	public function getParentObjectId()
	{
		return $this->parent_object_id;
	}

	/**
	 * Sets the class name of the parent object type.
	 * @param string $type
	 */
	public function setParentObjectType($type)
	{
		if ($this->isValidParentObjectType($type))
		{
			$this->parent_object_type = $type;
			$this->markDirty();
		}
		else
		{
			throw new Exception('Invalid parent object type: ' . $type);
		}
	}

	/**
	 * Returns the class name of the parent object type.
	 * @return string
	 */
	public function getParentObjectType()
	{
		return $this->parent_object_type;
	}

	/**
	 * Sets the ID of the characteristic.
	 * @param integer $id
	 */
	public function setCharacteristicId($id)
	{
		$this->characteristic_id = $id;
		$this->markDirty();
	}

	/**
	 * Returns the ID of the characteristic.
	 * @return integer
	 */
	public function getCharacteristicId()
	{
		return $this->characteristic_id;
	}

	/**
	 * Sets the characteristic value.
	 * @param mixed $value
	 */
	public function setValue($value) {}

	/**
	 * Returns the characteristic value
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

//	/**
//	 * Returns whether the characteristic is editable based on whether the 
//	 * parent object and characteristics have been assigned.
//	 * @return boolean
//	 */
//	private function editable()
//	{
//		return ($this->object instanceof app_domain_DomainObject && $this->characteristic instanceof app_domain_Characteristic);
//	}

	/**
	 * Return whether the parent object type is valid.
	 * @return boolean
	 */
	private function isValidParentObjectType($type)
	{
		return in_array($type, self::$valid_types); 
	}

	/**
	 * Find a characteristic by a given ID.
	 * @param integer $id characteristic ID
	 * @return app_domain_Characteristic object
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}
	
}

?>