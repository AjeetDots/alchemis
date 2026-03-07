<?php

/**
 * Defines the app_domain_Characteristic class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/mapper/CharacteristicMapper.php');

/**
 * @package Alchemis
 */
class app_domain_Characteristic extends app_domain_DomainObject
{
	/**
	 * @var array
	 */
	protected static $valid_types = array(	'app_domain_Company'  		=> array(	'table' => 'tbl_company_tags',
																					'field' => 'company_id'),
											'app_domain_Post' 			=> array(	'table' => 'tbl_post_tags',
																					'field' => 'post_id'),
											'app_domain_PostInitiative' => array(	'table' => 'tbl_post_initiative_tags',
																					'field' => 'post_initiative_id'));

	/**
	 * Short name of the characteristic.
	 * @var string
	 */
	protected $name;

	/**
	 * Long description of the characteristic.
	 * @var string
	 */
	protected $description;

	/**
	 * Type of characteristic (e.g. comapny, post, post initiative).
	 * @var string
	 */
	protected $type;

	/**
	 * Whether the characteristic has attributes.
	 * @var boolean
	 */
	protected $attributes;

	/**
	 * Whether the characteristic has options to select between.
	 * @var boolean
	 */
	protected $options;

	/**
	 * Whether multiple elements can be selected if the characteristic has
	 * multiple elements.
	 * @var boolean
	 */
	protected $multiple_select;

	/**
	 * The data type of the elements
	 * @var string
	 */
	protected $data_type;

