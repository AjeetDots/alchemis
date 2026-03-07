<?php

/**
 * Defines the app_domain_TieredCharacteristic class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/mapper/TieredCharacteristicMapper.php');


/**
 * @package alchemis
 */
class app_domain_TieredCharacteristic extends app_domain_DomainObject 
{
	/**
	 * @var array
	 */
	protected static $valid_types = array(	'app_domain_Company'  		=> array(	'table' => 'tbl_company_tags',
																					'field' => 'company_id'));

	/**
	 * Tiered characteristic value.
	 * @var string
	 */
	protected $value;

	/**
	 * Tiered characteristic category ID.
	 * @var integer
	 */
	protected $category_id;

	/**
	 * Tiered characteristic parent charateristic.
	 * @var integer
	 */
	protected $parent_id = null;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Set the tiered characteristic value
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
		$this->markDirty();
	}

	/**
	 * Return the tiered characteristic value.
	 * @return integer $value
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Set the tiered characteristic category id.
	 * @param number $tiered_characteristic_category_id the tiered characteristic category id
	 */
	public function setCategoryId($category_id)
	{
		$this->category_id = $category_id;
		$this->markDirty();
	}

	/**
	 * Return the tiered characteristic category id.
	 * @return number $category_id the tiered characteristic category id
	 */
	public function getCategoryId()
	{
		return $this->category_id;
	}
	
	/**
	 * Return the tiered characteristic parent.
	 * @return string
	 */
	public function getCategory()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCategory($this->category_id);
	}

	/**
	 * Set the tiered characteristic parent_id. Used for creating a hierachy
	 * @param number $parent_id the tiered characteristic parent id
	 */
	public function setParentId($parent_id)
	{
		if ($parent_id > 0)
		{
			$this->parent_id = $parent_id;
		}
		else
		{
			$this->parent_id = 0;
		}
		$this->markDirty();
	}

	/**
	 * Return the tiered characteristic parent id.
	 * @return number $category_id the tiered characteristic parent id
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * Return the tiered characteristic parent.
	 * @return string
	 */
	public function getParent()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupValue($this->parent_id);
	}

	/**
	 * Determines whether the tiered characteristic has a parent
	 * @return boolean
	 */
	public function hasParent()
	{
		if (empty($this->parent_id) || $this->parent_id < 1)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Find all tiered characteristics
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}

	/**
	 * Find all tiered characteristics
	 * @return array
	 */
	public static function findAllArray()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAllArray();
	}
	
	/**
	 * Return the tiered characteristics which do no have a parent.
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public static function findRootTieredCharacteristics($category_id = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findRootTieredCharacteristics($category_id);
	}

	/** Return the tiered characteristics which do no have a parent.
	 * @return array
	 */
	public static function findRootTieredCharacteristicsArray()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findRootTieredCharacteristicsArray();
	}


	/**
	 * Return the tiered characteristics which do no have a parent.
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public static function findRootTieredCharacteristicsForDropdown()
	{
		$items = self::findRootTieredCharacteristics(1)->toRawArray();
		$array = array();
		foreach ($items as $item)
		{
			$array[$item['id']] = $item['value'];
		}
		return $array;
	}

	/**
	 * Find all tiered characteristics formatted for a drop-down.
	 * @return array
	 */
	public static function findAllForDropdown()
	{
		$items = self::findAll()->toRawArray();
		$array = array();
		foreach ($items as $item)
		{
			if ($item['category'] && true)
			{
				$array[$item['category']][$item['id']] = $item['value'];
			}
			else
			{
				$array[$item['id']] = $item['value'];
			}
		}
		return $array;
	}

	/**
	 * Find all tiered characteristics by a given ID.
	 * @param integer $id tiered characteristic ID
	 * @return app_mapper_TieredCharacteristicCollection collection of app_domain_TieredCharacteristic objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	/**
	 * 
	 * @return app_mapper_TieredCharacteristicMapper raw data - one row
	 */
	public static function lookupCategoryById($category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCategoryById($category_id);
	}
	
	/**
	 * 
	 * @return app_mapper_TieredCharacteristicMapper raw data - one row
	 */
	public static function lookupCategories()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCategories();
	}


	/**
	 * 
	 * @return app_mapper_TieredCharacteristicMapper raw data - one row
	 */
	public static function lookupValue($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupValue($id);
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function lookupCategoriesForDropdown()
	{
		$finder = self::getFinder(__CLASS__);
		$items = $finder->lookupCategories();
		$array = array();
		foreach ($items as $item)
		{
			$array[$item['id']] = $item['name'];
		}
		return $array;
	}

	/**
	 * 
	 * @return app_mapper_TieredCharacteristicMapper raw data - one row
	 */
	public static function lookupByCategoryByIdAndValueAndParentId($category_id, $value, $parent_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupByCategoryByIdAndValueAndParentId($category_id, $value, $parent_id);
	}

	/**
	 * Find the tiered characteristics associated with a given company.
	 * @param integer $company_id
	 * @return app_mapper_TieredCharacteristicCollection collection of app_mapper_TieredCharacteristic objects
	 */
	public static function findByCompanyId($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyId($company_id);
	}
	
	/**
	 * Find the tiered characteristics associated with a given parent company.
	 * @param integer $company_id
	 * @return app_mapper_TieredCharacteristicCollection collection of app_mapper_TieredCharacteristic objects
	 */
	public static function findByParentCompanyId($parent_company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByParentCompanyId($parent_company_id);
	}

	/**
	 * Returns a collection of characteristics which are not associated with a 
	 * company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection 
	 */
	public static function selectAvailableByCompanyId($company_id, $category_id = null)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->selectAvailableByCompanyId($company_id, $category_id);
	}

	/**
	 * Find all tiered characteristics available for a company formatted for a drop-down.
	 * @return array
	 */
	public static function selectAvailableByCompanyIdForDropdown($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		$items = $finder->selectAvailableByCompanyId($company_id, 1)->toRawArray();
		$array = array();
		foreach ($items as $item)
		{
			if ($item['category'])
			{
				$array[$item['category']][$item['id']] = $item['value'];
			}
			// Following section rem'd 18/03/2008 - Phil Henry asked that a top level cat can never be the only way of classifying
			// a company - the user should only be able to select a subcat (and tier), the top level cat being assigned by virtue of being the 
			// parent of the selected subcat.
			//else
			//{
			//	$array[$item['id']] = $item['value'];
			//}
		}
		return $array;
	}
	
	/**
	 * Find all tiered sub characteristics formatted for a drop-down.
	 * @return array
	 */
	public static function selectAllSubCategoriesForDropdown()
	{
		$finder = self::getFinder(__CLASS__);
		$items = $finder->selectAllForDropdown(1)->toRawArray();
		$array = array();
		foreach ($items as $item)
		{
			if ($item['category'])
			{
				$array[$item['category']][$item['id']] = $item['value'];
			}
		}
		return $array;
	}
}

?>