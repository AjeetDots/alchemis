<?php

require_once('app/domain/DomainObject.php');
require_once('app/mapper/ObjectTieredCharacteristicMapper.php');


/**
 * @package alchemis
 */
class app_domain_ObjectTieredCharacteristic extends app_domain_DomainObject
{
	/**
	 * Holds database connection resource
	 * @var resource
	 */
	protected static $valid_types = array('app_domain_Company');

	/**
	 * The ID of the parent object against which the characteristic is linked - always company
	 * @var integer
	 */
	protected $parent_object_id;

	/**
	 * The ID of the parent company against which the characteristic is linked.
	 * @var integer
	 */
	protected $parent_company_id;

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
	 * The object characteristic tier.
	 * @var mixed
	 */
	protected $tier;

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

	public function setParentCompanyId($id)
	{
		$this->parent_company_id = $id;
		$this->markDirty();
	}

	public function getParentCompanyId()
	{
		return $this->parent_company_id;
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
	 * Set the tiered characteristic id
	 * @param string $tiered_characteristic_id
	 */
	public function setTieredCharacteristicId($tiered_characteristic_id)
	{
		$this->tiered_characteristic_id = $tiered_characteristic_id;
		$this->markDirty();
	}

	/**
	 * Return the tiered characteristic id.
	 * @return integer $tiered_characteristic_id
	 */
	public function getTieredCharacteristicId()
	{
		return $this->tiered_characteristic_id;
	}

	/**
	 * Set the tier. Used for passing through user input - not saved as part of this object
	 * @param number $tier the tier for this tiered characteristic for the associated parent id
	 */
	public function setTier($tier)
	{
		$this->tier = $tier;
		$this->markDirty();
	}

	/**
	 * Return the tier
	 * @return number $tier the  tier for this tiered characteristic for the associated parent id
	 */
	public function getTier()
	{
		if (
			(empty($this->parent_object_id) || $this->parent_object_id == 0) &&
			(empty($this->parent_company_id) || $this->parent_company_id == 0)
		)
		{
			$this->tier = 0;
		}
		return $this->tier;
	}

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


	/**
	 * Find a characteristic by a given company_id and a tiered_characterostic_id.
	 * @param integer $id
	 * @param integer $tiered_characterostic_id
	 * @return app_domain_ObjectTieredCharacteristic object
	 */
	public static function findByCompanyIdAndTieredCharacterisicId($company_id, $tiered_characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyIdAndTieredCharacterisicId($company_id, $tiered_characteristic_id);
	}

	/**
	 * Find a characteristic by a given company_id and a tiered_characterostic_id.
	 * @param integer $id
	 * @param integer $tiered_characterostic_id
	 * @return app_domain_ObjectTieredCharacteristic object
	 */
	public static function findByParentCompanyIdAndTieredCharacterisicId($company_id, $tiered_characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByParentCompanyIdAndTieredCharacterisicId($company_id, $tiered_characteristic_id);
	}

	/**
	 * Determine if a given object (company) is associated with a given tiered characteristic.
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return boolean
	 */
	public static function isAssociated($object_id, $tiered_characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->isAssociated($object_id, $tiered_characteristic_id);
	}

	/**
	 * Determine if a given object (company) is associated with a given tiered characteristic.
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return boolean
	 */
	public static function isAssociatedParent($parent_company_id, $tiered_characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->isAssociated($parent_company_id, $tiered_characteristic_id);
	}

		/**
	 * Determine how many sub-cats a top level cat has for a given object (company).
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return integer
	 */
	public static function countTieredCharacteristicByCompanyIdAndTieredCharacteristicId($object_id, $tiered_characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countTieredCharacteristicByCompanyIdAndTieredCharacteristicId($object_id, $tiered_characteristic_id);
	}

	/**
	 * Determine how many sub-cats a top level cat has for a given object (company).
	 * @param integer $object_id
	 * @param integer $tiered_characteristic_id
	 * @return integer
	 */
	public static function countTieredCharacteristicByParentCompanyIdAndTieredCharacteristicId($object_id, $tiered_characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countTieredCharacteristicByParentCompanyIdAndTieredCharacteristicId($object_id, $tiered_characteristic_id);
	}


}

?>