	/**
	 * The characteristic elements
	 * @var app_mapper_CharacteristicElementCollection
	 */
	protected $elements = null;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);
	}

	/**
	 * Sets the characteristic name.
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = trim($name);
		$this->markDirty();
	}

	/**
	 * Returns the characteristic name.
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the characteristic description.
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = trim($description);
		$this->markDirty();
	}

	/**
	 * Returns the characteristic description.
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Sets the characteristic type.
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = trim($type);
		$this->markDirty();
	}

	/**
	 * Returns the characteristic type.
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

//	/**
//	 * Sets whether the characteristic can have multiple elements.
//	 * @param boolean $b
//	 */
//	public function setMultipleElements($b)
//	{
//		$this->multiple_elements = (bool)$b;
//		if (!$b)
//		{
//			$this->multiple_elements = false;
//		}
//		$this->markDirty();
//	}
//
//	/**
//	 * Returns whether the characterstic can have multiple elements.
//	 * @return boolean
//	 */
//	public function hasMultipleElements()
//	{
//		return $this->multiple_elements;
//	}

	/**
	 * Sets whether the characteristic can have attributes. If set to true, the
	 * data type is automatically set to null.
	 * @param boolean $b
	 */
	public function setAttributes($b)
	{
		$this->attributes = (bool)$b;
		if ($this->attributes)
		{
			$this->data_type = null;
		}
		$this->markDirty();
	}

	/**
	 * Returns whether the characterstic can have attributes.
	 * @return boolean
	 */
	public function hasAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Sets whether the characteristic can have options to select between. If
	 * set to false, the multiple select is set to false. If set to true, the
	 * data type is automatically set to null.
	 * @param boolean $b
	 */
	public function setOptions($b)
	{
		$this->options = (bool)$b;
		if (!$this->options)
		{
			$this->multiple_select = false;
		}
		if ($this->options)
		{
			$this->data_type = null;
		}
		$this->markDirty();
	}

	/**
	 * Returns whether the characterstic can have options to select between.
	 * @return boolean
	 */
	public function hasOptions()
	{
		return $this->options;
	}

	/**
	 * Sets whether multiple elements can be selected if the characteristic has
	 * multiple elements.
	 * @param bool $b
	 */
	public function setMultipleSelect($b)
	{
		$this->multiple_select = (bool)$b;
		$this->markDirty();
	}

	/**
	 * Returns whether multiple elements can be selected.
	 * @return string
	 */
	public function hasMultipleSelect()
	{
		return $this->multiple_select;
	}

	/**
	 * Sets the dat type.
	 * @param string $data_type
	 */
	public function setDataType($data_type)
	{
		$this->data_type = $data_type;
		$this->markDirty();
	}

	/**
	 * Returns the data type. If either attributes or options is set, the data
	 * type is set to null and returned.
	 * @return string
	 */
	public function getDataType()
	{
		if ($this->attributes || $this->options)
		{
			$this->data_type = null;
		}
		return $this->data_type;
	}

	/**
	 * Sets the characteristic elements collection.
	 * @param app_mapper_CharacteristicElementCollection $elements
	 */
	public function setElements(app_mapper_CharacteristicElementCollection $elements)
	{
//		foreach ($elements as $e)
//		{
//			echo "<br />* [" . $e->getId() . "] " . $e->getValue() . " - " . $e->getDataType();
//		}
		$this->elements = $elements;
		$this->markDirty();
	}

	/**
	 * Adds an element to the collection.
	 * @param app_mapper_CharacteristicElement $element
	 */
	public function addElement(app_mapper_CharacteristicElement $element)
	{
		$this->elements->add($element);
		$this->markDirty();
	}

	public function setParentDomainObject(app_domain_DomainObject $obj)
	{
		if ($this->isValidType($obj))
		{
			$this->parentDomainObject = $obj;
		}
		else
		{
			throw new Exception('Invalid type');
		}
	}

	public function getParentDomainObject()
	{
		return $this->parentDomainObject;
	}

	protected function isValidType(app_domain_DomainObject $obj)
	{
		return array_key_exists(get_class($obj), self::$valid_types);
	}

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
	 * Returns the number of elements
	 * @return integer
	 */
	public function countElements()
	{
		return $this->elements->count();
	}

	/**
	 *
	 * @param integer $characteristic_id
	 * @return app_mapper_CharacteristicElementCollection
	 */
	public function getElements()
	{
//		$finder = self::getFinder(__CLASS__);
//		return $finder->getElements($characteristic_id);
		return $this->elements;
	}

	/**
	 * Returns a collection of charateristics which are associated with a company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function selectAssociatedWithCompanyId($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->selectAssociatedWithCompanyId($company_id);
	}

	/**
	 * Returns a collection of charateristics which are not associated with a company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function selectAvailableByCompanyId($company_id)
	{
		$finder = app_domain_DomainObject::getFinder(__CLASS__);


		return $finder->selectAvailableByCompanyId($company_id);
	}

	/**
	 * Returns a collection of charateristics which are not associated with a post.
	 * @param integer $post_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function selectAvailableByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->selectAvailableByPostId($post_id);
	}

	/**
	 * Returns a collection of charateristics which are not associated with a post initiative.
	 * @param integer $post_initiative_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function selectAvailableByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->selectAvailableByPostInitiativeId($post_initiative_id);
	}

	/**
	 * Returns the datatype for a given characteristic.
	 * @param integer $characteristic_id
	 * @return string
	 */
	public static function lookupDataType($characteristic_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupDataType($characteristic_id);
	}

	/**
	 * Find characteristics for a given company.
	 * @param integer $company_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function findByCompanyId($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyId($company_id);
	}

 	/** Find characteristics for a given post.
	 * @param integer $post_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function findByPostId($post_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostId($post_id);
	}

	/**
	 * Find characteristics for a given post initiative.
	 * @param integer $post_initiative_id
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function findByPostInitiativeId($post_initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByPostInitiativeId($post_initiative_id);
	}

	/**
	 * Find characteristics for a given type.
	 * @param string $type
	 * @return app_mapper_CharacteristicCollection
	 */
	public static function findByType($type)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByType($type);
	}

	/**
	 * Find a characteristic by name and type.
	 * @param string $name
	 * @param string $type
	 * @return app_domain_Characteristic
	 */
	public static function findByNameAndType($name, $type)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByNameAndType($name, $type);
	}
}

?>