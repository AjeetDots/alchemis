<?php

/**
 * Defines the app_domain_ObjectCharacteristicElement class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');

/**
 * @package Alchemis
 */
abstract class app_domain_ObjectCharacteristicElement extends app_domain_DomainObject
//class app_domain_ObjectCharacteristic extends app_domain_DomainObject
{
	/**
	 * The ID of the parent object characteristic against which the 
	 * characteristic element is linked.
	 * @var integer
	 */
	protected $object_characteristic_id;

	/**
	 * The ID of the characteristic element against which this value maps.
	 * @var integer
	 */
	protected $characteristic_element_id;
	
	/**
	 * The object characteristic element value.
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
	public function setObjectCharacteristicId($id)
	{
		$this->object_characteristic_id = $id;
		$this->markDirty();
	}

	/**
	 * Returns the parent object ID.
	 * @return app_domain_DomainObject
	 */
	public function getObjectCharacteristicId()
	{
		return $this->object_characteristic_id;
	}

	/**
	 * Sets the ID of the characteristic.
	 * @param integer $id
	 */
	public function setCharacteristicElementId($id)
	{
		$this->characteristic_element_id = $id;
		$this->markDirty();
	}

	/**
	 * Returns the ID of the characteristic.
	 * @return integer
	 */
	public function getCharacteristicElementId()
	{
		return $this->characteristic_element_id;
	}

	/**
	 * Sets the characteristic value.
	 * @param mixed $value
	 */
	abstract public function setValue($value);

	/**
	 * Returns the characteristic value
	 * @return mixed
	 */
	public function getValue()
	{
		if ($this->value == '')
		{
			return null;
		}
		else
		{
			return $this->value;
		}
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
	 * @param integer $company_id
	 * @param integer $characteristic_id
	 * @return integer
	 */
	public static function getObjectCharacteristicIdByCompanyIdAndCharacteristicId($company_id, $characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getObjectCharacteristicIdByCompanyIdAndCharacteristicId($company_id, $characteristic_id);
	}
	
}

?>