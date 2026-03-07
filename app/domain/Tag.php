<?php

require_once('app/domain/DomainObject.php');
require_once('app/mapper/TagMapper.php');


/**
 * @package alchemis
 */
class app_domain_Tag extends app_domain_DomainObject
{
	protected $value;
    protected $category_id;
    protected $data_source;

	protected static $valid_types = 	array(	'app_domain_Company'  		=> array(	'table' => 'tbl_company_tags',
																						'field' => 'company_id'),
												'app_domain_Post' 			=> array(	'table' => 'tbl_post_tags',
																						'field' => 'post_id'),
												'app_domain_PostInitiative' => array(	'table' => 'tbl_post_initiative_tags',
																						'field' => 'post_initiative_id'));
	protected $parentDomainObject;

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);

		if ($this->id)
		{

		}
	}


	/**
	 * Set the tag value
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
		$this->markDirty();
	}

	/**
	 * Return the tag value.
	 * @return integer $value
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Set the tag category id.
	 * @param number $category_id the tag category id
	 */
	public function setCategoryId($category_id)
	{
		$this->category_id = $category_id;
		$this->markDirty();
	}

	/**
	 * Return the tag category id.
	 * @return number $category_id the tag category id
	 */
	public function getCategoryId()
	{
		return $this->category_id;
    }

    /**
	 * Set the tag is data source.
	 * @param boolean $data_source if the tag is a data_source
	 */
	public function setIsDataSource($data_source)
	{
        $this->data_source = $data_source;
        $this->markDirty();
    }

    /**
	 * Return the tag is data source.
	 * @return boolean $data_source if the tag is a data_source
	 */
	public function getIsDataSource()
	{
		return $this->data_source;
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
	 * Return the valid types array.
	 * @return number $valid_types the valid types array of the tag
	 */
	public static function getValidTypes()
	{
		return self::$valid_types;
    }

    public function isDataSource()
    {
        $pivot = app_model_PostInitiativeTag::where('tag_id', $this->getId())->first();

        return $pivot ? $pivot->data_source : false;
    }

	/**
	 * Find all tags
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findAll()
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findAll();
	}


	/**
	 * Find all tags by a given id
	 * @param integer $id tag id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function find($id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->find($id);
	}

	public static function findByValue($value)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByValue($value);
	}

	/** Find all tags by a given company id
	 * @param integer $company_id company_id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByCompanyId($company_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyId($company_id);
	}

	/** Find all tags by a given company id and a category id
	 * @param integer $company_id company_id
	 * @param integer $category_id category_id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByCompanyIdAndCategoryId($company_id, $category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyIdAndCategoryId($company_id, $category_id);
	}

	/** Find all tags by a given parent object and a category id
	 * @param integer $parent_object_id parent_object_id
	 * @param integer $category_id category_id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByParentObjectIdAndCategoryId($parent_object_type, $parent_object_id, $category_id)
	{
        $finder = self::getFinder(__CLASS__);
		return $finder->findByParentObjectIdAndCategoryId($parent_object_type, $parent_object_id, $category_id);
	}


	/** Counts the occurence of a tag value by post parent object id and category id
	 * @param integer $parent_object_id parent_object_id
	 * @param integer $category_id category_id
	 * @param string $tag_value value of the tag to check
	 * @return integer
	 */
	public static function countOfTagValueByParentObjectIdAndCategoryId($parent_object_type, $parent_object_id, $category_id, $tag_value)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->countOfTagValueByParentObjectIdAndCategoryId($parent_object_type, $parent_object_id, $category_id, $tag_value);
	}



	/**
	 *
	 * @return app_mapper_TagMapper raw data - one row
	 */
	public static function lookupCategoryById($category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->lookupCategoryById($category_id);
	}

	/** Find all project ref tags for given initiative id
	 * @param integer $initiative_id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findProjectRefByInitiativeId($initiative_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findProjectRefByInitiativeId($initiative_id);
	}

	/** Find all records for a project ref which equals the query string
	 * @param string $project_ref
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByProjectRefEqual($project_ref)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByProjectRefEqual($project_ref);
	}

	/** Find all records for a project ref which starts with the query string
	 * @param string $project_ref
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByProjectRefStart($project_ref)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByProjectRefStart($project_ref);
	}

	/** Find all records for a project ref which includes the query string
	 * @param string $project_ref
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByProjectRefInclude($project_ref)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByProjectRefInclude($project_ref);
	}

	/** Find all records for a company tag which equals the query string
	 * @param string $value - tag value
	 * @param integer $category_id - tag category id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByCompanyTagCategoryIdEqual($value, $category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyTagCategoryIdEqual($value, $category_id);
	}

	/** Find all records for a company tag which start with the query string
	 * @param string $value - tag value
	 * @param integer $category_id - tag category id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByCompanyTagCategoryIdStart($value, $category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyTagCategoryIdStart($value, $category_id);
	}

	/** Find all records for a company tag which include the query string
	  * @param string $value - tag value
	 * @param integer $category_id - tag category id
	 * @return app_mapper_TagCollection collection of app_domain_Tag objects
	 */
	public static function findByCompanyTagCategoryIdIncludes($value, $category_id)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->findByCompanyTagCategoryIdIncludes($value, $category_id);
	}
}







?>