<?php

/**
 * Defines the app_domain_CharacteristicElementHelper class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/domain/ObjectWatcherKey.php');
require_once('app/mapper/CharacteristicElementMapper.php');

/**
 * @package Alchemis
 */
class app_domain_CharacteristicElementHelper extends app_domain_DomainObject implements app_domain_ObjectWatcherKey 
{
	/**
	 * The element's parent characteristic.
	 * @var app_domain_Characteristic
	 */
	protected $characteristic;
	
	/**
	 * The element's value.
	 * @var string
	 */
	protected $key;

	/**
	 * The element's value.
	 * @var string
	 */
	protected $value;
	
	/**
	 * The element's sort order. 
	 * @var integer
	 */
	protected $sort = 0;
	
	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Sets the element value.
	 * @param string $value
	 */
	public function setKey($key)
	{
		$this->key = $key;
		$this->markDirty();
	}

	/**
	 * Returns the element's value.
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Sets the element value.
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
		$this->markDirty();
	}

	/**
	 * Returns the element's value.
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Sets the element's sort order.
	 * @param integer $sort
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;
		$this->markDirty();
	}

	/**
	 * Returns the element's sort order.
	 * @return string
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * Sets the element data type.
	 * @param string $data_type
	 */
	public function setDataType($data_type)
	{
		$this->data_type = $data_type;
	}

	/**
	 * Returns the element data type.
	 * @return string
	 */
	public function getDataType()
	{
		return $this->data_type;
	}

//	 * Set the tag category id.
//	 * @param number $category_id the tag category id
//	 */
//	public function setCategoryId($category_id)
//	{
//		$this->category_id = $category_id;
//		$this->markDirty();
//	}
//
//	/**
//	 * Return the charactetitag category id.
//	 * @return number $category_id the tag category id
//	 */
//	public function getCategoryId()
//	{
//		return $this->category_id;
//	}

	/**
	 * 
	 * @param app_domain_Characteristic $characteristic
	 */
	public function setCharacteristic(app_domain_Characteristic $characteristic)
	{
//		if ($this->isValidType($obj))
//		{
			$this->characteristic = $characteristic;
//		}
//		else
//		{
//			throw new Exception('Invalid type');
//		}
	}

	/**
	 * Returns the element's parent characteristic.
	 * @return app_domain_Characteristic
	 */
	public function getCharacteristic()
	{
		return $this->characteristic;
	}
//	
//	protected function isValidType(app_domain_DomainObject $obj)
//	{
//		return array_key_exists(get_class($obj), self::$valid_types);
//	}
//
//	/**
//	 * Return the valid types array.
//	 * @return number $valid_types the valid types array of the tag 
//	 */
//	public static function getValidTypes()
//	{
//		return self::$valid_types;
//	}
	
	/**
	 * Find all characteristics.
	 * @return app_mapper_CharacteristicCollection collection of 
	 * app_domain_Characteristic objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
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

	/**
	 * Find all elements for a given characteristic.
	 * @param integer $characteristic_id characteristic ID
	 * @return app_mapper_CharacteristicElementCollection
	 */
	public static function findByCharacteristicId($characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCharacteristicId($characteristic_id);
	}

//	/**
//	 * 
//	 * @param integer $characteristic_id
//	 * @return app_mapper_CharacteristicElementCollection 
//	 */
//	public function getElements($characteristic_id)
//	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->getElements($characteristic_id);
//	}


	/**
	 * Returns additional string component to be used as part of the glabal key 
	 * implemented by app_domain_ObjectWatcher.
	 * @see app_domain_ObjectWatcher::globalKey()
	 * @return string
	 */
	public function objectWatcherKey()
	{
		return $this->data_type;
	}

}

?>