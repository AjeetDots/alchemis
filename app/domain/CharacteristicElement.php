<?php

/**
 * Defines the app_domain_CharacteristicElement class. 
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
//abstract class app_domain_CharacteristicElement extends app_domain_DomainObject implements app_domain_ObjectWatcherKey
//class app_domain_CharacteristicElement extends app_domain_DomainObject implements app_domain_ObjectWatcherKey

class app_domain_CharacteristicElement extends app_domain_DomainObject
//abstract class app_domain_CharacteristicElement extends app_domain_DomainObject 
{
	/**
	 * The element's parent characteristic.
	 * @var app_domain_Characteristic
	 */
	protected $characteristic;
	
	/**
	 * The element's name.
	 * @var string
	 */
	protected $name;

	/**
	 * The element's sort order. 
	 * @var integer
	 */
	protected $data_type;

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
	 * Sets the element name.
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->markDirty();
	}

	/**
	 * Returns the element's name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
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

	/**
	 * 
	 * @param app_domain_Characteristic $characteristic
	 */
	public function setCharacteristic(app_domain_Characteristic $characteristic)
	{
		$this->characteristic = $characteristic;
	}

	/**
	 * Returns the element's parent characteristic.
	 * @return app_domain_Characteristic
	 */
	public function getCharacteristic()
	{
		return $this->characteristic;
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

	
	/**
	 * Find element by characteristic_id and name.
	 * @param integer $characteristic_id characteristic ID
	 * @param string $name
	 * @return app_domain_CharacteristicElement
	 */
	public static function findByCharacteristicIdAndName($characteristic_id, $name)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCharacteristicIdAndName($characteristic_id, $name);
	}

	/**
	 * Lookup the name of a given characteristic element.
	 * @param integer $id
	 * @return string
	 */
	public static function lookupName($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupName($id);
	}

}

?>